<?php

namespace common\models;

use Yii;
use common\models\AppUserinfo;
use common\models\Agency;
use common\models\AgencyCout;
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
class User extends \yii\db\ActiveRecord
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
            [['group'], 'string', 'max' => 50]
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


    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function signup($params)
    {
        $user = User::find()->where(['uname'=>$params['uname']])->one();
        if(empty($user)){
            $user=new User();
        }
        //$user = $user ? $user : new User();
        //$user = new User();
        // $user = new User();
        $user->uname = $params['uname'];
        $password = isset($params['password']) ? $params['password'] : $user->uname;
        $openid = isset($params['openid']) ? $params['openid'] : "";
        $studyid = isset($params['studyid']) ? $params['studyid'] : "";
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->is_author = isset($params['is_author']) ? $params['is_author'] : "2";
        $user->utype = 1;
        $user->openid = $openid;
        $user->studyid = $studyid;
        $user->regtime = time();
        $user->last_login = $user->regtime;
        $user->status = 1;
        $user->gender = isset($params['gender']) ? $params['gender'] : 1;
        if ($user->save()) {
            return $user;
        }
        return null;
    }

    public function register($params)
    {
        $isIn = $this->checkUname($params['uname']);
        if($isIn){
            return false;
        }else{
            $pattern = '/^(1(([3578][0-9])|(47)|[8][0126789]))\d{8}$/';
            $isMobile = preg_match($pattern,trim($params['uname']));
            if($isMobile){
                $user = new User();
                $user->uname = trim($params['uname']);
                $password = isset($params['password']) ? $params['password'] : $user->uname;
                $user->setPassword($password);
                $user->generateAuthKey();
                $user->is_author = isset($params['is_author']) ? $params['is_author'] : "2";
                $user->utype = 1;
                $user->openid = isset($params['openid']) ? $params['openid'] : "";
                $user->studyid = isset($params['studyid']) ? $params['studyid'] : "";
                $user->regtime = time();
                $user->last_login = $user->regtime;
                $user->status = 1;
                $user->gender = isset($params['gender']) ? $params['gender'] : 1;

                if ($user->save()) {
                    return $user;
                }
                return false;
            }
        }
    }


    // public function addUserInfo($object)
    // {
        
    // }

    public function checkUname($uname)
    {
        $uname = trim($uname);
        if(!empty($uname)){
            $model = $this->find()->where(['uname'=>$uname])->one();
            // $model = $this->find()->where(['uname'=>$uname,'status'=>1])->one();
            if($model){
                return $model;
            }
        }
        return false;
    }


    //根据用户ID添加代理总数
    public function addAgencyCount($uid)
    {
        $agencyCount = AgencyCout::find()->where(['uid'=>$uid])->one();
        if($agencyCount){
            $model = $agencyCount;
        }else{
            $model = new AgencyCout();
        }
    }

    //根据用户ID获取并插入其代理总数
    public function getAgencyByUid($uid)
    {
        $agencyCount = AgencyCout::find()->where(['uid'=>$uid])->one();
        if($agencyCount){
            $model = $agencyCount;
        }else{
            $model = new AgencyCout();
        }
        $agencyCountArr = $this->getAgencyByUidStep($uid);
        print_r($agencyCountArr);
        if($agencyCountArr){
            $model->uid = $uid;
            $model->first_agency = $agencyCountArr['first'];
            $model->second_agency = $agencyCountArr['second'];
            $model->save();
        }
    }

    public function getAgencyByUidStep($uid)
    {
        $agency = Agency::find()->where(['parent_id'=>$uid])->asArray()->all();
        $firstAgencyCount = 0;
        $secondAgencyCount = 0;
        if($agency){
            foreach ($agency as $key => $value) {
                if(is_array($value)){
                    $value['level'] == 2 ? $firstAgencyCount++ : $secondAgencyCount++;
                }
            }
        }
        // else{
        //     $agency = new Agency();
        // }
        if($firstAgencyCount || $secondAgencyCount){
            return [
                'first'=>$firstAgencyCount,
                'second'=>$secondAgencyCount,
                ];
        }else{
            return false;
        }
    }

    //判断是否存在代理统计
    public function _getAgencyCountIsByUid($uid)
    {
        $agencyCount = AgencyCout::find()->where(['uid'=>$uid])->one();
        return $agencyCount ? $agencyCount : false;
    }



    public function validateUser($params)
    {
        $this->uname = $params['uname'];
        $user = $this->find('uid,uname,real_name,password')->where(['uname'=>$this->uname,'status'=>1])->one();
        return $user;
    }

    public function login($params)
    {
        $user = $this->validateUser($params);
        if($user){
            $this->password = $user->password;
            if($this->validatePassword($params['password'])){
                // $user->loginip = Yii::$app->getRequest()->getUserIP();
                $user->last_login = time();
                $user->save();
                return $user;
            }else{
                return 2;
            }
        }else{
            return 3;
        }
    }

    public function getInfo()
    {
        return $this->hasOne(AppUserinfo::className(),['uid'=>'uid']);
    }
    public function getAgency()
    {
        return $this->hasOne(AgencyCout::className(),['uid'=>'uid']);
    }

    //返回指定的字段值
    public function fields()
    {
        $fields = parent::fields();

          // 删除一些包含敏感信息的字段
          unset($fields['auth_key'],$fields['password']);

          return $fields;
    }
}
