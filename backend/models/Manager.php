<?php

namespace backend\models;

use Yii;
use common\models\AbstractModel;

/**
 * This is the model class for table "hql_manager".
 *
 * @property integer $mid
 * @property string $login_name
 * @property string $real_name
 * @property string $password
 * @property string $mobile
 * @property integer $sex
 * @property string $weixin
 * @property integer $status
 * @property integer $create_mid
 * @property integer $createtime
 * @property integer $logintime
 * @property string $loginip
 * @property integer $logintimes
 */
class Manager extends AbstractModel
{
    private $_manager = false;
    private $_msg = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manager}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login_name', 'real_name', 'password', 'mobile', 'status', 'create_mid', 'createtime'], 'required'],
            [['sex', 'status', 'create_mid', 'createtime', 'logintime', 'logintimes'], 'integer'],
            [['login_name', 'real_name'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 60],
            [['mobile'], 'string', 'max' => 11],
            [['weixin'], 'string', 'max' => 50],
            [['loginip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mid' => Yii::t('app', 'Mid'),
            'login_name' => Yii::t('app', '登录名称'),
            'real_name' => Yii::t('app', '真实姓名'),
            'password' => Yii::t('app', '密码'),
            'mobile' => Yii::t('app', '手机号码'),
            // 'email' => Yii::t('app', '邮箱地址'),
            'sex' => Yii::t('app', '性别(1,男2,女)'),
            'weixin' => Yii::t('app', '微信号'),
            'status' => Yii::t('app', '状态1，正常；2,停用'),
            'create_mid' => Yii::t('app', '创建人员ID'),
            'createtime' => Yii::t('app', '添加时间'),
            'logintime' => Yii::t('app', '登录时间'),
            'loginip' => Yii::t('app', '最后登录IP'),
            'logintimes' => Yii::t('app', '登录次数'),
        ];
    }

    public function login($params)
    {
        $manager = $this->validateUser($params);
        $this->password = $manager->password;
        if($this->validatePassword($params['password'])){
            $manager->logintime = time();
            $manager->loginip = Yii::$app->getRequest()->getUserIP();;
            $manager->logintimes = $manager->logintimes+1;
            $manager->save();
            return $manager;
        }else{
            $this->_msg = '用户名或密码错误';
        }
        return false;
    }


    public function validateUser($params)
    {
        $this->login_name = $params['login_name'];
        $manager = $this->find('mid,login_name,real_name,password')->where(['login_name'=>$this->login_name,'status'=>1])->one();
        return $manager;
    }

    public function setPassword($password)
    {
        return $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getUser()
    {
        if ($this->_manager === false) {
            $this->_manager =$this->find()->where(['login_name'=>$this->login_name])->one();
        }

        return $this->_manager;
    }

    public function getAll()
    {
        $menu = $this->find()->orderBy('createtime')->asArray()->all();
        return $menu;
    }
}
