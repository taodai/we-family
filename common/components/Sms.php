<?php
namespace common\components;
use Yii;
use linslin\yii2\curl;
/**
 * 短信发送
 * 需开启 CURL 支持
 */

class Sms{

    const SEND_URL = 'http://ums.zj165.com:8888/sms/Api/Send.do';   //短信发送地址;
    const PATTERN = '/^(1(([3578][0-9])|(47)|[8][0126789]))\d{8}$/';

    /* 企业编号 */
    private $comNum;
    /* 用户ID */
    private $userId;
    /* 密码 */
    private $secret;
    /* 模板 */
    private $smsTpl;
    /* 手机号码（string） */
    private $mobile; /* mixed*/
    /* 手机号码是否通过验证 */
    private $isCheck;

    public function __construct()
    {
        $this->comNum = Yii::$app->params['comNum'];
        $this->userId = Yii::$app->params['userId'];
        $this->secret = Yii::$app->params['secret'];
    }

    public function __set($key, $value) 
    {
        $this->$key = ($key == 'smsTpl') ? Yii::$app->params['smsTplBegin'].$value.Yii::$app->params['smsTplEnd'] : $value;
    }

    public function __get($key) 
    {
        return $this->$key;
    }
    
    public function regSms()
    {
        $this->_checkAndFormatMobile();
        if($this->isCheck){
            $curl = new curl\Curl();
            $postArr = [
                    'SpCode'=>$this->comNum,
                    'LoginName'=>$this->userId,
                    'Password'=>$this->secret,
                    'MessageContent'=>iconv( "UTF-8",'GBK',$this->smsTpl),
                    'UserNumber'=>$this->mobile,
                    'SerialNumber'=>'',
                    'ScheduleTime'=>'',
                    'ExtendAccessNum'=>'',
                    'f'=>1
                ];
            $response = $curl->setOption(
                    CURLOPT_POSTFIELDS, 
                    http_build_query($postArr)
                )
                ->post(self::SEND_URL);
            return $response;
        }else{
            return false;
        }
    }

    public function test($key)
    {
        echo $this->$key;
    }

    /**
    * @param $mobile 支持单个手机号码或者数组
    *   手机号码验证以及格式化;
    *   void;
    */
    private function _checkAndFormatMobile()
    {
        if($this->mobile){
            if(is_array($this->mobile) && count($this->mobile)<=1000){
                foreach($this->mobile as $m){
                    $this->isCheck = preg_match(self::PATTERN,$m) ? true : false;
                    if(!$this->isCheck){
                        break;
                    }
                }
                $this->mobile = implode(',', $this->mobile);
            }else{
                $this->isCheck = preg_match(self::PATTERN,$this->mobile) ? true : false;
            }
        }
    }
}
?>