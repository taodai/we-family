<?php

namespace common\models;

use Yii;
use common\models\Agency;
/**
 * This is the model class for table "{{%app_userinfo}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $uname
 * @property integer $userType
 * @property string $property
 * @property integer $points
 * @property string $picUrl
 * @property string $picName
 * @property integer $babySex
 * @property string $babyName
 * @property integer $babyBirthday
 * @property integer $babyDueDate
 * @property string $babyweight
 * @property string $babyheight
 */
class AppUserinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_userinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'userType', 'points', 'babySex', 'babyBirthday', 'babyDueDate'], 'integer'],
            [['property', 'babyweight', 'babyheight'], 'number'],
            [['uname', 'picName', 'babyName'], 'string', 'max' => 30],
            [['picUrl'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', '用户id'),
            'uname' => Yii::t('app', '用户名'),
            'userType' => Yii::t('app', '用户类型（1：备孕期，2：怀孕期，3：家有小宝）'),
            'property' => Yii::t('app', '资产'),
            'points' => Yii::t('app', '积分'),
            'picUrl' => Yii::t('app', '头像图片路径'),
            'picName' => Yii::t('app', '图片名称'),
            'babySex' => Yii::t('app', '性别（1：男，2：女）'),
            'babyName' => Yii::t('app', '宝宝昵称'),
            'babyBirthday' => Yii::t('app', '宝宝生日'),
            'babyDueDate' => Yii::t('app', '宝宝预产期'),
            'babyweight' => Yii::t('app', '宝宝体重'),
            'babyheight' => Yii::t('app', '宝宝身高'),
        ];
    }

    public function getByUid($uid)
    {
        $model = AppUserinfo::find()->where(['uid'=>$uid])->one();
        return $model ? $model : false;
    }
    public function getthreelevelname($uid)
    {
        $agency=Agency::find()->where(['child_id'=>$uid,'level'=>2])->one();
        $parent_id=$agency->parent_id;
        $model = AppUserinfo::find()->where(['uid'=>$parent_id])->one();
        return $model ? $model : false;
    }
}
