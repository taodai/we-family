<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $uid
 * @property string $uname
 * @property string $password
 * @property string $real_name
 * @property integer $gender
 * @property string $auth_key
 * @property integer $utype
 * @property integer $is_author
 * @property string $group
 * @property string $desc
 * @property integer $regtime
 * @property integer $last_login
 * @property integer $status
 * @property string $openid
 * @property integer $studyid
 * @property integer $in_crowd
 */
class test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uname', 'password', 'auth_key', 'regtime'], 'required'],
            [['gender', 'utype', 'is_author', 'regtime', 'last_login', 'status', 'studyid', 'in_crowd'], 'integer'],
            [['desc', 'openid'], 'string'],
            [['uname'], 'string', 'max' => 11],
            [['password'], 'string', 'max' => 100],
            [['real_name'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 32],
            [['group'], 'string', 'max' => 50],
            [['uname'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => Yii::t('app', 'Uid'),
            'uname' => Yii::t('app', '用户名--手机号码'),
            'password' => Yii::t('app', '登录密码(6-12）'),
            'real_name' => Yii::t('app', '真实姓名'),
            'gender' => Yii::t('app', '性别1男2女'),
            'auth_key' => Yii::t('app', '认证密钥-自动登录设置'),
            'utype' => Yii::t('app', '用户类型(1,个人会员2,园所会员3,加盟会员)'),
            'is_author' => Yii::t('app', '微信是否授权绑定(1,已绑定2,未绑定)'),
            'group' => Yii::t('app', '用户分组'),
            'desc' => Yii::t('app', '用户备注'),
            'regtime' => Yii::t('app', '注册时间(时间戳）'),
            'last_login' => Yii::t('app', '最后登录时间'),
            'status' => Yii::t('app', '用户状态(1,正常；2,注销)'),
            'openid' => Yii::t('app', '微信openid'),
            'studyid' => Yii::t('app', '用户学号'),
            'in_crowd' => Yii::t('app', '是否加入微信群（1，未加入；2，已加入）'),
        ];
    }
}
