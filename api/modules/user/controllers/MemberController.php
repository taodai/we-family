<?php

namespace api\modules\user\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\SmsInfo;
use common\models\AppUserinfo;
use common\models\UserPay;

class MemberController extends AbstractController
{

    public $modelClass = 'common\models\User';
    public $session = false;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        return true;
    }
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
    /**
    * 自定义方法开启
    */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        // 使用"prepareDataProvider()"方法自定义数据provider 
        // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function actionEvent(){
          echo '这是事件处理<br/>';
           
          // $person = new Person();
           
          // $this->on('SayHello', [$person,'say_hello'],'你好，朋友');
           
          // $this->on('SayGoodBye', ['app\models\Person','say_goodbye'],'再见了，我的朋友');
           
          $this->on('GoodNight', function(){
              echo '晚安！';
          });
           
           
          // $this->trigger('SayHello');
          // $this->trigger('SayGoodBye');
          $this->trigger('GoodNight');
           
      }

    

    //查询单个会员信息
    public function actionGetOne()
    {
        $params = Yii::$app->request->get();
        if(isset($params['type'])){
            $this->_formatResponse(100,'未开放');
        }else{
            $modelClass = $this->modelClass;
            $model = $modelClass::findOne($params['id']);
            return $model;
        }
    }

    //会员注册
    public function actionSignup()
    {
        $params = Yii::$app->request->post();
        //if(isset($params['uname']) && isset($params['code']) && isset($params['password'])){
            //$codeIsValid = $this->_checkCode($params['uname'],$params['code']);
            //if($codeIsValid == 1){
                $modelClass = new $this->modelClass;
                $response = $modelClass->signup($params);
                if($response){
                    $this->_formatResponse(1,'注册成功',$response);
                }else{
                    $this->_formatResponse(4,'注册失败');
                }
            //}else{
                //$msg = ($codeIsValid == 2) ? '验证码已过期' : '无效验证码';
                //$this->_formatResponse($codeIsValid,$msg);
            //}
        //}else{
            //$this->_formatResponse(5,'参数错误');
        //}
    }

    //会员注册(新)
    public function actionUserSignup()
    {
        $params = Yii::$app->request->post();

        $modelClass = new $this->modelClass;
        $response = $modelClass->signup($params);
        if($response){
            $params['uid']=$response['uid'];
            $model = AppUserinfo::find()->where(['uid'=>$params['uid']])->one();
            if(empty($model)){
                $model = new AppUserinfo();
            }
            //$model = $info ? $info : new AppUserinfo();
            foreach ($params as $key => $value) {
                if($key!="password"&&$key!='openid'&&$key!="studyid"&&$key!="is_author"&&$key!="gender"){
                    $model->$key = $value;
                }
                
            }
            $returnModel = $model->save();
            if($returnModel==1){
                $msg="录入信息成功";
            }else{
                $returnModel=2;
                $msg="录入信息失败";
            }
            $this->_formatResponse($returnModel,$msg,$model);
            
        }else{
            $this->_formatResponse(4,'注册失败');
        }
    
    }
    //会员注册(微信)
    public function actionWeixinSignup()
    {
        $params = Yii::$app->request->post();
        if($params){
            $model = AppUserinfo::find()->where(['uname'=>$params['uname']])->one();
            if(empty($model)){
                $model=new AppUserinfo();
            }
            foreach ($params as $key => $value) {
                $model->$key = $value;
            }
            $returnModel = $model->save();
            if($returnModel==1){
                $msg="录入信息成功";
            }else{
                $returnModel=2;
                $msg="录入信息失败";
            }
            $this->_formatResponse($returnModel,$msg,$model);
            
        }else{
            $this->_formatResponse(4,'注册失败');
        }
    }

    public function actionAddPay()
    {
        $params = Yii::$app->request->post();
        if($params){
            $model = UserPay::find()->where(['orderid'=>$params['orderid']])->one();
            if(empty($model)){
                $model=new UserPay();
            }
            foreach ($params as $key => $value) {
                $model->$key = $value;
            }
            $model->pay_time=time();
            $returnModel = $model->save();
            if($returnModel==1){
                $msg="录入支付信息成功";
            }else{
                $returnModel=2;
                $msg="录入支付信息失败";
            }
            $this->_formatResponse($returnModel,$msg,$model);
            
        }else{
            $this->_formatResponse(4,'录入失败');
        }
    }
    //检查验证码
    public function actionCheckCode()
    {
        $params = Yii::$app->request->post();
        $codeIsValid = $this->_checkCode($params['uname'],$params['code']);
        if($codeIsValid == 1){
            $msg="验证通过";
            $this->_formatResponse($codeIsValid,$msg);
        }else{
            $msg = ($codeIsValid == 2) ? '验证码已过期' : '无效验证码';
            $this->_formatResponse($codeIsValid,$msg);
        }
    }

    //会员登录
    public function actionLogin()
    {
        $params = Yii::$app->request->post();
        if($params){
            $model = new User();
            $user = $model->login($params);
            if(is_object($user)){
                $sessionData = [
                    'uid' => $user->uid,
                    'realName' => $user->uname
                ];
                unset($user->password);
                $this->session->set('user',(object)$sessionData);
                $this->_formatResponse(1,'登录成功',$user);
            }else{
                $this->_formatResponse($user,$user==2 ? '用户名或密码错误' : '用户不存在');
            }
        }
    }

    public function actionList()
    {
        $modelClass = $this->modelClass;

        $provider = new ActiveDataProvider([
            'query' => $modelClass::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $this->_formatResponse(1,'获取列表成功',$provider->getModels(),$provider->getPagination());
    }

    //查询验证码是否正确
    private function _checkCode($mobile,$code)
    {
        $smsInfo = new SmsInfo();
        return $smsInfo->codeIsValid($mobile,$code);
    }

    public function actionGetInfo()
    {
        $params = Yii::$app->request->post();
        if(isset($params['uid'])){
            $appUserinfo = new AppUserinfo();
            $model = $appUserinfo->getByUid($params['uid']);
            $this->_formatResponse($model?1:2,$model?'获取用户信息成功':'获取用户信息失败',$model);
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    public function actionInfoSave()
    {
        $params = Yii::$app->request->post();
        $info = AppUserinfo::find()->where(['uid'=>$params['uid']])->one();

        $model = $info ? $info : new AppUserinfo();
        foreach ($params as $key => $value) {
            $model->$key = $value;
        }
        $returnModel = $model->save();
        $this->_formatResponse($returnModel?1:2,$returnModel?'录入信息成功':'录入信息失败',$model);
    }
}
