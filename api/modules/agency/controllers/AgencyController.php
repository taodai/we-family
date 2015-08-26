<?php

namespace api\modules\agency\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\UserAccount;
use common\models\UserIncome;
use common\models\UserPayment;

class AgencyController extends AbstractController
{

    public $modelClass = 'common\models\Agency';
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
    /**
    * 自定义方法开启
    */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);

        return $actions;
    }

    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        return true;
    }

    //按级别查询代理信息
    public function actionGetAgency()
    {
        $post = Yii::$app->request->post();
        if(isset($post['uid']) && isset($post['level'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->getAgencyByParentId($post['uid'],$post['level']);
            if($response){
                $this->_formatResponse(1,'获取列表成功',$response);
            }else{
                $this->_formatResponse(2,'获取列表失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //添加代理关系
    public function actionAddAgency()
    {
        if(isset($this->params['parent_id']) && isset($this->params['child_id']))
        {
            $modelClass = new $this->modelClass;
            $response = $modelClass->addAgency($this->params['parent_id'],$this->params['child_id']);
            switch ($response) {
                // case 3:
                //     $this->_formatResponse(1,'添加代理关系成功');
                //     break;
                case 2:
                    // $this->_addPayment($this->params);
                    // if($this->_addIncome($this->params)){
                        if(true){
                        $user = new User();
                        $userClone = clone $user;
                        $user->getAgencyByUid($this->params['parent_id']); 
                        $userClone->getAgencyByUid($this->params['child_id']); 
                        $this->_formatResponse(1,'添加代理关系成功');
                    }else{
                        $this->_formatResponse(5,'收益信息添加失败');
                    }
                    break;
                case 1:
                    $this->_formatResponse(3,'已存在代理关系');
                    break;
            }
        }else{
            $this->_formatResponse(4,'参数错误');
        }
    }

    private function _addPayment($params)
    {
        $arr = array();
        if(isset($params['child_id']) && isset($params['income'])){
            $modelClass = new UserPayment();
            $arr['uid'] = $params['child_id'];
            $arr['money'] = $params['income'];
            $arr['desc'] = $params['content'];
            $response = $modelClass->addPayment($arr);
            if($response){
                $this->_formatResponse(1,'添加成功');
            }else{
                $this->_formatResponse(2,'添加失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //查询用户所有代理信息
    public function actionGetAgencyAll()
    {
        $post = Yii::$app->request->post();
        if(isset($post['uid'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->getAgencyAll($post['uid']);
            if($response){
                $this->_formatResponse(1,'获取列表成功',$response);
            }else{
                $this->_formatResponse(2,'获取列表失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //代理收益总额(按等级返回)
    public function actionGetIncome()
    {
        if(isset($this->params['uid'])){
            $model = new UserAccount();
            $response = $model->getIncome($this->params['uid']);
            if($response){
                $this->_formatResponse(1,'获取收益成功',$response);
            }else{
                $this->_formatResponse(2,'获取收益失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //查找当前用户的代理
    public function actionFindAgency()
    {
        if(isset($this->params['uid']) && isset($this->params['level'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->findAgencyUpAll($this->params['uid']);
            return $response;
        }
    }

    //收益明细增加
    public function actionIncomeAdd()
    {
        if(isset($this->params['uid']) && isset($this->params['income']) && isset($this->params['source']) && isset($this->params['content'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->findAgencyUpAll($this->params['uid']);
            $parentIdArr = array();
            if($response){
                $model = new UserIncome();
                foreach ($response as $key => $value) {
                    if(!in_array($value['parent_id'],$parentIdArr)){
                        array_push($parentIdArr,$value['parent_id']);
                    }
                    $income = clone $model;
                    if(is_array($value)){
                        $income->userid = $value['parent_id'];   
                        $income->child_id = $this->params['uid'];   
                        $income->level = $value['level']; 
                        $incomeMoney =  round(($value['level'] == 2) ? $this->params['income'] * 0.5 : $this->params['income'] * 0.5 * 0.5,2);
                        $income->income = $incomeMoney;  
                        $income->income_time = time();
                        $income->source = $this->params['source']; 
                        $income->content = $this->params['content']; 
                        $income->save();
                    }
                }
                $this->_addAccount($parentIdArr);
                $this->_formatResponse(1,'收益添加成功');
            }else{
                $this->_formatResponse(2,'无代理关系');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //
    private function _addIncome($params)
    {
        if(isset($params['child_id']) && isset($params['income']) && isset($params['source']) && isset($params['content'])){
            $modelClass = new $this->modelClass;
            $response = $modelClass->findAgencyUpAll($params['child_id']);
            $parentIdArr = array();
            if($response){
                $model = new UserIncome();
                foreach ($response as $key => $value) {
                    if(!in_array($value['parent_id'],$parentIdArr)){
                        array_push($parentIdArr,$value['parent_id']);
                    }
                    $income = clone $model;
                    if(is_array($value)){
                        $income->userid = $value['parent_id'];   
                        $income->child_id = $params['child_id'];   
                        $income->level = $value['level']; 
                        $incomeMoney =  round(($value['level'] == 2) ? $params['income'] * 0.5 : $params['income'] * 0.5 * 0.5,2);
                        $income->income = $incomeMoney;  
                        $income->income_time = time();
                        $income->source = $params['source']; 
                        $income->content = $params['content']; 
                        $income->save();
                    }
                }
                $this->_addAccount($parentIdArr);
                return true;
                // $this->_formatResponse(1,'收益添加成功');
            }else{
                return false;
                // $this->_formatResponse(2,'无代理关系');
            }
        }
    }

    //按代理等级返回当前等级代理的明细表（按代理用户ID返回）
    public function actionGetIncomeByLevel()
    {
        if(isset($this->params['userid'])){
            $model = new UserIncome();
            $response = $model->getIncomeListByLevel($this->params['userid'],$this->params['level']);
            if($response){
                $this->_formatResponse(1,'获取成功',$response);
            }else{
                $this->_formatResponse(2,'获取失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //按代理级别查询收益总额
    public function actionGetIncomeAllByLevel()
    {
        if(isset($this->params['userid'])){
            $model = new UserIncome();
            $response = $model->getIncomeAllByLevel($this->params['userid']);
            if($response){
                $this->_formatResponse(1,'获取成功',$response);
            }else{
                $this->_formatResponse(2,'获取失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    //收益明细列表
    public function actionIncomeList()
    {
        if(isset($this->params['userid'])){
            $modelClass = new UserIncome();
            $response = $modelClass->getIncomeList($this->params['userid']);
            if($response){
                $this->_formatResponse(1,'获取列表成功',$response);
            }else{
                $this->_formatResponse(2,'获取列表失败');
            }
        }else{
            $this->_formatResponse(3,'参数错误');
        }
    }

    private function _addAccount($idArr)
    {
        if(is_array($idArr)){
            foreach ($idArr as $key => $value) {
                $modelClass = new UserIncome();
                $_modelClass = clone $modelClass;
                $response = $_modelClass->getIncomeAllByLevel($value);
                if($response){
                    $account = UserAccount::find()->where(['uid'=>$value])->one();
                    if($account){
                        $account->income_total = $response['leveltwo'] + $response['levelthree'];
                        $account->income_first = $response['leveltwo'];
                        $account->income_second = $response['levelthree'];
                        $account->cash_left = floor($account->income_total/100)*100;
                        $account->save();
                        // if($account->save()){
                        //     return true;
                        // }
                    }else{
                        $model = new UserAccount();
                        $_model = clone $model;
                        $_model->income_total = $response['leveltwo'] + $response['levelthree'];
                        $_model->income_first = $response['leveltwo'];
                        $_model->income_second = $response['levelthree'];
                        $_model->cash_left = floor($_model->income_total/100)*100;
                        $_model->uid = $value;
                        $_model->save();
                        // if($_model->save()){
                        //     return true;
                        // }
                    }
                }
            }
            return true;
        }
        return false;
    }
}
