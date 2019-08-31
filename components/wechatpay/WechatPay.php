<?php
namespace components\wechatpay;

use Yii;
use yii\base\InvalidParamException;
use yii\base\InvalidConfigException;
use yii\web\HttpException;
use yii\helpers\Json;

class WechatPay
{
    /**
     * @var String APPID 微信公众号唯一标识
     */
    public $appId;

    /**
     * @var String mchId 微信支付商户号
     * 商户申请微信支付后，由微信支付分配的商户收款账号
     */
    public $mchId;

    /**
     * @var String key API密钥
     * 交易过程生成签名的密钥，仅保留在商户系统和微信支付后台，不会在网络中传播
     */
    public $key;

    /**
     * @var String AppSecret
     * AppSecret是APPID对应的接口密码，用于获取接口调用凭证access_token时使用
     */
    public $appSecret;

    /**
     * @var string SSL_CERT_PATH 微信支付接口SSL证书所在路径
     */
    public $sslCertPath = '@components/wechatpay/certs/apiclient_cert.pem';
    public $sslKeyPath = '@components/wechatpay/certs/apiclient_key.pem';

    /**
     * @var string 代理服务器地址
     */
    public $curlProxyHost = '0.0.0.0';

    /**
     * @var int 代理服务器端口号
     */
    public $curlProxyPort = 0;

    /**
     * @var bool 启用安全支付标题
     * 是否启用微信安全支付Title
     */
    public $enableSafeTitle = true;

    /**
     * @var array 请求参数
     * 向接口服务器发送请求的参数
     */
    protected $requestParams = [];

    /**
     * 微信支付接口基本地址
     */
    const WECHAT_PAY_BASE_URL = 'https://api.mch.weixin.qq.com/pay';

    const WECHAT_OAUTH2_ACCESS_TOKEN_PREFIX = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * 统一下单地址
     */
    const UNIFIED_ORDER_URL = '/unifiedorder';

    /**
     * 微信被扫支付地址
     */
    const MICRO_PAY_RUL = '/micropay';

    /**
     * 订单查询地址
     */
    const ORDER_QUERY_URL = '/orderquery';

    /**
     * 关闭订单地址
     */
    const CLOSE_ORDER_URL = '/closeorder';

    /**
     * 申请退款接口地址
     */
    const REFUND_URL = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

    /**
     * 退款查询地址
     */
    const REFUND_QUERY_URL = '/refundquery';

    /**
     * 下载对账单地址
     */
    const DOWNLOAD_BILL_URL = '/downloadbill';

    /**
     * 转换短链接地址
     */
    const SHORT_URL = 'https://api.mch.weixin.qq.com/tools/shorturl';

    /**
     * 原生支付URL
     */
    const BIZ_PAY_URL = 'weixin://wxpay/bizpayurl?';

    /**
     * 网页授权获取用户信息
     */
    const WECHAT_OAUTH2_AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    /**
     * 发送红包
     */
    const WECHAT_SEND_RED_PACK_RUL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

    /**
     * 查询红包
     */
    const GET_HB_INFO = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gethbinfo';

    /**
     * @var array 许可的参数列表
     */
    public $licenseParams = [
        'appid',
        'mch_id',
        'device_info',
        'nonce_str',
        'sign',
        'body',
        'detail',
        'attach',
        'out_trade_no',
        'fee_type',
        'total_fee',
        'spbill_create_ip',
        'time_start',
        'time_expire',
        'goods_tag',
        'notify_url',
        'trade_type',
        'product_id',
        'openid',
        'transaction_id',
        'out_trade_no',
        'out_refund_no',
        'refund_fee',
        'refund_fee_type',
        'op_user_id',
        'refund_id',
        'bill_date',
        'bill_type',
        'long_url',
        'time_stamp',
        'return_code',
        'return_msg',
        'result_code',
        'prepay_id',
        'code_url',
        'timeStamp',
        'package',
        'signType',
        'paySign',
        'auth_code',
        'err_code',
        'err_code_des',
        'is_subscribe'
    ];


    public function init()
    {
        parent::init();
        if ($this->mchId === null) {
            throw new InvalidConfigException('The mchId property must be set.');
        } elseif ($this->key === null) {
            throw new InvalidConfigException('The key property must be set.');
        }
    }

