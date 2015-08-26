<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%app_address}}".
 *
 * @property integer $id
 * @property integer $userid
 * @property string $username
 * @property string $receiver
 * @property string $mobile
 * @property string $province
 * @property string $city
 * @property string $county
 * @property string $address
 * @property integer $status
 */
class AppAddress extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid', 'status'], 'integer'],
            [['username'], 'string', 'max' => 100],
            [['receiver'], 'string', 'max' => 30],
            [['mobile', 'province', 'city', 'county'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userid' => Yii::t('app', '用户id'),
            'username' => Yii::t('app', '用户名'),
            'receiver' => Yii::t('app', '收货人'),
            'mobile' => Yii::t('app', '手机号'),
            'province' => Yii::t('app', '省份'),
            'city' => Yii::t('app', '城市'),
            'county' => Yii::t('app', '县/地区'),
            'address' => Yii::t('app', '详细地址'),
            'status' => Yii::t('app', '状态（0：正在使用，1：已删除）'),
        ];
    }

    public function getAddress($uid)
    {
        return $this->find()->where(['userid'=>$uid])->asArray()->all();
    }

    public function getProvince(){
        return $this->hasOne(Province::className(),['province_code'=>'province']);
    }
    public function getCity(){
        return $this->hasOne(City::className(),['city_code'=>'city']);
    }
    public function getArea(){
        return $this->hasOne(Area::className(),['area_code'=>'county']);
    }
    // public function addressAdd($params)
    // {
    //     $this->loadValue($params);
        
    // }
}
