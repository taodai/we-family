<?php

namespace app\modules\content\controllers;

use common\library\tool;
use common\models\AppCategory;
use common\models\AppWiki;
use common\models\UploadForm;
use Yii;
use linslin\yii2\curl;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class WikiController extends Controller
{
    public $enableCsrfValidation = false;

    public  $month =[
            ['id'=>0,'text'=>'备孕期','children'=>[]],
            ['id'=>100,'text'=>'怀孕期','state'=>'closed',
                'children'=>[
                    ['id'=>1,'text'=>'第1个月'],
                    ['id'=>2,'text'=>'第2个月'],
                    ['id'=>3,'text'=>'第3个月'],
                    ['id'=>4,'text'=>'第4个月'],
                    ['id'=>5,'text'=>'第5个月'],
                    ['id'=>6,'text'=>'第6个月'],
                    ['id'=>7,'text'=>'第7个月'],
                    ['id'=>8,'text'=>'第8个月'],
                    ['id'=>9,'text'=>'第9个月'],
                    ['id'=>10,'text'=>'第10个月']
                ]
            ],
            ['id'=>200,'text'=>'新生儿(0-1岁)',
                'children'=>[
                    ['id'=>11,'text'=>'第1个月'],
                    ['id'=>12,'text'=>'第2个月'],
                    ['id'=>13,'text'=>'第3个月'],
                    ['id'=>14,'text'=>'第4个月'],
                    ['id'=>15,'text'=>'第5个月'],
                    ['id'=>16,'text'=>'第6个月'],
                    ['id'=>17,'text'=>'第7个月'],
                    ['id'=>18,'text'=>'第8个月'],
                    ['id'=>19,'text'=>'第9个月'],
                    ['id'=>20,'text'=>'第10个月'],
                    ['id'=>21,'text'=>'第11个月'],
                    ['id'=>22,'text'=>'第12个月']
                ]
            ],
            ['id'=>300,'text'=>'婴幼儿(1-3岁)',
                'children'=>[
                    ['id'=>23,'text'=>'13-15个月'],
                    ['id'=>26,'text'=>'16-18个月'],
                    ['id'=>29,'text'=>'19-21个月'],
                    ['id'=>32,'text'=>'22-24个月'],
                    ['id'=>35,'text'=>'25-27个月'],
                    ['id'=>38,'text'=>'28-30个月'],
                    ['id'=>41,'text'=>'31-33个月'],
                    ['id'=>44,'text'=>'34-36个月']

                ]
            ],
            ['id'=>400,'text'=>'学龄前(3-6岁)',
                'children'=>[
                    ['id'=>47,'text'=>'37-42个月'],
                    ['id'=>53,'text'=>'43-48个月'],
                    ['id'=>59,'text'=>'49-54个月'],
                    ['id'=>65,'text'=>'55-60个月'],
                    ['id'=>71,'text'=>'61-66个月'],
                    ['id'=>77,'text'=>'67-72个月']

                ]
            ]
        ];
    public function init(){
        $this->layout = 'layouts';
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
        //获取顶级分类
        $module = Yii::$app->request->get('module');
        $wiki_model = new AppWiki();
        return $this->renderPartial('index',['module'=>$module,'model'=>$wiki_model]);
    }

    public function actionData(){
        $model = new AppWiki();
        if($cid = Yii::$app->request->get('category')){
            $cateModel = new AppCategory();
            $allCate = $cateModel->getAllCate(2);
            $allChild = tool::getChild($allCate,$cid);//获取所有子分类ID
            $allChild[] = $cid;
            $condition['hql_app_wiki.category'] = $allChild;
        }
        $map = '';
        if($title = Yii::$app->request->get('title')){
            $map = "hql_app_wiki.title like '%".$title."%'";
        }
        $condition['hql_app_wiki.status'] = 0;//取出未被删除的数据
        $total = $model->find()->where($condition)->andOnCondition($map)->count();
        $page = new Pagination(['totalCount' =>$total, 'pageSize' => $_POST['rows']]);
        $page->page = Yii::$app->request->post('page')-1;
        $result = $model->find()->joinWith('cate')->andOnCondition($map)->where($condition)->orderBy(['id'=>SORT_DESC])->offset($page->offset)->limit($page->limit)->asArray()->all();
        foreach($result as $key=>$value){
            $result[$key]['addtime'] = date('Y-m-d H:i:s',$value['addtime']);
        }
        $returnjson = [
            'total'=>$total,
            'rows'=>$result
        ];
        echo Json::encode($returnjson);
    }

    public function actionShowadd(){

        $wiki_model = new AppWiki();
        $model = new AppCategory();
        $category = $model->find()->where(['module'=>2,'status'=>0])->orderBy(['rank'=>SORT_ASC])->asArray()->all();
        //格式化成easyui可用的json
        $data = [];
        foreach($category as $key=>$value){
            $data[$key]['id'] = $value['id'];
            $data[$key]['text'] = $value['category'];
            $data[$key]['pid'] = $value['pid'];
        }
        $tree = tool::node_merge($data);
        return $this->render('add',['category'=>Json::encode($tree),'model'=>$wiki_model]);
    }

    public function actionShowedit(){
        $month = $this->month;
        if($id = Yii::$app->request->get('id')){
            $wiki_model = new AppWiki();
            $model = new AppCategory();
            $category = $model->find()->where(['module'=>2,'status'=>0])->orderBy(['rank'=>SORT_ASC])->asArray()->all();
            $wiki = $wiki_model->find()->where(['id'=>$id])->asArray()->one();
            $data = [];
            foreach($category as $key=>$value){
                $data[$key]['id'] = $value['id'];
                $data[$key]['text'] = $value['category'];
                $data[$key]['pid'] = $value['pid'];
            }
            $tree = tool::node_merge($data);
            $month_time = '';
            $str = '';

            if($wiki['pregnancy']!=''&&!empty($wiki['month'])){
                $str = ',';
            }
            if($wiki['pregnancy']!=''){
                $month_time .= $wiki['pregnancy'].$str;
            }
            if(!empty($wiki['month'])){
                $month_time .= $wiki['month'];
            }
            $tempArr = explode(',',$month_time);
            foreach ($month as $key=>$value){
                if(in_array($value['id'],$tempArr))$month[$key]['checked'] = true;
                foreach($value['children'] as $k=>$v){
                    if(in_array($v['id'],$tempArr))$month[$key]['children'][$k]['checked'] = true;
                }
            }
            $monthJson = Json::encode($month);
            return $this->render('edit',['category'=>Json::encode($tree),'model'=>$wiki_model,'wiki'=>$wiki,'month'=>$monthJson]);
        }
    }
    public function actionValid(){
        $title = Yii::$app->request->post('title');
        $result = AppWiki::find()->where(['title'=>$title])->one();
        if($result){
            $data['info'] = '已存在该标题的信息';
            $data['status'] = 0;
        }else{
            $data['status'] = 1;
        }
        echo Json::encode($data);
    }
    public function actionAdd(){
        $data = Yii::$app->request->post();
        $data['content'] = $data['AppWiki']['content'];
        unset($data['month']);
        $monthStr = $data['months'];
        $tmpArr = explode(',',$monthStr);
        $month = [];
        $pregnancy = [];
        foreach($tmpArr as $key=>$value){
            if($value<=10){
                $pregnancy[] = $value;
            }else{
                if($value>=100)continue;
                $month[] = $value;
                if($value>=23 && $value<=44){
                    $month[] = ++$value;
                    $month[] = ++$value;
                }elseif($value>=47 && $value<=77){
                    $month[] = ++$value;
                    $month[] = ++$value;
                    $month[] = ++$value;
                    $month[] = ++$value;
                    $month[] = ++$value;
                }
            }

        }
        $data['tag'] = str_replace("，", ",", $data['tag']);
        $data['month'] = implode(',',$month);
        $data['pregnancy'] = implode(',',$pregnancy);
        unset($data['AppWiki']['content']);

        if(empty($data['id'])){
            $user = Yii::$app->session->get('manager');
            $data['real_name'] = $user->realName;
            $data['addtime'] = time();
            unset($data['id']);
            $model = new AppWiki();
            $flag = 1;//新增
        }else{
            $model = AppWiki::findOne($data['id']);
            $flag = 2;//修改
        }
        $model->attributes = $data;
        if($model->validate()){
            $model->save();
            echo $flag;
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
            $model = new AppWiki();
            $result = $model->updateAll(['status'=>1],['id'=>$id]);
            echo $result?$result:0;
        }
    }

    public function actionSetmonth(){
        $month = [];
        $wiki_model = new AppWiki();
        $id = Yii::$app->request->get('id');
        $wiki = $wiki_model->find()->where(['id'=>$id])->asArray()->one();
        $tempArr = explode(',',$wiki);
        foreach ($month as $key=>$value){
            if(in_array($value['id'],$tempArr))$month[$key]['checked'] = true;
            foreach($value['children'] as $k=>$v){
                if(in_array($v['id'],$tempArr))$month[$key]['children'][$k]['checked'] = true;
            }
        }
    }

    public function actionGetmonth(){
        $month = $this->month;
        echo Json::encode($month);
    }



}
