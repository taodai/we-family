<?php

namespace app\modules\product\controllers;

use common\library\tool;
use common\models\ProBrand;
use common\models\ProCategory;
use common\models\Products;
use common\models\ProProperty;
use common\models\ProTags;
use Yii;
use linslin\yii2\curl;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;


class ProductsController extends Controller
{
    public $enableCsrfValidation = false;

    public function init(){
        $this->layout = false;
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->request->hostInfo,//图片访问路径前缀
                    "imagePathFormat" => "/uploads/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionData(){
        $model = new Products();
        $where = [];
        if($cid = Yii::$app->request->get('category')){
            $cateModel = new ProCategory();
            $allCate = $cateModel->getAllCate();
            $allChild = tool::getChild($allCate,$cid);//获取所有子分类ID
            $allChild[] = $cid;
            $where['hql_products.pro_cat'] = $allChild;
        }
        $map = '';
        if($title = Yii::$app->request->get('title')){
            $map = "hql_products.pro_title like '%".$title."%'";
        }
        $condition = ['>','hql_products.pro_status',0];
        //$condition['hql_products.status'] = [1,2,3];//取出未被删除的数据
        $total = $model->find()->where($condition)->andWhere($where)->andOnCondition($map)->count();
        $page = new Pagination(['totalCount' =>$total, 'pageSize' => Yii::$app->request->post('rows')]);
        $page->page = Yii::$app->request->post('page')-1;
        $result = $model->find()->joinWith('cate')->joinWith('brand')->with('attr')->where($condition)->andWhere($where)->andOnCondition($map)->orderBy(['pro_id'=>SORT_DESC])->offset($page->offset)->limit($page->limit)->asArray()->all();

        foreach($result as $key=>$value){
            $result[$key]['pro_time'] = date('Y-m-d H:i:s',$value['pro_time']);
            $property='';
            foreach($value['attr'] as $k=>$v){
                $property .= $v['pp_name'].':'.$v['pp_value'].'；';
            }
            $result[$key]['property'] = $property;
        }

        $returnjson = [
            'total'=>$total,
            'rows'=>$result
        ];

        echo Json::encode($returnjson);
    }


    public function actionShowadd(){
        //获取标签
        $allTag = ProTags::find()->select(['pt_id as id','pt_name as text'])->where(['pt_status'=>1])->asArray()->all();
        //获取品牌
        $allBrand = ProBrand::find()->select(['pb_id as id','pb_name as text'])->where(['pb_status'=>1])->asArray()->all();
        //获取分类
        $cate_model = new ProCategory();
        $allCate = $cate_model->getAllCate();
        $jsonArr = tool::node_merge($allCate);
        $model = new Products();
        if($id = Yii::$app->request->get('id')){
            $result = $model->find()->joinWith('attr')->where(['pro_id'=>$id])->asArray()->one();
            $view = 'edit';
           // var_dump($result);
            if($result['pro_image_file'])$result['pro_image_file'] = explode('|',$result['pro_image_file']);
        }else{
            $result = [];
            $view = 'add';
        }
        return $this->render($view,['model'=>$model,'product'=>$result,'pro_tag'=>Json::encode($allTag),'pro_cate'=>Json::encode($jsonArr),'pro_brand'=>Json::encode($allBrand)]);
    }

    public function actionShowedit(){
        if($id = Yii::$app->request->get('id')){
            $model = new Products();
            $result = $model->find()->where(['pro_id'=>$id])->asArray()->one();
        }
    }

