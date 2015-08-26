<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%user_pay}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $source
 * @property double $income
 * @property string $orderid
 * @property integer $pay_time
 * @property string $content
 */
class UserPay extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'source', 'income', 'pay_time', 'content'], 'required'],
            [['uid', 'source', 'pay_time'], 'integer'],
            [['income'], 'number'],
            [['orderid'], 'string', 'max' => 20],
            [['content'], 'string', 'max' => 100]
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
            'source' => Yii::t('app', '来源（1微信,2，APP,3）'),
            'income' => Yii::t('app', 'Income'),
            'orderid' => Yii::t('app', '订单号'),
            'pay_time' => Yii::t('app', '支付时间'),
            'content' => Yii::t('app', '支付说明'),
        ];
    }
}