    /**
     * 设置单个请求参数
     * @param $key
     * @param $value
     */
    public function setRequestParam($key, $value)
    {
        if (empty($key)) {
            throw new InvalidParamException('使用了非法参数');
        }
        $this->requestParams[$key] = $value;
    }

    /**
     * 设置多个请求参数
     * @param array $params
     */
    public function setRequestParams($params = [])
    {
        if (!empty($params)) {
            foreach($params as $key => $value) {
                $this->setRequestParam($key, $value);
            }
        }
    }

    /**
     * 得到请求参数
     * @return array
     */
    public function getRequestParams()
    {
        $this->setRequestParam('appid', $this->appId);
        $this->setRequestParam('mch_id', $this->mchId);
        $this->setRequestParam('nonce_str', $this->getNonceStr());
        return $this->requestParams;
    }

    /**
     * 得到32位随机字符串
     * @return string
     */
    public function getNonceStr()
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * 生成(本地系统)订单号
     * @return string
     */
    public function generateOrderNo()
    {
        return $this->mchId . date('YmdHis');
    }

    /**
     * 取得请求参数的XML
     * @return string
     */
    public function getRequestXml()
    {
        if(!is_array($this->requestParams) || count($this->requestParams) <= 0) {
            throw new HttpException(500, "数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($this->requestParams as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 得到参数组合的签名
     * @param array $params
     * @return bool|string
     */
    public function getSign($params = [])
    {
        if (empty($params)) {
            return false;
        }

        //签名步骤一：按字典序排序参数
        ksort($params);

        $buff = "";
        foreach ($params as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        $string = $buff;
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=". $this->key;
        Yii::info($string);
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 将XML装换成数组格式
     * @param $xml
     * @return mixed
     */
    public static function xmlToArray($xml)
    {
        if (empty($xml)) {
            throw new InvalidParamException('XML数据异常');
        }
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 得到原生支付URL(扫码支付模式一)
     * 用于生成二维码
     * @return string
     */
    public function getNativeBizPayUrl($product_id)
    {
        if (empty($product_id)) {
            return false;
        }

        $this->setRequestParam('product_id', $product_id);
        $this->setRequestParam('time_stamp', time());

        $requestParams = $this->getRequestParams();

        $this->setRequestParam('sign', $this->getSign($requestParams));

        return static::BIZ_PAY_URL . http_build_query($this->requestParams);
    }

    /**
     * 获取生成二维码的支付地址
     * @param $data
     * @return bool
     * @throws HttpException
     */
    public function getCodeUrl($data)
    {
        if (empty($data)) {
            return false;
        }

        $this->setRequestParams($data);
        $result = $this->unifiedOrder();

        if (!isset($result['code_url'])) {
            throw new HttpException(500, '从服务器获取code_url失败');
        }

        return $result['code_url'];
    }

    /**
     * 获取网页授权的微信用户的Openid
     * @return mixed
     * @throws HttpException
     */
    public function getOpenId()
    {
        //取得微信公众号code再使用code请求微信用户的openid
        if (($code = Yii::$app->request->get('code')) === null) {
            $currentUrl =  Yii::$app->getRequest()->getAbsoluteUrl();
            Yii::$app->response->redirect($this->getOauth2AuthorizeUrl($currentUrl, 'STATE'));
        }

        $result = $this->getOauth2AccessToken($code);
        if (!isset($result['openid'])) {
            throw new HttpException(500, '从服务器获取Openid失败');
        }

        return $result['openid'];
    }

    /**
     * 获取微信统一支付返回的预付id
     * @param $data
     * @return bool
     */
    public function getPrepayId($data)
    {
        if (empty($data)) {
            return false;
        }
        $this->setRequestParams($data);
        $result = $this->unifiedOrder();
        if (!isset($result['prepay_id'])) {
            throw new HttpException(500, '从服务器获取prepay_id失败');
        }
        return $result['prepay_id'];
    }

    /**
     * 获取JSAPI支付的配置参数
     * @param $data
     * @return bool|string
     * @throws HttpException
     */
    public function getJsApiParameter($data)
    {
        if (empty($data)) {
            return false;
        }

        if (!isset($data['trade_type']) || $data['trade_type'] !== 'JSAPI') {
            throw new HttpException(500, '交易类型错误!');
        }

        if (!isset($data['openid'])) {
            $data['openid'] = $this->getOpenId();
        }

        $prepayId = $this->getPrepayId($data);
        $parameters = [
            'appId' => $this->appId,
            'nonceStr' => $this->getNonceStr(),
            'package' => "prepay_id={$prepayId}",
            'signType' => 'MD5',
            'timeStamp' => (string)time(),
        ];
        $parameters['paySign'] = $this->getSign($parameters);

        return $parameters;
    }

    /**
     * 调用统一下单支付
     */
    public function unifiedOrder()
    {
        $requestParams = $this->getRequestParams();

        if (!isset($requestParams['out_trade_no'])) {
            throw new InvalidParamException('缺少统一支付接口必填参数out_trade_no!');
        } elseif (!isset($requestParams['body'])) {
            throw new InvalidParamException('缺少统一支付接口必填参数body!');
        } elseif (!isset($requestParams['total_fee'])) {
            throw new InvalidParamException('缺少统一支付接口必填参数total_fee!');
        } elseif (!isset($requestParams['trade_type'])) {
            throw new InvalidParamException('缺少统一支付接口必填参数trade_type!');
        } elseif (!isset($requestParams['notify_url'])) {
            throw new InvalidParamException('缺少统一支付接口必填参数notify_url!');
        }

        if ($requestParams['trade_type'] === 'JSAPI' && !isset($requestParams['openid'])) {
            throw new InvalidParamException('统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数!');
        }

        if ($requestParams['trade_type'] === 'NATIVE' && !isset($requestParams['product_id'])) {
            throw new InvalidParamException('统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数!');
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));
        $requestXml = $this->getRequestXml();
        $result = $this->postXmlCurl($requestXml, static::UNIFIED_ORDER_URL);

        return $this->parseResponse($result);
    }

    /**
     * 调用刷卡支付
     * @return array
     */
    public function microPay()
    {
        $requestParams = $this->getRequestParams();

        if(!isset($requestParams['body'])) {
            throw new InvalidParamException("提交被扫支付API接口中，缺少必填参数body！");
        } else if(!isset($requestParams['out_trade_no'])) {
            throw new InvalidParamException("提交被扫支付API接口中，缺少必填参数out_trade_no！");
        } else if(!isset($requestParams['total_fee'])) {
            throw new InvalidParamException("提交被扫支付API接口中，缺少必填参数total_fee！");
        } else if(!isset($requestParams['auth_code'])) {
            throw new InvalidParamException("提交被扫支付API接口中，缺少必填参数auth_code！");
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));

        $xml = $this->getRequestXml();

        $result = $this->postXmlCurl($xml, static::MICRO_PAY_RUL);

        return $this->parseResponse($result);

    }

    /**
     * 查询订单
     * @return array
     */
    public function orderQuery()
    {
        $requestParams = $this->getRequestParams();

        //检测必填参数
        if(!isset($requestParams['out_trade_no']) && !isset($requestParams['transaction_id'])) {
            throw new InvalidParamException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));
        $xml = $this->getRequestXml();
        $result = $this->postXmlCurl($xml, static::ORDER_QUERY_URL);

        return $this->parseResponse($result);
    }

    /**
     * 关闭订单
     * @return array
     */
    public function closePaymentOrder()
    {
        $requestParams = $this->getRequestParams();
        if (!isset($requestParams['out_trade_no'])) {
            throw new InvalidParamException("关闭订单时, out_trade_no商户订单号必须填写");
        }
        $this->setRequestParam('sign', $this->getSign($requestParams));
        $xml = $this->getRequestXml();
        $result = $this->postXmlCurl($xml, static::CLOSE_ORDER_URL);
        return $this->parseResponse($result);
    }

    /**
     * 申请退款
     * @return array
     */
    public function refund()
    {
        $requestParams = $this->getRequestParams();
        /**
         * 微信订单号(transaction_id)与商户订单号(out_trade_no)必须填写一个当两个都填写时
         * 优先级 transaction_id > out_trade_no
         */
        if (!isset($requestParams['transaction_id']) && !isset($requestParams['out_trade_no'])) {
            throw new InvalidParamException('申请退款接口中transaction_id与out_trade_no参数至少填写一个');
        } elseif (!isset($requestParams['out_refund_no'])) {
            throw new InvalidParamException('缺少必填参数out_refund_no!');
        } elseif (!isset($requestParams['total_fee'])) {
            throw new InvalidParamException('缺少必填参数total_fee!');
        } elseif (!isset($requestParams['refund_fee'])) {
            throw new InvalidParamException('缺少必填参数refund_fee');
        } elseif (!isset($requestParams['op_user_id'])) {
            throw new InvalidParamException('缺少必填参数op_user_id');
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));
        $result = $this->postXmlCurl($this->getRequestXml(), static::REFUND_URL);
        return $this->parseResponse($result);
    }

    /**
     * 查询退款信息
     * @return array
     */
    public function refundQuery()
    {
        $requestParams = $this->getRequestParams();
        /**
         * refund_id、out_refund_no、out_trade_no、transaction_id四个参数必填一个，如果同时存在优先级为：
         * refund_id>out_refund_no>transaction_id>out_trade_no
         */
        if (!isset($requestParams['refund_id']) &&
            !isset($requestParams['out_refund_no']) &&
            !isset($requestParams['out_trade_no']) &&
            !isset($requestParams['transaction_id'])) {
            throw new InvalidParamException('refund_id,out_refund_no,transaction_id,out_trade_no至少填写一个');
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));
        $result = $this->postXmlCurl($this->getRequestXml(), static::REFUND_QUERY_URL);
        return $this->parseResponse($result);
    }

    /**
     * 下载对账单
     * @return mixed|string
     */
    public function downloadBill()
    {
        $requestParams = $this->getRequestParams();
        if (!isset($requestParams['bill_date'])) {
            throw new InvalidParamException('缺少必填参数bill_date');
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));

        $response = self::postXmlCurl($this->getRequestXml(), static::DOWNLOAD_BILL_URL);
        if(substr($response, 0 , 5) == "<xml>"){
            return "";
        }
        return $response;

    }


