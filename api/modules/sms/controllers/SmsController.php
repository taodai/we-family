<?php

namespace api\modules\sms\controllers;

use Yii;
use api\controllers\AbstractController;
use common\models\User;
use common\models\SmsInfo;
use common\components\Sms;

class SmsController extends AbstractController
{
    public $modelClass = 'common\models\User';

    public $pattern = '/^(1(([3578][0-9])|(47)|[8][0126789]))\d{8}$/';

    //短信发送
    public function actionSmsSend()
    {
        $params = Yii::$app->request->post();

        $isMobile = $this->_checkIsMobile($params['uname']);

        if($isMobile){
            $bool = $this->_checkUname($params['uname']);
            if($bool){
                $this->_formatResponse(2,'该手机号码已注册');
            }else{
                $sms = new Sms();
                $code = mt_rand(111111,999999);

                $sms->smsTpl = $code;
                $sms->mobile = $params['uname'];
                $smsResult = $sms->regSms();
                if($smsResult){
                    $smsResultArr = $this->_formatReturn($smsResult);
                    if($smsResultArr[0]['result'] == '0'){
                        if($this->_saveSmsInfo($sms->mobile,$code,$sms->smsTpl)){
                            $this->_formatResponse(1,'验证码发送成功');
                        }else{
                            $this->_formatResponse(5,'验证码发送失败');
                        }
                    }else{
                        $this->_formatResponse(4,iconv( 'GBK',"UTF-8",$smsResultArr[1]['description']));
                    }
                }else{
                    $this->_formatResponse(5,'验证码发送失败');
                }
            }
        }else{
            $this->_formatResponse(3,'非法手机号码');
        }
    }

    //短信发送无限制
    public function actionSmsSendNo()
    {
        $params = Yii::$app->request->post();
        
        $isMobile = $this->_checkIsMobile($params['uname']);

        if($isMobile){
            $sms = new Sms();
            $code = mt_rand(111111,999999);

            $sms->smsTpl = $code;
            $sms->mobile = $params['uname'];
            $smsResult = $sms->regSms();

            if($smsResult){
                $smsResultArr = $this->_formatReturn($smsResult);
                if($smsResultArr[0]['result'] == '0'){
                    if($this->_saveSmsInfo($sms->mobile,$code,$sms->smsTpl)){
                        $this->_formatResponse(1,'验证码发送成功');
                    }else{
                        $this->_formatResponse(5,'验证码发送失败');
                    }
                }else{
                    $this->_formatResponse(4,iconv( 'GBK',"UTF-8",$smsResultArr[1]['description']));
                }
            }else{
                $this->_formatResponse(5,'验证码发送失败');
            }
        }else{
            $this->_formatResponse(3,'非法手机号码');
        }
    }

    private function _formatReturn($return)
    {
        if($return){
            $returnArr = explode('&', $return);
            foreach ($returnArr as $key => $value) {
                $explodeArr = explode('=', $value);
                if(count($explodeArr) == 2){
                    $returnArr[$key] = array($explodeArr[0]=>$explodeArr[1]);
                }
            }
            return $returnArr;
        }
        return false;
    }

    private function _checkUname($uname)
    {
        $model = new User();
        return $model->checkUname($uname);
    }

    //判断是否为合法的手机号码
    private function _checkIsMobile($mobile)
    {
        return preg_match($this->pattern,$mobile) ? true : false;
    }

    //保存发送记录
    private function _saveSmsInfo($mobile,$code,$content)
    {
        $model = new SmsInfo();
        return $model->smsSave($mobile,$code,$content);
    }
}
