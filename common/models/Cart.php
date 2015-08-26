<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
use common\models\Products;
/**
 * This is the model class for table "{{%cart}}".
 *
 * @property integer $cart_id
 * @property integer $uid
 * @property integer $pro_id
 * @property integer $nums
 * @property integer $status
 * @property integer $add_time
 */
class Cart extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'pro_id', 'nums', 'add_time'], 'required'],
            [['cart_id', 'uid', 'pro_id', 'nums', 'status', 'add_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cart_id' => Yii::t('app', 'Cart ID'),
            'uid' => Yii::t('app', 'Uid'),
            'pro_id' => Yii::t('app', 'Pro ID'),
            'nums' => Yii::t('app', 'Nums'),
            'status' => Yii::t('app', 'Status'),
            'add_time' => Yii::t('app', '添加时间'),
        ];
    }

    public function addProduct($params)
    {
        if(is_array($params)){
            $model = $this->getByProduct($params);
            if($model){
                $model->nums = $params['nums'] + $model->nums;
                if($model->save()){
                    return true;
                }
            }else{
                $this->loadValue($params);
                $this->add_time = time();
                if($this->save()){
                    return true;
                }
            }
        }
        return false;
    }

    public function getAll($uid)
    {
        return $this::find()->joinWith('product')->where(['uid'=>$uid])->asArray()->all();
    }

    public function getByProduct($params)
    {
        return $this::find()->where(['uid'=>$params['uid'],'pro_id'=>$params['pro_id']])->one();
    }

    public function getProduct(){
        return $this->hasOne(Products::className(),['pro_id'=>'pro_id']);
    }
}
