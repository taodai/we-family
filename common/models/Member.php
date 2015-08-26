<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member}}".
 *
 * @property string $userid
 * @property string $username
 * @property string $passport
 * @property string $company
 * @property string $password
 * @property string $payword
 * @property string $email
 * @property integer $message
 * @property integer $chat
 * @property integer $sound
 * @property integer $online
 * @property integer $avatar
 * @property integer $gender
 * @property string $truename
 * @property string $mobile
 * @property string $msn
 * @property string $qq
 * @property string $ali
 * @property string $skype
 * @property string $department
 * @property string $career
 * @property integer $groupid
 * @property integer $regid
 * @property integer $sms
 * @property integer $credit
 * @property string $money
 * @property string $locking
 * @property string $bank
 * @property string $account
 * @property string $edittime
 * @property string $regip
 * @property string $regtime
 * @property string $loginip
 * @property string $logintime
 * @property string $logintimes
 * @property string $black
 * @property integer $send
 * @property string $auth
 * @property string $authvalue
 * @property string $authtime
 * @property integer $vemail
 * @property integer $vmobile
 * @property integer $vtruename
 * @property integer $vbank
 * @property integer $vcompany
 * @property integer $vtrade
 * @property string $trade
 * @property string $support
 * @property string $inviter
 * @property integer $areaid
 */
class Member extends \yii\db\ActiveRecord
{
    public $repassword;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'chat', 'sound', 'online', 'avatar', 'gender', 'groupid', 'regid', 'sms', 'credit', 'edittime', 'regtime', 'logintime', 'logintimes', 'send', 'authtime', 'vemail', 'vmobile', 'vtruename', 'vbank', 'vcompany', 'vtrade', 'areaid'], 'integer'],
            [['money', 'locking'], 'number'],
            [['areaid'], 'required'],
            [['username', 'passport', 'ali', 'skype', 'department', 'career', 'bank', 'account', 'inviter'], 'string', 'max' => 30],
            [['company', 'authvalue'], 'string', 'max' => 100],
            [['password', 'payword', 'auth'], 'string', 'max' => 32],
            [['email', 'mobile', 'msn', 'regip', 'loginip', 'trade', 'support'], 'string', 'max' => 50],
            [['truename', 'qq'], 'string', 'max' => 20],
            [['black'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['passport'], 'unique'],
            [['repassword'], 'compare', 'compareAttribute'=>'password']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => Yii::t('app', '会员ID'),
            'username' => Yii::t('app', '会员名'),
            'passport' => Yii::t('app', '通行证'),
            'company' => Yii::t('app', '公司名'),
            'password' => Yii::t('app', '登录密码'),
            'payword' => Yii::t('app', '支付密码'),
            'email' => Yii::t('app', 'E-mail'),
            'message' => Yii::t('app', '新信件数量'),
            'chat' => Yii::t('app', '新对话数量'),
            'sound' => Yii::t('app', '站内信声音(0 无、1提示一、2提示二、3提示三)'),
            'online' => Yii::t('app', '在线状态(1在线0隐身)'),
            'avatar' => Yii::t('app', '是否有头像(0 否 1 是)'),
            'gender' => Yii::t('app', '性别(1 先生、2女士)'),
            'truename' => Yii::t('app', '姓名/联系人'),
            'mobile' => Yii::t('app', '手机'),
            'msn' => Yii::t('app', 'MSN'),
            'qq' => Yii::t('app', 'QQ'),
            'ali' => Yii::t('app', '阿里旺旺'),
            'skype' => Yii::t('app', 'Skype'),
            'department' => Yii::t('app', '部门'),
            'career' => Yii::t('app', '职位'),
            'groupid' => Yii::t('app', '注册会员组(关联到member_group)'),
            'regid' => Yii::t('app', '注册会员组(5个人会员 、6 企业会员)'),
            'sms' => Yii::t('app', '短信余额'),
            'credit' => Yii::t('app', '积分余额'),
            'money' => Yii::t('app', '资金余额'),
            'locking' => Yii::t('app', '资金锁定'),
            'bank' => Yii::t('app', '收款银行'),
            'account' => Yii::t('app', '收款帐号'),
            'edittime' => Yii::t('app', '修改时间'),
            'regip' => Yii::t('app', '注册IP'),
            'regtime' => Yii::t('app', '注册时间'),
            'loginip' => Yii::t('app', '登录IP'),
            'logintime' => Yii::t('app', '登录时间'),
            'logintimes' => Yii::t('app', '登录次数'),
            'black' => Yii::t('app', '站内信黑名单'),
            'send' => Yii::t('app', '是否允许转发站内信至邮箱'),
            'auth' => Yii::t('app', '验证密钥'),
            'authvalue' => Yii::t('app', '验证内容'),
            'authtime' => Yii::t('app', '验证时间'),
            'vemail' => Yii::t('app', '邮箱认证(0 否 1 是)'),
            'vmobile' => Yii::t('app', '手机认证(0 否 1 是)'),
            'vtruename' => Yii::t('app', '实名认证(0 否 1 是)'),
            'vbank' => Yii::t('app', '银行认证(0 否 1 是)'),
            'vcompany' => Yii::t('app', '公司认证(0 否 1 是)'),
            'vtrade' => Yii::t('app', '支付宝帐号认证(暂时不用)'),
            'trade' => Yii::t('app', '支付宝帐号(暂时不用)'),
            'support' => Yii::t('app', '客服专员'),
            'inviter' => Yii::t('app', '推荐注册人'),
            'areaid' => Yii::t('app', 'Areaid'),
            'repassword' => Yii::t('app', '确认密码'),
        ];
    }
    
    /*
     * 金额变化记录
     */
    public static function moneyRecord($RecordModel){
        if($RecordModel->userid && $RecordModel->amount){
            $moenyModel = Member::find()->where(['userid'=>$userid])->select('moeny')->one();
            if($moenyModel){
                $financeRecord = new \common\models\FinanceRecord();
                $financeRecord->userid = $RecordModel->userid;
                $financeRecord->username = $RecordModel->username;
                $financeRecord->type = $RecordModel->type;
                $financeRecord->tradeno = $RecordModel->tradeno;
                $financeRecord->amount = $RecordModel->$amount;
                $financeRecord->balance = $moenyModel->money;
                $financeRecord->addtime = time();
                $financeRecord->editor = $RecordModel->editor;
                $financeRecord->save(); 
            }
        }
    }
    
    /*
     * 增加金额
     */
    public static function moneyAdd($userid,$amount){
        if($userid && $amount){
            $memberModel = Member::find()->where(["userid"=>$userid])->one();
            if($moenyModel){
                $memberModel->money += $amount;
                $memberModel->save(); 
            }
        }
    }
}
