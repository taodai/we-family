<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;

/**
 * This is the model class for table "{{%user_account}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property double $income_total
 * @property double $pay_total
 * @property double $income_first
 * @property double $income_second
 * @property double $cash_left
 * @property double $cash_has
 */
class UserAccount extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'income_total'], 'required'],
            [['uid'], 'integer'],
            [['income_total', 'pay_total', 'income_first', 'income_second', 'cash_left','cash_has'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', '关联用户ID'),
            'income_total' => Yii::t('app', '收益总额'),
            'pay_total' => Yii::t('app', '支付总额'),
            'income_first' => Yii::t('app', '一级收益总额'),
            'income_second' => Yii::t('app', '二级收益总额'),
            'cash_left' => Yii::t('app', '可提现总额'),
            'cash_has' => Yii::t('app', '已提现金额'),
        ];
    }

    public function getIncome($uid)
    {
        return $this::find()->where(['uid'=>$uid])->asArray()->one();
    }
}
