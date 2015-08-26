<?php

namespace api\modules\order\controllers;

use common\library\AlipayNotify;
use common\models\Orders;
use common\models\UserPayment;
use Yii;
use yii\web\Controller;

class AlipayController extends Controller
{
    public $enableCsrfValidation = false;
    public $session = false;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];


    public function actionNotify(){

        $alipay_config = Yii::$app->params['alipay_config'];

        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result){

            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') { //不支持退款
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //该种交易状态只在两种情况下出现
                //1、开通了普通即时到账，买家付款成功后。
                //2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
                //调试用，写文本函数记录程序运行情况是否正常
                $condition['order_code'] = $_POST['out_trade_no'];
                $order_model = new Orders();
                $order = $order_model->find()->where($condition)->asArray()->one();
                if($order['order_status']==1){
                    $order_model->updateAll(['order_status'=>2,'pay_type'=>2],$condition);
                    $user_pay_model = new UserPayment();
                    $data['pay_time'] = strtotime($_POST['gmt_payment']);
                    $data['desc'] ='购买商品:'.$_POST['body'];
                    $data['money'] = $_POST['total_fee'];
                    $data['uid'] = $order['uid'];
                    $user_pay_model->attributes = $data;
                    $user_pay_model->save();
                }

            }
            echo "success";
        }else{
            echo "fail";
        }
    }

}