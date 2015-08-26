<?php

namespace api\modules\products\controllers;

use common\library\tool;
use common\models\AppFavorite;
use common\models\AppWiki;
use common\models\ProCategory;
use common\models\Products;
use common\models\ProTags;
use common\models\ProEvaluate;
use Yii;
use api\controllers\AbstractController;


class ProductsController extends AbstractController
{
    public $enableCsrfValidation = false;

    /**
     * 商品详情列表
     */
    public function actionList(){
        $params = Yii::$app->request->post();
        $page = isset($params['page']) ? $params['page'] : 1;
        $rows = isset($params['rows']) ? $params['rows'] : 10;
        $condition['pro_status'] = 1;

        if(isset($params['order'])){

            if($params['order']=='price'){
                $order = ['pro_price'=>SORT_ASC];
            }elseif($params['order']=='sales'){
                $order = ['pro_sales'=>SORT_DESC];
            }else{
                $order = ['pro_time'=>SORT_DESC];
            }
        }else{
            $order = ['pro_time'=>SORT_DESC];
        }
        $where = [];
        if(isset($params['pc_id'])){
            if($params['pc_id']!=0){
                $cid = $params['pc_id'];
                $cateModel = new ProCategory();
                $allCate = $cateModel->getAllCate();
                $allChild = tool::getChild($allCate,$cid);//获取所有子分类ID
                $allChild[] = $cid;
                $where['hql_products.pro_cat'] = $allChild;
            }
        }

        if(isset($params['brand'])){
            $condition['pro_brand'] = $params['brand'];
        }

        $sql = '';
        if(isset($params['tag'])){
            foreach($params['tag'] as $key=>$value){
                $sql .="find_in_set($value,pro_tag)".' or ';
            }
            $sql= substr($sql,0,-3);
        }
        //$order = isset($params['price']) ? "pro_price {$params['price']}" : 'pro_time desc';
        $proModel = new Products();
        $total = $proModel->find()->count();
        $totalPage =  ceil($total/$rows);
        $result = $proModel->find()->joinWith('cate')->joinWith('brand')->with('attr')
            ->where($condition)->andWhere($sql)->andWhere($where)
            ->orderBy($order)->offset($rows*($page-1))->limit($rows)->asArray()->all();
        $web = Yii::$app->request->hostInfo;
        $url = str_replace('api','admin',$web);
        foreach($result as $key=>$value){
            $result[$key]['img'] = $url.'/'.$value['pro_prefix'].$value['pro_image_default'];
        }

        $this->_formatResponse(1,'正常',$result,$totalPage);
    }

    /**
     * 获取商品分类
     */
    public function actionGetCategory(){
        $params = Yii::$app->request->post();
        if(isset($params['rows'])){
            $rows = $params['rows'];
        }else{
            $rows = '';
        }
        $model = new ProCategory();
        $condition['pc_fid'] = 0;
        $condition['pc_status'] = 1;
        $result = $model->find()->where($condition)->limit($rows)->orderBy(['pc_id'=>SORT_DESC])->asArray()->all();
        if($result){
            $this->_formatResponse(1,'正常',$result);
        }else{
            $this->_formatResponse(2,'获取数据失败');
        }

    }

