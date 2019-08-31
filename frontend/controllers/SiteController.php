<?php
namespace frontend\controllers;

use common\models\OrderLog;
use common\models\Orders;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\authclient\InvalidResponseException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => Yii::$app->params['cors_origin'],
                    'Access-Control-Allow-Credentials' => true,
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    public function successCallback($client) {
        Yii::$app->request->enableCookieValidation = false;
        $cookies = Yii::$app->response->cookies;

        $first = false;
        $index = 0;
        if (!\Yii::$app->user->isGuest) {
            $data=['err'=>0,'msg'=>'已登录状态'];
            $token = Yii::$app->user->identity->getOldAttribute('password_hash');
        }
        else{
            $userinfo = $client->getUserAttributes();
            $data=[];
            if(($model_user = User::findByUsername($userinfo['openid'])) !== null){
                $model_user->updateAttributes(['password'=>$client->accessToken->token]);

                if(($model_profile = Profile::findOne($model_user['id'])) === null){
                    $model_profile = new Profile();
                }
                $model_profile->load(['Profile'=>['nickname'=>$userinfo['nickname'],'avatar'=>$userinfo['headimgurl'],'id'=>$model_user['id']]]);
                if(!$model_profile->save())
                    $data=['err'=>1,'msg'=>$model_profile->getErrors()];

                if (! Yii::$app->user->login($model_user,7*24*3600))
                    $data=['err'=>1,'msg'=>'登录失败'];
                $token = $model_user->getOldAttribute('password_hash');
            }
            else{
                $model = new SignupForm();
                $sign_data['SignupForm'] = [
                    'username'=>$userinfo['openid'],
                    'password'=>$client->accessToken->token,
                    'email'=>$userinfo['openid'].'@g.g',
                ];
                if ($model->load($sign_data)) {
                    if ($user = $model->signup()) {
                        $first = true;
                        $index = $user['id'];
                        $model_profile = new Profile();
                        $model_profile->load(['Profile'=>['nickname'=>$userinfo['nickname'],'avatar'=>$userinfo['headimgurl'],'id'=>$user['id']]]);
                        if(!$model_profile->save())
                            $data=['err'=>1,'msg'=>$model_profile->getErrors()];

                        if (! Yii::$app->getUser()->login($user,7*24*3600))
                            $data=['err'=>1,'msg'=>'登录失败'];
                        $token = $user->getOldAttribute('password_hash');
                    }
                    else
                        $data=['err'=>1,'msg'=>'注册失败'];
                }
            }
        }
        $data=$data?$data:['err'=>0,'msg'=>'success'];
        if(!empty($token))
            $cookies->add(new \yii\web\Cookie([
                'name' => 'token',
                'value' => $token,
                'expire' => time()+7*24*3600,
                'httpOnly' => false,
            ]));
        if(!$data['err'] && $first){
            $data['first'] = true;
            $data['index'] = $index+3456;
        }
//        if(isset($userinfo['headimgurl']))
//            $cookies->add(new \yii\web\Cookie([
//                'name' => 'avatar',
//                'value' => $userinfo['headimgurl'],
//                'expire' => time()+7*24*3600,
//                'httpOnly' => false,
//            ]));
//        if(isset($userinfo['nickname']))
//            $cookies->add(new \yii\web\Cookie([
//                'name' => 'nickname',
//                'value' => $userinfo['nickname'],
//                'expire' => time()+7*24*3600,
//                'httpOnly' => false,
//            ]));
        $return_url = Yii::$app->request->get('return');
        if(strpos($return_url,'?')===false)
            $url = $return_url.'?'. http_build_query($data);
        else
            $url = $return_url.'&'. http_build_query($data);
        Yii::trace("url:".$url);
        $this->redirect($url);
    }


    public function actionWx(){
        $oauthClient = Yii::$app->authClientCollection->getClient('weixin');
        $code = Yii::$app->request->get('code');
        Yii::info($code);
        if($code){
            $host = substr(Yii::$app->request->get('host'),7);
            $return = Yii::$app->request->get('return');
            $state = Yii::$app->request->get('state');
            Yii::info($host);
            Yii::info($state);
            $url = sprintf('http://%s/site/auth?q=%%2Fsite%%2Fauth&authclient=weixin&return=%s&code=%s&state=%s',$host,urlencode($return),$code,$state);
            Yii::info($url);
            Yii::$app->getResponse()->redirect($url);
        }
        else{
            $url = $oauthClient->buildAuthUrl();
            Yii::info($url);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    public function actionWxj(){
        $oauthClient = Yii::$app->authClientCollection->getClient('weixin_basic');
        $code = Yii::$app->request->get('code');
        if($code){
            $host = Yii::$app->request->get('host');
            $return = Yii::$app->request->get('return');
            $state = Yii::$app->request->get('state');
            $url = sprintf('%s/site/auth?q=%%2Fsite%%2Fauth&authclient=weixin&return=%s&code=%s&state=%s',$host,urlencode($return),$code,$state);
            Yii::info($url);
            Yii::$app->getResponse()->redirect($url);
        }
        else{
            $url = $oauthClient->buildAuthUrl();
            Yii::info($url);
            Yii::$app->getResponse()->redirect($url);
        }
    }

    public function actionWxx(){
        $code = Yii::$app->request->get('code');
//        Yii::info($code);
        if($code) {
            $auth = $oauthClient = Yii::$app->authClientCollection->getClient('weixin');
            $defaultParams = [
                'appid' => $auth->clientId,
                'secret' => $auth->clientSecret,
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ];

            $request = $auth->createRequest()
                ->setMethod('POST')
                ->setUrl($auth->authUrl)
                ->setData($defaultParams);

            $response = $request->send();

            if (!$response->getIsOk()) {
                throw new InvalidResponseException($response, 'Request failed with code: ' . $response->getStatusCode() . ', message: ' . $response->getContent());
            }

            $response = $response->getData();
            Yii::info($response);
            $result = $this->actionSign($response);
            return json_encode($result);
        }else{
            throw new NotFoundHttpException('code not found');
        }
    }

    public function actionSign($response){
        $session_id = base64_encode($response['openid'].$response['session_key']);
        $model = User::find()->where(['username'=>$response['openid']])->one();
        if(empty($model)){
            $sign_data['SignupForm'] = [
                'username'=> $response['openid'],
                'password'=> $session_id,
                'email'=>$response['session_key'].'@g.g'
            ];            $model = new SignupForm();
            if($model->load($sign_data)){
                if ($user = $model->signup()) {
                    $token = $user->getAttribute('password_hash');
                }
            }
        }else{
            $model->password = $session_id;

            $model->email = $response['session_key'].'@g.g';
            if ($model->update()) {
                $token = $model->getAttribute('password_hash');
            }
        }
        return [
            'skey'=> $token
        ];

    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionIsguest()
    {
        Yii::$app->request->enableCookieValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $identity = Yii::$app->user->identity;
        $isGuest = Yii::$app->user->isGuest;
        $data = [
            'isguest'=>$isGuest,
        ];
        if(!$isGuest){
            $data['token'] = $identity->password_hash;
        }
        return $data;
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
