<?php

namespace api\modules\order\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\components\OrderCode;
use common\models\OrderProduct;
use common\models\Cart;
use common\models\OrderStatus;

class OrderController extends AbstractController
{

    public $modelClass = 'common\models\Orders';
    public $session = false;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    // public function behaviors()
    // {
    //     $behaviors = parent::behaviors();
    //     $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
    //     return $behaviors;
    // }


    public function init()
    {
        $this->session = Yii::$app->session;
    }

    //获取用户订单
    public function actionGetOrders()
    {
        if(isset($this->params['uid'])){
            $page = isset($params['page']) ? $params['page'] : 1;
            $rows = isset($params['rows']) ? $params['rows'] : 10;

            $modelClass = new $this->modelClass;

            $statusArr = $modelClass->getCountByStatus($this->params['uid']);
            // var_dump($response);exit;
            $listArr = $modelClass->getAll($this->params['uid']);
            $total = $modelClass->find(['uid'=>$this->params['uid']])->count();
            $totalPage =  ceil($total/$rows);
            $orderStatusArr = OrderStatus::find()->asArray()->offset($rows*($page-1))->limit($rows)->all();
            $response = array('data'=>$listArr,'statusarr'=>$statusArr,'orderStatus'=>$orderStatusArr);
            if($response){
                $this->_formatResponse(1,'订单列表获取成功',$response,$totalPage);
            }else{
                $this->_formatResponse(2,'订单列表获取失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //根据状态值获取用户订单数据
    public function actionGetOrdersStatus()
    {
        if(isset($this->params['uid']) && isset($this->params['order_status'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->getAllByStatus($this->params['uid'],$this->params['order_status']);
            if($response){
                $this->_formatResponse(1,'订单列表获取成功',$response);
            }else{
                $this->_formatResponse(2,'订单列表获取失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    public function actionOrderAdd()
    {
        if(isset($this->params['uid'])){
            //生成订单编码
            $orderCode = new OrderCode();
            $orderCode->rangeOrderCode();
            $this->params['order_code'] = $orderCode->orderCode;
            $this->params['order_time']  = time();
            $this->params['order_status'] = 1;
            if(isset($this->params['pro_id'])){
                $product = $this->params['pro_id'];
                $modelClass = new $this->modelClass;
                $response = $modelClass->orderAdd($this->params); //返回新增的order_id;
                if($response){
                    $orderProduct = new OrderProduct();
                    $return = $orderProduct->addUnion($product,$response);
                    if($return){
                        $proIdStr = implode(',',explode('|', $this->params['pro_id']));
                        $isDelCart = Cart::deleteAll('uid='.$this->params['uid'].' and pro_id in ('.$proIdStr.')');
                        if($isDelCart){
                            $order_code = $this->params['order_code'];
                            $this->_formatResponse(1,'订单成功',['order_code'=>$order_code]);
                        }else{
                            $this->_formatResponse(6,'购物车数据清除失败，请检查');  
                        }
                    }else{
                        $model = new $this->modelClass;
                        $delOrder = $model::findOne($response);
                        $delOrder->delete();
                        $this->_formatResponse(5,'无法插入商品数据,请检查参数是否合法');
                    }
                }else{
                    $this->_formatResponse(2,'订单失败');
                }
            }else{
                $this->_formatResponse(4,'无效订单,没有选择商品'); 
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    public function actionUpdateOrderStatus()
    {
        if(isset($this->params['order_id']) && isset($this->params['order_status'])){
            $modelClass = new $this->modelClass;
            $model = $modelClass::findOne($this->params['order_id']);
            $model->order_status = $this->params['order_status'];
            if($model->save()){
                $this->_formatResponse(1,'订单状态更新成功'); 
            }else{
                $this->_formatResponse(2,'订单状态更新失败'); 
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }
}