<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;

/**
 * This is the model class for table "{{%user_payment}}".
 *
 * @property integer $pay_id
 * @property integer $uid
 * @property double $money
 * @property string $desc
 * @property integer $pay_time
 */
class UserPayment extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'money', 'pay_time'], 'required'],
            [['uid', 'pay_time'], 'integer'],
            [['money'], 'number'],
            [['desc'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pay_id' => Yii::t('app', 'Pay ID'),
            'uid' => Yii::t('app', '用户ID'),
            'money' => Yii::t('app', '支付金额'),
            'desc' => Yii::t('app', '用途'),
            'pay_time' => Yii::t('app', '支付时间'),
        ];
    }

    public function addPayment($params)
    {
        if($params){
            $this->loadValue($params);
            $this->pay_time = time();
            return $this->save();
        }
    }

    public function getAll($uid)
    {
        return $this::find()->where(['uid'=>$uid])->asArray()->all();
    }
}