    /**
     * 发送红包
     * @return mixed
     */
    public function sendRedPack()
    {
        $requestParams = $this->getRequestParams();
        if (!isset($requestParams['mch_billno'])) {
            throw new InvalidParamException('缺少必要参数mch_billno');
        } elseif (!isset($requestParams['nick_name'])) {
            throw new InvalidParamException('缺少必要参数nick_name');
        } elseif (!isset($requestParams['re_openid'])) {
            throw new InvalidParamException('缺少必要参数re_openid');
        } elseif (!isset($requestParams['total_amount'])) {
            throw new InvalidParamException('缺少必要参数total_amount');
        } elseif (!isset($requestParams['min_value'])) {
            throw new InvalidParamException('缺少必要参数min_value');
        } elseif (!isset($requestParams['max_value'])) {
            throw new InvalidParamException('缺少必要参数max_value');
        } elseif (!isset($requestParams['total_num'])) {
            throw new InvalidParamException('缺少必要参数total_num');
        } elseif (!isset($requestParams['wishing'])) {
            throw new InvalidParamException('缺少必要参数wishing');
        } elseif (!isset($requestParams['client_ip'])) {
            throw new InvalidParamException('缺少必要参数client_ip');
        } elseif (!isset($requestParams['act_name'])) {
            throw new InvalidParamException('缺少必要参数act_name');
        } elseif (!isset($requestParams['remark'])) {
            throw new InvalidParamException('缺少必要参数remark');
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));

