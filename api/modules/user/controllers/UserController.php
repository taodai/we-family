<?php 
namespace api\modules\user\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\web\Controller;
use yii\helpers\Json;
use common\models\AppUserinfo;
use common\models\Area;
use common\models\Province;
use common\models\City;
use common\library\WeixinApi;
class UserController extends AbstractController
{
	public function actionAddress(){
		$province=Province::find()->select(['province_code','province_name'])->asArray()->All();
		$data['province']=$province;
		$city=City::find()->select(['province_code','city_code','city_name'])->asArray()->All();
		$data['city']=$city;
		$area=Area::find()->select(['area_code','area_name','city_code'])->asArray()->All();
		$data['area']=$area;
		$this->_formatResponse(1,'正常',$data);
	}
	public function actionUserInfo(){
		$params = Yii::$app->request->post();
		$user = AppUserinfo::find()->where(['uid'=>$params['uid'],'uname'=>$params['uname']])->one();
		$usertypearr=array('未怀孕','备孕期','怀孕期','家有小宝','未入园','幼儿园','小学阶段');
		$data['usertypename']=$usertypearr[$user->userType];
		$data['babyName']=$user->babyName;
		$data['picUrl']=$user->picUrl;
		$this->_formatResponse(1,'正常',$data);
	}
	public function actionUpdateUserinfo(){
		$params = Yii::$app->request->post();
		$user = AppUserinfo::find()->where(['uid'=>$params['uid'],'uname'=>$params['uname']])->one();
		foreach ($params as $key => $value) {
            if($key=="babyName"){
            	$name = AppUserinfo::find()->where(['babyName'=>$params['babyName']])->one();
				if(!empty($name)){
					$this->_formatResponse(2,'该昵称无法使用');exit;
				}else{
					$user->$key = $value;
				}
            }else{
            	$user->$key = $value;
            }
        }
        if($user->save()){
        	$this->_formatResponse(1,'正常');
        }else{
        	$this->_formatResponse(3,'信息保存失败');
        }
            
		
	}
	public function actionPromote(){
		$params = Yii::$app->request->post();
		$wxapi=new WeixinApi();
		$url='http://api.haqile.net/agencyApi/agency/get-income';
		$par['uid']=$params['uid'];
		$result=$wxapi->http_post($url,$par);
		$income=json_decode($result);
		//print_r($income);
		if(empty($income->data)){
			$incomefee="0.00";
			$cash_left="0.00";
		}else{
			$incomefee=$income->data->income_total;
			$cash_left=$income->data->cash_left;
		}
		$data['incomefee']=$incomefee;
		$data['cash_left']=$cash_left;
		$this->_formatResponse(1,'正常',$data);
	}
	public function actionAgency(){
        $get = Yii::$app->request->post();
        $uid=$get['uid'];
        $wxapi=new WeixinApi();
        $url='http://api.haqile.net/agencyApi/agency/get-agency';
        $par['uid']=$uid;
        $par['level']=2;
        $result=$wxapi->http_post($url,$par);
        $result=json_decode($result);
        $first=count($result->data);
        $par['level']=3; 
        $result=$wxapi->http_post($url,$par);
        $result=json_decode($result);
        $second=count($result->data);
        $data['first']=$first;
        $data['second']=$second;
        $this->_formatResponse(1,'正常',$data);

    }
    public function actionAgencyDetail(){
        $post = Yii::$app->request->post();
        $uid=$post['uid'];
        $level=$post['level'];
        $wxapi=new WeixinApi();
        $url='http://api.haqile.net/agencyApi/agency/get-income-by-level';
        $par['userid']=$uid;
        $par['level']=$level;
        $result=$wxapi->http_post($url,$par);
        $result=json_decode($result);
        $data=$result->data;
        if(!empty($data)){
        	foreach($data as $key=>$val){
        		$info[$key]['picUrl']=$val->userinfo->picUrl;
        		$info[$key]['babyName']=$val->userinfo->babyName;
        		$info[$key]['income']=$val->income;
        		if($level==2){
        			$referrer=AppUserinfo::getByUid($val->userid);
        		}else if($level==3){
        			$referrer=AppUserinfo::getthreelevelname($val->child_id);
        		}
        		$info[$key]['referrer']=$referrer->babyName;
        	}
        }else{
        	$info=null;
        }
        $this->_formatResponse(1,'正常',$info);
    }
    public function actionProfit(){
        $post = Yii::$app->request->post();
        $uid=$post['uid'];
        $wxapi=new WeixinApi();
        $url='http://api.haqile.net/agencyApi/agency/get-income-all-by-level';
        $par['userid']=$uid;
        $result=$wxapi->http_post($url,$par);
        $result=json_decode($result);
        $leveltwo=$result->data->leveltwo;
        $levelthree=$result->data->levelthree;
        if($leveltwo==0&&$levelthree==0){
            $oneper=0;
            $twoper=0;
        }else{
            $total=$leveltwo+$levelthree;
            $oneper=round(($leveltwo/$total),2)*100;
            $twoper=100-$oneper;
        }
        $data['oneper']=$oneper;
        $data['twoper']=$twoper;
        $data['leveltwo']=$leveltwo;
        $data['levelthree']=$levelthree;
        $this->_formatResponse(1,'正常',$data);

    }
    public function actionProfitDetail(){
        $post = Yii::$app->request->post();
        $uid=$post['uid'];
        $wxapi=new WeixinApi();
        $url='http://api.haqile.net/agencyApi/agency/income-list';
        $par['userid']=$uid;
        $result=$wxapi->http_post($url,$par);
        $result=json_decode($result);
        $data=$result->data;
        if(!empty($data)){
        	foreach($data as $key=>$val){
        		$info[$key]['income']=$val->income;
        		$info[$key]['income_time']=date("Y-m-d",$val->income_time);
        		if($val->level==2){
        			$info[$key]['level']="一级代理";
        		}else if($val->level==3){
        			$info[$key]['level']="二级代理";
        		}
        		$user=AppUserinfo::getByUid($val->child_id);
        		$info[$key]['referrer']=$user->babyName;
        	}
        }else{
        	$info=null;
        }
        $this->_formatResponse(1,'正常',$info);
    }
}
?>