    public function actionAdd(){
        $data = Yii::$app->request->post();
        if(!empty($data['pro_tag'])){
            $data['pro_tag'] = implode(',',$data['pro_tag']);
        }
        //将属性拼装成数组，
        foreach($data['pp_name'] as $key=>$value){

            if($key==0){ //$key=0是新增的
                foreach($data['pp_name'][0] as $k=>$v){
                    $property[$key][] =[
                        'pp_name'=>$data['pp_name'][0][$k],
                        'pp_value'=>$data['pp_value'][0][$k]
                    ];
                }
            }else{
                $property[$key] = [ //$key为id是修改，
                    'pp_name'=>$data['pp_name'][$key],
                    'pp_value'=>$data['pp_value'][$key]
                ];
            }
        }
        $data['pro_image_file'] = implode('|',$data['pro_image_file']);
        $data['pro_desc'] = $data['Products']['pro_desc'];

        unset($data['pp_name']);
        unset($data['pp_value']);
        unset($data['file']);
        unset($data['Products']);

        if(empty($data['pro_id'])){
            $user = Yii::$app->session->get('manager');
            $data['real_name'] = $user->realName;
            $data['pro_creater'] = $user->mid;
            $data['pro_time'] = time();
            unset($data['pro_id']);
            $model = new Products();
            $flag = 1;//新增
        }else{
            $model = Products::findOne($data['pro_id']);
            $flag = 2;//修改
        }
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            $pro_id = $model->attributes['pro_id'];
            $property_model = new ProProperty();
//            foreach($property as $key=>$value){
//                $property_model->isNewRecord = true;
//                $property_model->pp_pro_id = $pro_id;
//                $property_model->pp_name = $value['pp_name'];
//                $property_model->pp_value = $value['pp_value'];
//                $property_model->save()&&$property_model->pp_id=0;
//            }
            foreach($property as $key=>$value){
                if($key==0){ //新增
                    foreach($value as $k=>$v){
                        $property_model->isNewRecord = true;
                        $property_model->pp_pro_id = $pro_id;
                        $property_model->pp_name = $v['pp_name'];
                        $property_model->pp_value = $v['pp_value'];
                        $property_model->save()&&$property_model->pp_id=0;
                    }
                }else{ //修改
                    if(empty($value))continue;
                    $attr = [
                        'pp_name'=>$value['pp_name'],
                        'pp_value'=>$value['pp_value']
                    ];
                    $property_model->updateAll($attr,['pp_id'=>$key]);
                }
            }
            echo $pro_id;
        }else{
            foreach($model->getErrors() as $key=>$value){
                foreach($value as $k=>$v){
                    echo ($k+1).'.'.$v;
                }
            }
        }


    }

    //软删除
    public function actionRemove(){
        if(Yii::$app->request->isAjax){
            $id = \Yii::$app->request->post('id');
            $model = new Products();
            $result = $model->updateAll(['pro_status'=>0],['pro_id'=>$id]);
            echo $result?$result:0;
        }
    }
    public function actionDel(){
        if(Yii::$app->request->isAjax){
            $id = \Yii::$app->request->post('id');

            $pp_model = ProProperty::find()->where(['pp_id' => $id])->one();
            if($pp_model->delete()){
                echo 1;
            }else{
                echo 0;
            }
        }
    }

    public function actionExport(){
        header("Content-Type:text/html; charset=UTF-8");
        $str = '大号活动字母箱,345.83,LM-H003,材质：木质-颜色：彩色,23,LM-H003-1.jpg@#纺锤棒箱,175.00,LM-H014,材质：木质-颜色：彩色,23,LM-H014-1.jpg@#除法心算盒,125.69,LM-H030,材质：木质-颜色：彩色,23,LM-H030-1.jpg@#乘法心算盒,125.69,LM-H031,材质：木质-颜色：彩色,23,LM-H031-1.jpg@#乘法板,90.28,LM-H032,材质：木质-颜色：彩色,23,LM-H032-1.jpg@#三项式,175.00,LM-H037,材质：木质-颜色：彩色,23,LM-H037-1.jpg@#十进制系统,226.39,LM-H051,材质：木质-颜色：彩色,23,LM-H051-1.jpg@#衣饰框架,437.50,LM-H058,材质：木质-颜色：彩色,23,LM-H058-1.jpg@#编结衣饰框,53.47,LM-H060,材质：木质-颜色：彩色,23,LM-H060-1.jpg@#毛毡扣衣饰框,53.47,LM-H062,材质：木质-颜色：彩色,23,LM-H062-1.jpg@#插座圆柱体,578.47,LM-H072,材质：木质-颜色：彩色,23,LM-H072-1.jpg@#粉红塔,238.19,LM-H074,材质：木质-颜色：彩色,23,LM-H074-1.jpg@#几何立体组与投影板,398.61,LM-H076,材质：木质-颜色：彩色,23,LM-H076-1.jpg@#三3原色色板,43.75,LM-H078,材质：木质-颜色：彩色,23,LM-H078-1.jpg@#11色色板,77.78,LM-H079,材质：木质-颜色：彩色,23,LM-H079-1.jpg@#63色色卡,192.36,LM-H080,材质：木质-颜色：彩色,23,LM-H080-1.jpg@#触觉板配对,92.36,LM-H081,材质：木质-颜色：彩色,23,LM-H081-1.jpg@#重量板,121.53,LM-H082,材质：橡木 +松木+ 椴木-颜色：彩色,23,LM-H082-1.jpg@#几何图形示范橱,802.78,LM-H084,材质：木质-颜色：彩色,23,LM-H084-1.jpg@#嗅觉筒,123.61,LM-H086,材质：木质-颜色：彩色,23,LM-H086-1.jpg@#温觉板,109.03,LM-H087,材质：木质-颜色：彩色,23,LM-H087-1.jpg@#布盒（白布）,72.92,LM-H091,材质：木质-颜色：彩色,23,LM-H091-1.jpg@#构成三角形,432.64,LM-H092,材质：木质-颜色：彩色,23,LM-H092-1.jpg@#花朵拼图,34.72,LM-H098,颜色：彩色,23,LM-H098-1.jpg@#马拼图,34.72,LM-H099,颜色：彩色,23,LM-H099-1.jpg@#青蛙拼图,34.72,LM-H100,颜色：彩色,23,LM-H100-1.jpg@#鱼拼图,34.72,LM-H101,颜色：彩色,23,LM-H101-1.jpg@#乌龟拼图,34.72,LM-H102,颜色：彩色,23,LM-H102-1.jpg@#鸟拼图,35.00,LM-H103,颜色：彩色,23,LM-H103-1.jpg@#彩色地球仪,272.22,LM-H116,材质：木质-颜色：彩色,23,LM-H116-1.jpg';

        $arr = explode('@#', $str);
        foreach ($arr as $key => $value) {
            $arr[$key] = explode(',', $value);
        }
        foreach ($arr as $key => $value) {
            $arr[$key][5] = $arr[$key][2].'-1.jpg';
            $arr[$key][3] = explode('-', $arr[$key][3]);
            foreach ($arr[$key][3] as $k => $v) {
                $tmp[$k] = explode('：', $v);
                $arr[$key][3] = $tmp;
            }
        }
        foreach ($arr as $key => $value) {
            $data[$key]['pro_title'] = $arr[$key][0];
            $data[$key]['pro_price'] = $arr[$key][1];
            $data[$key]['pro_sn'] = $arr[$key][2];
            $data[$key]['pro_attr'] = $arr[$key][3];
            $data[$key]['pro_cat'] = $arr[$key][4];
            $data[$key]['pro_image_default'] = $arr[$key][5];
            $data[$key]['pro_image_file'] = $arr[$key][5];
            $data[$key]['pro_prefix'] = 'uploads/products/20150715/';

        }

        $pro_model = new Products();
        $pp_model = new ProProperty();
        foreach($data as $key=>$value){
            $model = clone $pro_model;
            $model->pro_title = $value['pro_title'];
            $model->pro_price = $value['pro_price'];
            $model->pro_sn = $value['pro_sn'];
            $model->pro_cat = $value['pro_cat'];
            $model->pro_image_default = $value['pro_image_default'];
            $model->pro_image_file = $value['pro_image_file'];
            $model->pro_prefix = $value['pro_prefix'];
            $model->pro_sales = 0;
            $model->pro_time = time();
            $model->pro_creater = 1;
            $model->real_name = 'admin';
            $model->save();
            $pro_id = $model->attributes['pro_id'];
            foreach($value['pro_attr'] as $k=>$v){
                $clone_model = clone $pp_model;
                $clone_model->pp_pro_id=$pro_id;
                $clone_model->pp_name=$v[0];
                $clone_model->pp_value=$v[1];
                $clone_model->save()&&$pp_model->pp_id=0;
            }
        }
    }
}