        //$result = $this->postXmlCurl($this->getRequestXml(), static::WECHAT_SEND_RED_PACK_RUL, true);

        return $this->parseHttp(static::WECHAT_SEND_RED_PACK_RUL, true);

        return $this->parseResponse($result);

    }

    /**
     * 查询红包
     * @return mixed
     */
    public function getRedPack()
    {
        $requestParams = $this->getRequestParams();
        if (!isset($requestParams['mch_billno'])) {
            throw new InvalidParamException('缺少必要参数mch_billno');
        } elseif (!isset($requestParams['bill_type'])) {
            throw new InvalidParamException('缺少必要参数bill_type');
        }

        $this->setRequestParam('sign', $this->getSign($requestParams));
        $result = $this->postXmlCurl($this->getRequestXml(), static::GET_HB_INFO, true);
        return $this->parseResponse($result);
    }

    /**
     * 解析响应数据
     * @param $responseXml
     * @return mixed
     */
    protected function parseResponse($responseXml)
    {
        $result = static::xmlToArray($responseXml);
        //$this->setRequestParams($response);
        if ($result['return_code'] === 'FAIL' && isset($result['return_msg'])) {
            Yii::error(['response' => $result], 'wechatPay');
            throw new HttpException(500, $result['return_msg']);
        }

        if (YII_DEBUG) {
            Yii::info(['response' => $result]);
        }

        return $result;
    }


    protected function parseHttp($url, $useCert = false, $second = 30)
    {
        if (YII_DEBUG) {
            Yii::info(['request' => $this->getRequestParams()], 'wechatPay');
        }

        $response = $this->postXmlCurl($this->getRequestXml(), $url, $useCert, $second);

        $result = static::xmlToArray($response);

        if ($result['return_code'] === 'FAIL' && isset($result['return_msg'])) {
            Yii::error($result['return_msg'], 'wechatPay');
            throw new HttpException(500, $result['return_msg']);
        }

        if (YII_DEBUG) {
            Yii::info(['response' => $result]);
        }

        return $result;
    }

    /**
     * 使用CURL提交XML数据
     * @param $xml
     * @param $url
     * @param bool $useCert
     * @param int $second
     * @return mixed
     */
    protected function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        if (stripos($url, 'http://') === false && stripos($url, 'https://') === false) {
            $url = self::WECHAT_PAY_BASE_URL . $url;
        }

        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if($this->curlProxyHost != "0.0.0.0"
            && $this->curlProxyPort != 0){
            curl_setopt($ch,CURLOPT_PROXY, $this->curlProxyHost);
            curl_setopt($ch,CURLOPT_PROXYPORT, $this->curlProxyPort);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, Yii::getAlias($this->sslCertPath));
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, Yii::getAlias($this->sslKeyPath));
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            Yii::error($error, 'wechatPay');
            throw new HttpException(500, "curl出错，错误码:$error");
        }
    }
    public function getOauth2AuthorizeUrl($redirectUrl, $state = 'authorize', $scope = 'snsapi_base')
    {
        return $this->httpBuildQuery(self::WECHAT_OAUTH2_AUTHORIZE_URL, [
            'appid' => $this->appId,
            'redirect_uri' => $redirectUrl,
            'response_type' => 'code',
            'scope' => $scope,
            'state' => $state,
        ]) . '#wechat_redirect';
    }
    protected function httpBuildQuery($url, array $options)
    {
        if (!empty($options)) {
            $url .= (stripos($url, '?') === null ? '&' : '?') . http_build_query($options);
        }
        return $url;
    }
    public function getOauth2AccessToken($code, $grantType = 'authorization_code')
    {
        $result = $this->httpGet(self::WECHAT_OAUTH2_ACCESS_TOKEN_PREFIX, [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'code' => $code,
            'grant_type' => $grantType
        ]);
        return !array_key_exists('errcode', $result) ? $result : false;
    }
    public function httpGet($url, array $options = [])
    {
        Yii::info([
            'url' => $url,
            'options' => $options
        ], __METHOD__);
        return $this->parseHttpRequest(function($url) {
            return $this->http($url);
        }, $this->httpBuildQuery($url, $options));
    }
    public function parseHttpRequest(callable $callable, $url, $postOptions = null, $force = true)
    {
        $result = call_user_func_array($callable, [$url, $postOptions]);
        if (isset($result['errcode']) && $result['errcode']) {
            $this->lastError = $result;
            Yii::warning([
                'url' => $url,
                'result' => $result,
                'postOptions' => $postOptions
            ], __METHOD__);
            switch ($result ['errcode']) {
                case 40001: //access_token 失效,强制更新access_token, 并更新地址重新执行请求
                    if ($force) {
                        $url = preg_replace_callback("/access_token=([^&]*)/i", function(){
                            return 'access_token=' . $this->getAccessToken(true);
                        }, $url);
                        $result = $this->parseHttpRequest($callable, $url, $postOptions, false); // 仅重新获取一次,否则容易死循环
                    }
                    break;
            }
        }
        return $result;
    }
    protected function http($url, $options = [])
    {
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
        ] + (stripos($url, "https://") !== false ? [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1 // 微信官方屏蔽了ssl2和ssl3, 启用更高级的ssl
        ] : []) + $options;
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $content = curl_exec($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);
        if (isset($status['http_code']) && $status['http_code'] == 200) {
            return json_decode($content, true) ?: false; // 正常加载应该是只返回json字符串
        }
        Yii::error([
            'result' => $content,
            'status' => $status
        ],  __METHOD__);
        return false;
    }
} 
