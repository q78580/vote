<?php
namespace fnsoxt;

use yii\filters\auth\AuthMethod;

/**
 * QueryParamAuth is an action filter that supports the authentication based on the access token passed through a query parameter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MpAuth extends AuthMethod
{
    /**
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = explode(';',$request->headers['cookie']);
        if(count($accessToken)>1){
            foreach($accessToken as $key => $value){
                $accessToken = $this->ex($value);
                if($accessToken !==false){
                    break;
                }
            }
        }else{
            $accessToken = $this->ex($accessToken[0]);
        }
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }

    private function ex($as){
        $result = explode('token=',$as);
        if(count($result)>1){
            return  urldecode($result[1]);
        }else{
            return false;
        }
    }
}
