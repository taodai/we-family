<?php

namespace app\modules\weixin\controllers;

use Yii;
use yii\web\Controller;
use common\library\tool;
use yii\helpers\Json;
use common\models\WeixinMenu;
use common\models\WeixinNews;
use common\models\WeixinKey;
use common\models\WeixinMessage;
use common\models\WeixinInput;
use yii\imagine\Image;
use common\models\UploadForm;
use yii\web\UploadedFile;

class HandleController extends Controller
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
                    "imagePathFormat" => "/uploads/weixinimage/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }
    public function actionIndex()
    {
    	$menu=WeixinMenu::find()->where(['pid'=>'0'])->all();
        $keys=WeixinKey::find()->all();

        return $this->render('menu',['menu'=>$menu,'keys'=>$keys]);
    }
    
    public function actionNews()
    {
        $news=new WeixinNews();
        return $this->render('news',['model'=>$news]);
    }
    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        return true;
    }

    public function actionMenuList()
    {
        $data = WeixinMenu::find()->orderby('sort',SORT_ASC)->asArray()->all();
        foreach($data as $key=>$val){
            if($data[$key]['menu_type']=="click"){
                $keys=WeixinKey::find()->where(['id'=>$data[$key]['key']])->one();
                $data[$key]['keyname']=$keys->key;
            }else if($data[$key]['menu_type']=="view"){
                $data[$key]['keyname']="无";
            }
        }
        $menu = tool::node_merge($data);
        //print_r($menu);exit;
        $returnjson = [
            //'total'=>$total,
            'rows'=>$menu
        ];
        echo Json::encode($returnjson);
    }

    public function actionMenuAdd()
    {
        $post = Yii::$app->request->post();
        $menu = new WeixinMenu();
        $menu->loadValue($post);
        if ($menu->save()) {
            $out['suc'] = true;
        }
        //print_r(get_class_methods($model));exit;
        echo json_encode($out);
    }

    public function actionMenuEdit()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        $menu=WeixinMenu::find()->where(['id'=>$get['id']])->one();
        $menu->loadValue($post);
        if ($menu->save()) {
            $out['suc'] = true;
        }
        echo json_encode($out);
    }
    public function actionNewsList()
    {
        $data = WeixinNews::find()->where(['status'=>0])->orderby('addtime',SORT_DESC)->asArray()->all();
        if(!empty($data)){
        	foreach($data as $key=>$val){
        		$data[$key]['addtime']=date("Y-m-d H:i:s",$data[$key]['addtime']);
        	}
        }
        
        echo Json::encode($data);
    }

    public function actionNewsAdd()
    {
        $post = Yii::$app->request->post();
        $news = new WeixinNews();
        $news->loadValue($post);
        if ($news->save()) {
            $out['suc'] = true;
        }
        //print_r(get_class_methods($model));exit;
        echo json_encode($out);
    }

    public function actionNewsEdit()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        $news=WeixinNews::find()->where(['id'=>$get['id']])->one();
        $news->loadValue($post);
        if ($news->save()) {
            $out['suc'] = true;
        }
        echo json_encode($out);
    }
    public function actionShowEdit()
    {
        $get = Yii::$app->request->get();
        $news=WeixinNews::find()->where(['id'=>$get['id']])->one();
        return $this->render('news-edit',['news'=>$news]);
    }
    public function actionShowAdd()
    {
        $news=new WeixinNews();
        return $this->render('news-add',['news'=>$news]);
    }
    public function actionKeys()
    {
        $message=WeixinMessage::find()->all();
        $news=WeixinNews::find()->where(['status'=>0])->all();
        return $this->render('keys',['message'=>$message,'news'=>$news]);
    }
    public function actionKeysList()
    {
        $data = WeixinKey::find()->asArray()->all();
        if(!empty($data)){
            foreach($data as $key=>$val){
                if($data[$key]['type']==1){
                    $data[$key]['typename']="消息";
                    $message=WeixinMessage::find()->where(['id'=>$data[$key]['return_id']])->one();
                    $data[$key]['title']=$message->title;
                }else if($data[$key]['type']==2){
                    $data[$key]['typename']="图文";
                    $news=WeixinNews::find()->where(['id'=>$data[$key]['return_id']])->one();
                    $data[$key]['title']=$news->title;
                }
                $data[$key]['message_id']=$data[$key]['return_id'];
                $data[$key]['news_id']=$data[$key]['return_id'];
            }
        }
        echo Json::encode($data);
    }

    public function actionKeysAdd()
    {
        $post = Yii::$app->request->post();
        if($post['type']==1){
            $post['return_id']=$post['message_id'];
        }else if($post['type']==2){
            $post['return_id']=$post['news_id'];
        }
        $keyname=$post['key'];
        $key=WeixinKey::find()->where(['key'=>$keyname])->one();
        if(!empty($key)){
            $out['errorMsg']="该key已存在";
        }else{
            $keys = new WeixinKey();
            $keys->loadValue($post);
            if ($keys->save()) {
                $out['suc'] = true;
            }
        }
        
        //print_r(get_class_methods($model));exit;
        echo json_encode($out);
    }

    public function actionKeysEdit()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        if($post['type']==1){
            $post['return_id']=$post['message_id'];
        }else if($post['type']==2){
            $post['return_id']=$post['news_id'];
        }
        $keyname=$post['key'];
        $key=WeixinKey::find()->where(['key'=>$keyname])->andOnCondition("id != ".$get['id'])->one();
        if(!empty($key)){
            $out['errorMsg']="该key已存在";
        }else{
            $keys=WeixinKey::find()->where(['id'=>$get['id']])->one();
            $keys->loadValue($post);
            if ($keys->save()) {
                $out['suc'] = true;
            }
        }
        
        echo json_encode($out);
    }
    public function actionMessage()
    {
        return $this->render('message');
    }
    public function actionMessageList()
    {
        $data = WeixinMessage::find()->asArray()->all();
        echo Json::encode($data);
    }

    public function actionMessageAdd()
    {
        $post = Yii::$app->request->post();
        $message = new WeixinMessage();
        $message->loadValue($post);
        if ($message->save()) {
            $out['suc'] = true;
        }
        //print_r(get_class_methods($model));exit;
        echo json_encode($out);
    }

    public function actionMessageEdit()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        $message=WeixinMessage::find()->where(['id'=>$get['id']])->one();
        $message->loadValue($post);
        if ($message->save()) {
            $out['suc'] = true;
        }
        echo json_encode($out);
    }
    public function actionInput()
    {
        $message=WeixinMessage::find()->all();
        $news=WeixinNews::find()->where(['status'=>0])->all();
        return $this->render('input',['message'=>$message,'news'=>$news]);
    }
    public function actionInputList()
    {
        $data = WeixinInput::find()->asArray()->all();
        if(!empty($data)){
            foreach($data as $key=>$val){
                if($data[$key]['type']==1){
                    $data[$key]['typename']="消息";
                    $message=WeixinMessage::find()->where(['id'=>$data[$key]['return_id']])->one();
                    $data[$key]['title']=$message->title;
                }else if($data[$key]['type']==2){
                    $data[$key]['typename']="图文";
                    $news=WeixinNews::find()->where(['id'=>$data[$key]['return_id']])->one();
                    $data[$key]['title']=$news->title;
                }
                $data[$key]['message_id']=$data[$key]['return_id'];
                $data[$key]['news_id']=$data[$key]['return_id'];
            }
        }
        echo Json::encode($data);
    }

    public function actionInputAdd()
    {
        $post = Yii::$app->request->post();
        if($post['type']==1){
            $post['return_id']=$post['message_id'];
        }else if($post['type']==2){
            $post['return_id']=$post['news_id'];
        }
        $inputname=$post['input'];
        $data=WeixinInput::find()->where(['input'=>$inputname])->one();
        if(!empty($data)){
            $out['errorMsg'] = "该input信息已存在";
        }else{
            $input = new WeixinInput();
            $input->loadValue($post);
            if ($input->save()) {
                $out['suc'] = true;
            }
        }
        
        //print_r(get_class_methods($model));exit;
        echo json_encode($out);
    }

    public function actionInputEdit()
    {
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        if($post['type']==1){
            $post['return_id']=$post['message_id'];
        }else if($post['type']==2){
            $post['return_id']=$post['news_id'];
        }
        $inputname=$post['input'];
        $data=WeixinInput::find()->where(['input'=>$inputname])->andOnCondition("id != ".$get['id'])->one();
        if(!empty($data)){
            $out['errorMsg'] = "该input信息已存在";
        }else{
            $input=WeixinInput::find()->where(['id'=>$get['id']])->one();
            $input->loadValue($post);
            if ($input->save()) {
                $out['suc'] = true;
            }
        }
        
        echo json_encode($out);
    }
    public function actionUploadfile(){
        $name=rand(1000,9999)."image";
		$image = UploadedFile::getInstanceByName('thumb');
		

        $uploaddir = 'uploads/weixinnews/'; //主目录
        $subDir = $uploaddir.date('Ym').'/'; //子目录
        
        if(!file_exists($uploaddir)){
            if(!is_dir($uploaddir) || is_writable($uploaddir)){
                mkdir($uploaddir);
            }
        }
        if(!file_exists($subDir)) {
            if (!is_dir($subDir) || is_writable($subDir)) {
                mkdir($subDir);
            }
        }
        $filename=date("YmdHis");
        $image->saveAs($subDir . $filename . '.' . $image->extension);
        $url = Yii::$app->request->hostInfo.'/'.$subDir.$filename.".".$image->extension;
        $pic = 
        $result['url']=$url;
        $result['pic']=$filename . '.' . $image->extension;
        echo json_encode($result);
    }

}
