<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_product}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $pro_id
 */
class OrderProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pro_id'], 'required'],
            [['order_id', 'pro_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', '订单ID'),
            'pro_id' => Yii::t('app', '商品ID'),
        ];
    }

    public function addUnion($productStr,$orderId)
    {
        if(preg_match("/^[0-9|]+$/", $productStr)){
            $productArr = explode('|', $productStr);
            if(is_array($productArr) && $orderId){
                foreach($productArr as $productId)
                {
                    if($productId){
                        $_model = clone $this;
                        $_model->pro_id = $productId;
                        $_model->order_id = $orderId;
                        $_model->save();
                    }
                }
                return true;
            }
        }
        return false;
    }
}
