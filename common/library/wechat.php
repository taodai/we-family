<?php
namespace common\library;
use yii;
/**
 * 微信类
 * 服务器端必须要有 CURL 支持
 */
class wechat{
    /* 获取ACCESS_TOKEN URL */
	const AUTH_URL = 'https://api.weixin.qq.com/cgi-bin/token';
	/* 统一下单地址 */
	const UNIFIED_ORDER_URL   = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
	/* OAuth2.0授权地址 */
	const OAUTH_AUTHORIZE_URL     = 'https://open.weixin.qq.com/connect/oauth2/authorize';
	const OAUTH_USER_TOKEN_URL  = 'https://api.weixin.qq.com/sns/oauth2/access_token';
	const OAUTH_GET_USERINFO	  = 'https://api.weixin.qq.com/sns/userinfo';
	/* 订单状态查询 */
	const ORDER_QUERY_URL  = 'https://api.mch.weixin.qq.com/pay/orderquery';
	private $result;
	private $error;
	
	private $token;
	private $appid;
	private $secret;
	private $access_token;
	private $mch_id;  //商户号（微信支付必须配置）
	private $payKey; //商户支付密钥（微信支付必须配置）
	
	public function __construct($options = array()){
	    $this->token = isset($options['token']) ? $options['token'] : '';
	    $this->appid = isset($options['appid']) ? $options['appid'] : '';
	    $this->secret = isset($options['secret']) ? $options['secret'] : '';
	    $this->access_token = isset($options['access_token']) ? $options['access_token'] : '';
	    $this->mch_id = isset($options['mch_id']) ? $options['mch_id'] : '';
	    $this->payKey = isset($options['payKey']) ? $options['payKey'] : '';
	}
	
	public function __get($key) {
	    return $this->$key;
	}
	
	public function __set($key, $value) {
	    $this->$key = $value;
	}
	
	public function getError() {
	    return $this->error;
	}
    /**
     * 验证URL有效性
     */
	public function validate(){
	    $echoStr = \Yii::$app->request->get('echostr');
	    if(isset($echoStr)){
	        $this->checkSignature() && exit($echoStr);
	    }else{
	        !$this->checkSignature() && exit('Access Denied!');
	    }
	    return true;
	}
	
	/**
	 * 签名
	 * @return boolean
	 */
	public function checkSignature(){
	    $signature = \Yii::$app->request->get('signature');
	    $timestamp = \Yii::$app->request->get('timestamp');
	    $nonce = \Yii::$app->request->get('nonce');
	    if(empty($signature) || empty($timestamp)  || empty($nonce)) return false;
	    $token = $this->token;
	    if(!$token) return false;
	    $tempArr = array($token,$timestamp,$nonce);
	    sort($tempArr,SORT_STRING);
	    $temStr = implode($tempArr);
	    return sha1($temStr) == $signature;
	}
	/**
	 * 获取ACCESS_TOKEN
	 * @return string|boolean
	 */
    public function getToken() {
		$access_token = $this->access_token;
		if (!empty($access_token)) {
			return $this->access_token;
		}else {
			if ($this->getAccessToken()) {
				return $this->access_token;
			}else {
				return false;
			}
		}
	}
	/**
	 * 获取ACCESS_TOKEN
	 * @return string|boolean
	 */
	private function getAccessToken(){
	    $params = array(
				'grant_type' => 'client_credential',
				'appid'      => $this->appid,
				'secret'     => $this->secret
		);
	    $jsonStr = $this->http(self::AUTH_URL, $params);
	    if ($jsonStr) {
	        $jsonArr = $this->parseJson($jsonStr);
	        if ($jsonArr) {
	            return $this->access_token = $jsonArr['access_token'];
	        }else {
	            return false;
	        }
	    }else {
	        return false;
	    }
	}
	/**
	 * OAuth 授权跳转接口
	 * @param string $callback 回调URI，填写完整地址，带http://
	 * @param sting $state 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值
	 * @param string snsapi_userinfo获取用户授权信息，snsapi_base直接返回openid
	 */
	public function getOAuthRedirect($callback, $state='', $scope='snsapi_base'){
	    return self::OAUTH_AUTHORIZE_URL.'?appid='.$this->appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
	}
	/**
	 * 通过code获取Access Token
	 * @return array|boolean
	 */
	public function getOauthAccessToken(){
	    
	    $code = \Yii::$app->request->get('code');
	    if (!$code) return false;
	    $params = array(
	        'appid' => $this->appid,
	        'secret'=> $this->secret,
	        'code'  => $code,
	        'grant_type' => 'authorization_code'
	    );
	    $jsonStr = $this->http(self::OAUTH_USER_TOKEN_URL, $params);
	    $jsonArr = $this->parseJson($jsonStr);
	    if ($jsonArr) {
	        return $jsonArr;
	    }else {
	        return false;
	    }
	}
	