    public function actionAllCate(){
        $model = new ProCategory();
        $condition['pc_status'] = 1;
        $result = $model->find()->where($condition)->orderBy(['pc_id'=>SORT_DESC])->asArray()->all();
        $category = $model->node_merge($result);
        $this->_formatResponse(1,'正常',$category);
    }
    /**
     * 获取商品详情
     */
    public function actionGetOne(){
        $id = Yii::$app->request->post('id');
        $proModel = new Products();
        $field = ['pro_id','pro_title','pro_sn','pro_cat','pro_brand','pro_store','pro_prefix','pro_image_default','pro_image_file','pro_price','pro_price'];
        $product = $proModel->find()
            ->joinWith('cate')->joinWith(['brand'])->joinWith('attr')
            ->where(['pro_id'=>$id,'pro_freight'=>1])->asArray()->one();
        if($product){
            $web = Yii::$app->request->hostInfo;
            $url = str_replace('api','admin',$web);
            $product['pro_freight']=$product['pro_freight']?'包邮':'';
            $product['pro_coupon']=$product['pro_coupon']?'优惠券可用':'';
            $product['pro_image_file'] = explode('|',$product['pro_image_file']);
            //获取tag标签
            if($product['pro_tag']){
                $tagId = explode(',',$product['pro_tag']);
                $tag = ProTags::find()->select(['pt_name'])->where(['pt_id'=>$tagId])->asArray()->all();
                foreach($tag as $k=>$v){
                    $product['tag'][] = $v['pt_name'];
                }
            }
            foreach($product['pro_image_file'] as $k=>$v){
                $product['pro_img'][]['img'] = $url.'/'.$product['pro_prefix'].$v;
            }
            $this->_formatResponse(1,'正常',$product);
        }else{
            $this->_formatResponse(2,'获取数据失败');
        }

    }

    public function actionFavorite(){
        $params = Yii::$app->request->post();
        $favorite_model = new AppFavorite();
        $condition['userid'] = $params['userid'];
        $condition['favorite_type'] = $params['favorite_type'];
        $condition['favorite_id'] = $params['favorite_id'];
        $result = AppFavorite::find()->where($condition)->one();
        if($result){
            if($result['status']==0){
                $data['info'] = '已收藏';
            }else{
                $result->status = 0;
                $result->addtime = time();
                $result->save();
                $data['info'] = '收藏成功';
            }
        }else{
            $data['userid'] = $params['userid'];
            $data['username'] = $params['username'];
            $data['favorite_type'] = $params['favorite_type'];
            $data['favorite_id'] = $params['favorite_id'];
            $data['addtime'] = time();
            $data['status'] = 0;
            $favorite_model->attributes = $data;
            $favorite_model->save();
            $data['info'] = '收藏成功';
        }
        $this->_formatResponse(1,'正常',$data);
    }

    public function actionFavoriteList(){
        $params = Yii::$app->request->post();
        $page = isset($params['page']) ? $params['page'] : 1;
        $rows = isset($params['rows']) ? $params['rows'] : 10;
        $favorite_model = new AppFavorite();
        $condition = [
            'favorite_type'=>$params['favorite_type'],
            'userid'=>$params['userid']
        ];
        $favorite = $favorite_model->find()->select(['favorite_id'])->where($condition)->asArray()->all();
        $favoriteId=[];
        if($favorite){
            foreach($favorite as $key=>$value){
                $favoriteId[] = $value['favorite_id'];
            }
        }

        if($params['favorite_type']=='mall'){
            $model = new Products();
            $map['pro_id'] = $favoriteId;
        }elseif($params['favorite_type']=='wiki'){
            $model = new AppWiki();
            $map['id'] = $favoriteId;
        }
        $result = $model->find()->where($map)->offset($rows*($page-1))->limit($rows)->asArray()->all();
        if($params['favorite_type']=='mall'){
            $web = Yii::$app->request->hostInfo;
            $url = str_replace('api','admin',$web);
            foreach($result as $key=>$value){
                $result[$key]['img'] = $url.'/'.$value['pro_prefix'].$value['pro_image_default'];
            }
        }else{

        }

        $this->_formatResponse(1,'正常',$result);

    }

    //添加商品评价
    public function actionProEvaluate()
    {
        $params = json_decode($this->params['params']);
        if(is_array($params)){
            $proEvaluate = new ProEvaluate();
            $response = $proEvaluate->addEvaluate($params);
            if($response){
                $this->_formatResponse(1,'评价商品成功');
            }else{
                $this->_formatResponse(2,'评价商品失败');
            }
        }else{
            $this->_formatResponse(3,'参数格式错误');
        }
    }
}
