<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
use common\models\OrderProduct;
use common\models\Products;
/**
 * This is the model class for table "{{%orders}}".
 *
 * @property integer $order_id
 * @property string $order_code
 * @property integer $uid
 * @property double $money_total
 * @property double $carriage
 * @property integer $coupon_id
 * @property string $receiver
 * @property string $mobile
 * @property string $province_name
 * @property string $city_name
 * @property string $county_name
 * @property string $address
 * @property integer $order_time
 * @property integer $order_status
 * @property integer $pay_time
 * @property integer $pay_type
 */
class Orders extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_code', 'uid', 'money_total', 'receiver', 'order_time'], 'required'],
            [['uid', 'coupon_id', 'order_time', 'order_status', 'pay_time', 'pay_type'], 'integer'],
            [['money_total', 'carriage'], 'number'],
            [['mobile', 'province_name', 'city_name', 'county_name'], 'string', 'max' => 20],
            [['receiver','order_code'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Order ID'),
            'order_code' => Yii::t('app', '订单编号'),
            'uid' => Yii::t('app', '用户ID'),
            'money_total' => Yii::t('app', '订单金额'),
            'carriage' => Yii::t('app', '运费'),
            'coupon_id' => Yii::t('app', '优惠券ID'),
            'receiver' => Yii::t('app', '收货人'),
            'mobile' => Yii::t('app', '手机号'),
            'province_name' => Yii::t('app', '省份名称'),
            'city_name' => Yii::t('app', '城市名称'),
            'county_name' => Yii::t('app', '县/地区名称'),
            'address' => Yii::t('app', '详细地址'),
            'order_time' => Yii::t('app', '订单时间'),
            'order_status' => Yii::t('app', '订单状态：
                                    1，待付款 - 马上支付
                                    2，待发货－提示发货
                                    3，待收货－确认收货
                                    4，待评价－马上评价
                                    5，已完成－再次购买'),
            'pay_time' => Yii::t('app', '支付时间'),
            'pay_type' => Yii::t('app', '支付途径-1，微信；2，支付宝；'),
        ];
    }

    public function getProducts()
    {
        return $this->hasMany(Products::className(),['pro_id'=>'pro_id'])->viaTable('{{%order_product}}', ['order_id' => 'order_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(OrderStatus::className(),['status_val'=>'order_status']);
    }

    public function getAll($uid)
    {
        $lists = $this::find()->joinwith('products')->joinwith('status')->where(['uid'=>$uid])->asArray()->all();
        foreach ($lists as $key => $list) {
            if(is_array($list)){
                foreach ($list['products'] as $k => $products) {
                    unset($lists[$key]['products'][$k]['pro_desc']);
                }
            }
        }
        return $lists;
    }

    public function getAllByStatus($uid,$status)
    {
        $lists = $this::find()->joinwith('products')->joinwith('status')->where(['uid'=>$uid,'order_status'=>$status])->asArray()->all();
        foreach ($lists as $key => $list) {
            if(is_array($list)){
                foreach ($list['products'] as $k => $products) {
                    unset($lists[$key]['products'][$k]['pro_desc']);
                }
            }
        }
        return $lists;
    }
    public function orderAdd($params)
    {
        $this->loadValue($params);
        if($this->save()){
            return $this->primaryKey;
        }
        return false;
    }

    public function getCountByStatus($uid)
    {
        $response = $this::find()->select(['count(*) as nums','order_status'])->where(['uid'=>$uid])->groupBy('order_status')->asArray()->all();
        return $response;
    }
}