	/**
	 * 网页获取用户信息
	 * @param  string $access_token  通过getOauthAccessToken方法获取到的token
	 * @param  string $openid        用户的OPENID
	 * @return array
	 */
	public function getOauthUserInfo($access_token, $openid) {
	    $params = array(
	        'access_token'  => $access_token,
	        'openid'        => $openid,
	        'lang'          => 'zh_CN'
	    );
	    $jsonStr = $this->http(self::OAUTH_GET_USERINFO, $params);
	    $jsonArr = $this->parseJson($jsonStr);
	    if ($jsonArr) {
	        return $jsonArr;
	    }else {
	        return false;
	    }
	}
	
	public function unifiedOrder($openid, $body, $orderId, $money, $notify_url = ''){
	    if(strlen($body)>127)$body=substr($body, 0,127);
	    $params = array(
	        'openid'        => $openid,
	        'appid'           => $this->appid,
	        'mch_id'        => $this->mch_id,
	        'nonce_str'   => self::_getRandomStr(), //随机字符串 
	        'body'            => $body, //商品或支付单简要描述 
	        'out_trade_no' => $orderId, //商户系统内部的订单号,32个字符内、可包含字母
	        'total_fee'      => $money * 100, // 转换成分
	        'spbill_create_ip' => $_SERVER['REMOTE_ADDR'], //APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。 
	        'notify_url'   => $notify_url, //接收微信支付异步通知回调地址 
	        'trade_type' => 'JSAPI',
	    );
	    // 生成签名
	    $params['sign'] = self::_getOrderMd5($params);
	    $data = self::_array2Xml($params);
	    $data = $this->http(self::UNIFIED_ORDER_URL, $data, 'POST');
	    $data = self::_extractXml($data);
	    if ($data) {
	        if ($data['return_code'] == 'SUCCESS') {
	            if ($data['result_code'] == 'SUCCESS') {
	                return $this->createPayParams($data['prepay_id']);
	            } else {
	                $this->error = $data['err_code'];
	                return false;
	            }
	        } else {
	            $this->error = $data['return_msg'];
	            return false;
	        }
	    } else {
	        $this->error = '创建订单失败';
	        return false;
	    }
	}
	
	/**
	 * 
	 * @param string $orderId
	 * @param number $type
	 * @return Ambigous <boolean, multitype:>
	 */
	public function getOrderInfo($orderId, $type = 0) {
	    $params['appid']          = $this->appid;
	    $params['mch_id']         = $this->mch_id;
	    if ($type == 1) {
	        $params['transaction_id'] = $orderId;
	    } else {
	        $params['out_trade_no']   = $orderId;
	    }
	    $params['nonce_str']      = self::_getRandomStr();
	    $params['sign']           = self::_getOrderMd5($params);
	    $data = self::_array2Xml($params);
	    $data = $this->http(self::ORDER_QUERY_URL, $data, 'POST');
	    return self::parsePayRequest($data);
	}
	/**
	 * 支付结果通用通知
	 * return array
	 */
	public function getNotify() {
	    $data = $GLOBALS["HTTP_RAW_POST_DATA"];
	    return self::parsePayRequest($data);
	}
	
	/**
	 * 解析支付接口的返回结果
	 */
	public function parsePayRequest($data) {
	    $data = self::_extractXml($data); 
	    if (empty($data)) {
	        $this->error = '支付返回内容解析失败';
	        return false;
	    }
	    if (!self::_checkSign($data)) return false;
	    // return_code为SUCCESS的时候有返回
	    if ($data['return_code'] == 'SUCCESS') {
	        if ($data['result_code'] == 'SUCCESS') {
	            return $data;
	        } else {
	            $this->error = $data['err_code'];
	            return false;
	        }
	    } else {
	        $this->error = $data['return_msg'];
	        return false;
	    }
	}
	
