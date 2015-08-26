<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property integer $pro_id
 * @property string $pro_title
 * @property string $pro_sn
 * @property integer $pro_cat
 * @property integer $pro_brand
 * @property integer $pro_store
 * @property string $pro_image_default
 * @property string $pro_image_file
 * @property string $pro_price
 * @property string $pro_tag
 * @property string $pro_desc
 * @property integer $pro_freight
 * @property integer $pro_status
 * @property integer $pro_coupon
 * @property integer $pro_time
 * @property integer $pro_creater
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pro_cat', 'pro_brand', 'pro_store', 'pro_freight', 'pro_status', 'pro_coupon', 'pro_time', 'pro_creater','pro_sales'], 'integer'],
            [['pro_image_file', 'pro_desc'], 'string'],
            [['pro_price'], 'number'],
            [['pro_title','pro_prefix'], 'string', 'max' => 100],
            [['pro_sn','real_name'], 'string', 'max' => 50],
            [['pro_image_default'], 'string', 'max' => 200],
            [['pro_tag'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pro_id' => 'Pro ID',
            'pro_title' => 'Pro Title',
            'pro_sn' => 'Pro Sn',
            'pro_cat' => 'Pro Cat',
            'pro_brand' => 'Pro Brand',
            'pro_store' => 'Pro Store',
            'pro_image_default' => 'Pro Image Default',
            'pro_image_file' => 'Pro Image File',
            'pro_price' => 'Pro Price',
            'pro_tag' => 'Pro Tag',
            'pro_desc' => 'Pro Desc',
            'pro_freight' => 'Pro Freight',
            'pro_status' => 'Pro Status',
            'pro_coupon' => 'Pro Coupon',
            'pro_time' => 'Pro Time',
            'pro_creater' => 'Pro Creater',
        ];
    }

    public function getCate(){
        return $this->hasOne(ProCategory::className(),['pc_id'=>'pro_cat']);
    }
    public function getBrand(){
        return $this->hasOne(ProBrand::className(),['pb_id'=>'pro_brand']);
    }
    public function getAttr(){
        return $this->hasMany(ProProperty::className(),['pp_pro_id'=>'pro_id']);
    }
}
