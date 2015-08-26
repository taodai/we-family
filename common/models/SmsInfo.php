<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sms}}".
 *
 * @property integer $smsid
 * @property integer $sms_type
 * @property string $sms_mobile
 * @property integer $sms_code
 * @property integer $sms_status
 * @property string $sms_content
 * @property integer $sms_send
 * @property integer $sms_expiration
 */
class SmsInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sms_type', 'sms_mobile', 'sms_content', 'sms_send'], 'required'],
            [['sms_type', 'sms_send', 'sms_expiration','sms_code','sms_status'], 'integer'],
            [['sms_mobile'], 'string', 'max' => 11],
            [['sms_content'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'smsid' => Yii::t('app', 'Smsid'),
            'sms_type' => Yii::t('app', '短信类型'),
            'sms_mobile' => Yii::t('app', '用户接受短信手机号码'),
            'sms_code' => Yii::t('app', '注册码'),
            'sms_content' => Yii::t('app', '短信内容'),
            'sms_status' => Yii::t('app', '状态1,已使用，2未使用'),
            'sms_send' => Yii::t('app', '短信发送时间'),
            'sms_expiration' => Yii::t('app', '短信过期时间'),
        ];
    }

    public function setId($id)
    {
        $this->smsid = $id;
    }

    public function setMobile($mobile)
    {
        $this->sms_mobile = $mobile;
    }

    /**
    * 判断时间是否过期
    */
    public function checkIsExpiration()
    {
        $model = $this->find('sms_expiration')->where(['smsid'=>$this->smsid])->one();
        if($model->sms_expiration > time()){
            return true;
        }
        return false;
    }

    public function checkIsMobile($mobile)
    {
        $pattern = '/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/';
        preg_match($pattern,$mobile);
    }

    public function smsSave($mobile,$code,$content)
    {
        $this->sms_type = 1;
        $this->sms_mobile = $mobile;
        $this->sms_code = $code;
        $this->sms_content = $content;
        $this->sms_send = time();
        $this->sms_expiration = time()+60*10;
        if($this->save()){
            return true;
        }
        return false;
    }

    //判断验证码是否有效
    public function codeIsValid($mobile,$code)
    {

        $info = $this->find()->where(['sms_mobile' => $mobile,'sms_code' => $code,'sms_status'=>2])->one();
        if($info){
            if($info->sms_expiration > time()){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 3;
        }
    }
}