	/**
	 * 对支付回调接口返回成功通知
	 */
	public function returnNotify($return_msg = true) {
	    if ($return_msg == true) {
	        $data = array(
	            'return_code' => 'SUCCESS',
	        );
	    } else {
	        $data = array(
	            'return_code' => 'FAIL',
	            'return_msg'  => $return_msg
	        );
	    }
	    exit(self::_array2Xml($data));
	}
	/**
	 * 本地MD5签名
	 */
	private function _getOrderMd5($params) {
	    ksort($params);
	    $params['key'] = $this->payKey;
	    return strtoupper(md5(urldecode(http_build_query($params))));
	}
	
	private function _array2Xml($array) {
	    $xml  = new \SimpleXMLElement('<xml></xml>');
	    $this->_data2xml($xml, $array);
	    return $xml->asXML();
	}
	
	/**
	 * 数据XML编码
	 * @param  object $xml  XML对象
	 * @param  mixed  $data 数据
	 * @param  string $item 数字索引时的节点名称
	 * @return string xml
	 */
	private function _data2xml($xml, $data, $item = 'item') {
	    foreach ($data as $key => $value) {
	        /* 指定默认的数字key */
	        is_numeric($key) && $key = $item;
	        /* 添加子元素 */
	        if(is_array($value) || is_object($value)){
	            $child = $xml->addChild($key);
	            $this->_data2xml($child, $value, $item);
	        } else {
	            if(is_numeric($value)){
	                $child = $xml->addChild($key, $value);
	            } else {
	                $child = $xml->addChild($key);
	                $node  = dom_import_simplexml($child);
	                $node->appendChild($node->ownerDocument->createCDATASection($value));
	            }
	        }
	    }
	}
	/**
	 * XML文档解析成数组，并将键值转成小写
	 * @param  xml $xml
	 * @return array
	 */
	private function _extractXml($xml) {
	    $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	    return array_change_key_case($data, CASE_LOWER);
	}
	
	/**
	 * 返回随机填充的字符串
	 */
	private function _getRandomStr($lenght = 16)	{
	    $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	    return substr(str_shuffle($str_pol), 0, $lenght);
	}
	/**
	 * 解析json
	 * @param string(json数据) $json
	 * @return boolean|array
	 */
	private function parseJson($json) {
	    $jsonArr = json_decode($json, true);
	    if (isset($jsonArr['errcode'])) {
	        if ($jsonArr['errcode'] == 0) {
	            $this->result = $jsonArr;
	            return true;
	        } else {
	            $this->error = $jsonArr['errcode'];
	            return false;
	        }
	    }else {
	        return $jsonArr;
	    }
	}
	
	/**
	 * 生成支付参数
	 */
	private function createPayParams($prepay_id) {
	    if (empty($prepay_id)) {
	        $this->error = 'prepay_id参数错误';
	        return false;
	    }
	    $params['appId']     = $this->appid;
	    $params['timeStamp'] = (string)$_SERVER['REQUEST_TIME'];
	    $params['nonceStr']  = self::_getRandomStr();
	    $params['package']   = 'prepay_id='.$prepay_id;
	    $params['signType']  = 'MD5';
	    $params['paySign']   = self::_getOrderMd5($params);
	    return json_encode($params);
	}
	/**
	 * 发送HTTP请求方法,需要服务器支持CURL
	 * @param  string  $url    请求URL
	 * @param  array   $params 请求参数
	 * @param  string  $method 请求方法GET/POST
	 * @return array   $data   响应数据
	 */
	private function http($url, $params = array(), $method = 'GET'){
	    $opts = array(
	        CURLOPT_TIMEOUT        => 30,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_SSL_VERIFYPEER => false,
	        CURLOPT_SSL_VERIFYHOST => false
	    );
	    /* 根据请求类型设置特定参数 */
	    switch(strtoupper($method)){
	        case 'GET':
	            $opts[CURLOPT_URL] = $url .'?'. http_build_query($params);
	            break;
	        case 'POST':
	            $opts[CURLOPT_URL] = $url;
	            $opts[CURLOPT_POST] = 1;
	            $opts[CURLOPT_POSTFIELDS] = $params;
	            break;
	    }

	    /* 初始化并执行curl请求 */
	    $ch = curl_init();
	    curl_setopt_array($ch, $opts);
	    $data  = curl_exec($ch);
	    $err = curl_errno($ch);
	    $errmsg = curl_error($ch);
	    curl_close($ch);
	    if ($err > 0) {
	        $this->error = $errmsg;
	        return false;
	    }else {
	        return $data;
	    }
	}
}