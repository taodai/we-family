<?php 
namespace common\library;
use Yii;

define('APPID','wx771185e82a1c60af');
define('SECRET','cf3cab2d3f485f811c3d6079c5a7d4a6');
define('MCH_ID','1239208502');
define('PAYKEY','c4a5e8b9b36bae0da7b46aa6e6042a71');
define('UNIFIED_ORDER_URL','https://api.mch.weixin.qq.com/pay/unifiedorder');
class WeixinApi{
	/**
	 * 获取用户openid
	 * 
	 */
	public function getopenid($code){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.SECRET.'&code='.$code.'&grant_type=authorization_code';
        $result = $this->http_get($url);
        $result=json_decode($result);
        $openid=$result->openid;
        return $openid;
    }
    /**
     *获取授权access_token
     * 
     */
    public function getaccess($code){
        // $data = json_decode(file_get_contents("access_token1.json"));
        // if ($data->expire_time < time()) {
        //   // 如果是企业号用以下URL获取access_token
        //   // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
        //   $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.SECRET.'&code='.$code.'&grant_type=authorization_code';
        //   $result = $this->http_get($url);
        //   $result=json_decode($result);
        //   $access_token = $result->access_token;
        //   if ($access_token) {
        //     $data->expire_time = time() + 7000;
        //     $data->access_token = $access_token;
        //     $fp = fopen("access_token.json1", "w");
        //     fwrite($fp, json_encode($data));
        //     fclose($fp);
        //   }
        // } else {
        //   $access_token = $data->access_token;
        // }


        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.SECRET.'&code='.$code.'&grant_type=authorization_code';
        //$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.APPID.'&secret='.SECRET;
        $result = $this->http_get($url);
        $result=json_decode($result);
        
        //$access_token=$result->access_token;
        return $result;
    }
    /**
     * 获取用户全部信息
     * 
     */
    public function getallinfo($openid,$access_token){
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid;
        //$url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid;
        $result = $this->http_get($url);
        $result=json_decode($result);
        return $result;
    }
    private function http_get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
    public function http_post($url,$param){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (is_string($param)) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
    /**
     * 构造订单信息
     * 
     */
    public function unifiedOrder($openid,$orderId,$body, $money, $notify_url = ''){
        
        $params = array(
            'openid'        => $openid,
            'appid'           => APPID,
            'mch_id'        => MCH_ID,
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
        $data = $this->http_post(UNIFIED_ORDER_URL, $data);
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
     * 本地MD5签名
     */
    private function _getOrderMd5($params) {
        ksort($params);
        $params['key'] = PAYKEY;
        return strtoupper(md5(urldecode(http_build_query($params))));
    }
    private function _array2Xml($array) {
        $xml  = new \SimpleXMLElement('<xml></xml>');
        $this->_data2xml($xml, $array);
        return $xml->asXML();
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
     * 生成支付参数
     */
    private function createPayParams($prepay_id) {
        if (empty($prepay_id)) {
            $this->error = 'prepay_id参数错误';
            return false;
        }
        $params['appId']     = APPID;
        $params['timeStamp'] = (string)$_SERVER['REQUEST_TIME'];
        $params['nonceStr']  = self::_getRandomStr();
        $params['package']   = 'prepay_id='.$prepay_id;
        $params['signType']  = 'MD5';
        $params['paySign']   = self::_getOrderMd5($params);
        return json_encode($params);
    }
    /**
     * 返回随机填充的字符串
     */
    private function _getRandomStr($lenght = 16)    {
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        return substr(str_shuffle($str_pol), 0, $lenght);
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
}
 ?>
