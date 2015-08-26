<?php

namespace app\modules\weixin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Json;
use yii\helpers\Url;
use common\models\User;
use common\models\AppUserinfo;
use common\models\Lecturer;
use common\models\Agency;
use common\models\CourseNotice;
use common\library\WeixinApi;
use common\phpqrcode\QRcode;
use common\models\WeixinNews;
class HandleController extends Controller
{
    public $enableCsrfValidation = false;
    
    /**
     * @inheritdoc
     */
    private $wechat;
    
    // public function init(){
    //     $configs = array(
    //         'token'  => 'haqile',
    //         'appid'  => 'wx771185e82a1c60af',
    //         'secret'  => 'cf3cab2d3f485f811c3d6079c5a7d4a6',
    //         'mch_id'=>'1239208502',
    //         'payKey'=>'c4a5e8b9b36bae0da7b46aa6e6042a71'
    //     );
    // }
    // public function beforeaction($object)
    // {
    //     $ajaxAction=array('WikiData');
    //     $action=substr($object->actionMethod,6);
    //     if(in_array($action,$ajaxAction)){
    //         header("Access-Control-Allow-Origin: *");
    //     }
    //     return true;
    // }
    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        return true;
    }
    public function actionMenbercenter()
    {
        $get = Yii::$app->request->get();
        $session = Yii::$app->session;
        $session->open();
        $wxapi=new WeixinApi();
        if($get['code']){
            if(isset($session['weixincode'])&&$session['weixincode']){
                if($get['code']==$session['weixincode']){
                    $openid=$session['useropenid'];
                }else{
                    $result=$wxapi->getaccess($get['code']);
                    $access_token=$result->access_token;
                    $openid=$result->openid;
                    $allinfo=$wxapi->getallinfo($openid,$access_token);
                    $session['weixincode']=$get['code'];
                    $session['useropenid']=$openid;
                    $session['nickname']=$allinfo->nickname;
                    $session['headimgurl']=$allinfo->headimgurl;
                    $session['useropenid']=$openid;
                }
            }else{
                $result=$wxapi->getaccess($get['code']);
                $access_token=$result->access_token;
                $openid=$result->openid;
                $allinfo=$wxapi->getallinfo($openid,$access_token);
                $session['weixincode']=$get['code'];
                $session['useropenid']=$openid;
                $session['nickname']=$allinfo->nickname;
                $session['headimgurl']=$allinfo->headimgurl;
                $session['useropenid']=$openid;
            }
            $user=User::find()->where(['openid'=>$openid])->one();
            if(empty($user)){
                return $this->redirect(['weixin-reg', 'code' => $get['code']]);
            }else{
                $info = AppUserinfo::find()->where(['uid'=>$user->uid])->one();
                return $this->render('membercenter',['openid'=>$openid,'info'=>$info,'user'=>$user]);
            }
        }
        // $user=User::find()->where(['uid'=>22])->one();
        // $info = AppUserinfo::find()->where(['uid'=>$user->uid])->one();
        // return $this->render('membercenter',['info'=>$info,'user'=>$user]);
        
    }
    public function actionWeixinReg(){
        
        $get = Yii::$app->request->get();
        $session = Yii::$app->session;
        $session->open();
        $wxapi=new WeixinApi();
        $jsApiParameters="";
        if($get['code']){
            if(isset($get['uid'])){
                $uid=$get['uid'];
            }else{
                $uid="";
            }

            if(isset($session['weixincode'])&&$session['weixincode']){
                if($get['code']==$session['weixincode']){
                    $openid=$session['useropenid'];
                }else{
                    $result=$wxapi->getaccess($get['code']);
                    $access_token=$result->access_token;

                    $openid=$result->openid;
                    $session['weixincode']=$get['code'];
                    $session['useropenid']=$openid;
                    
                    $allinfo=$wxapi->getallinfo($openid,$access_token);
                    $session['nickname']=$allinfo->nickname;
                    $session['headimgurl']=$allinfo->headimgurl;
                    $session['sex']=$allinfo->sex;
                    
                }
            }else{
                $result=$wxapi->getaccess($get['code']);
                $access_token=$result->access_token;

                $openid=$result->openid;
                $session['weixincode']=$get['code'];
                $session['useropenid']=$openid;
                $allinfo=$wxapi->getallinfo($openid,$access_token);
                $session['nickname']=$allinfo->nickname;
                $session['sex']=$allinfo->sex;
                $session['headimgurl']=$allinfo->headimgurl;
                
            }
            $notify_url='http://'.$_SERVER['HTTP_HOST'].Url::toRoute('notify');
            $orderId = date("YmdHis").rand(1000,9999);
            $body='哈奇乐早教学院学费';
            $money=48;
            $session['paymoney']=$money;
            $jsApiParameters=$wxapi->unifiedOrder($openid,$orderId,$body, $money,$notify_url);
            $user=User::find()->where(['openid'=>$openid])->one();
            if(empty($user)){
                $isreg=0;
            }else{
                $isreg=1;
            }
            if(isset($money)){
                $money=$money;
            }else{
                $money="";
            }
            $lec=Lecturer::find()->andOnCondition("is_reg = 1")->all();
            return $this->render('weixin-reg',[
                "user"=>$user,
                'openid'=>$openid,
                'orderid'=>$orderId,
                "isreg"=>$isreg,
                'jsApiParameters'=>$jsApiParameters,
                'code'=>$get['code'],
                'uid'=>$uid,
                'sex'=>$session['sex'],
                'lec'=>$lec,
                'nickname'=>$session['nickname'],
                'headimgurl'=>$session['headimgurl'],
                'money'=>$money
                ]);
        }
        // $lec=Lecturer::find()->andOnCondition("reg_pic !=''")->all();
        // return $this->render('weixin-reg',['isreg'=>0,'lec'=>$lec]);
    }
    public function actionLecturer(){
        $get = Yii::$app->request->get();
        if($get['id']){
            $id=$get['id'];
            $lec=Lecturer::find()->where(['leid'=>$id])->one();
            return $this->render('lecturer',['lec'=>$lec]);
        }
    }
    public function actionRegSucceed(){
        $wxapi=new WeixinApi();
        $get = Yii::$app->request->get();
        $session = Yii::$app->session;
        $session->open();
        $uname=$get['uname'];
        $user=User::find()->where(['uname'=>$uname])->one();
        if(empty($user)){
            $checkcode=$session['checkcode'];
            if($checkcode==$get['checkcode']){
                unset($session['checkcode']);
                $url='http://api.haqile.net/memberApi/member/signup';
                $password=substr($get['uname'],-6);
                $studyid=time();
                $session['studyid']=$studyid;
                $params['password']=$password;
                $params['uname']=$get['uname'];
                $params['studyid']=$studyid;
                $params['openid']=$get['useropenid'];
                $params['is_author']=1;
                $params['gender']=$get['sex'];
                $result=$wxapi->http_post($url,$params);
                $data=json_decode($result);
                $uid=$data->data->uid;
                $uname=$data->data->uname;
                $imgurl=$this->createimg($uid,$uname);


                $infoparams['qrimg_url']=$imgurl;
                $infoparams['userType']="0";
                $infoparams['uname']=$get['uname'];
                $infoparams['uid']=$uid;
                $infoparams['babyName']=$get['nickname'];
                if($get['headimgurl']==""){
                    $get['headimgurl']="http://www.haqile.net/image/mind-detail.jpg";
                }
                $infoparams['picUrl']=$get['headimgurl'];
                
                

                
                $url='http://api.haqile.net/memberApi/member/weixin-signup';
                $result=$wxapi->http_post($url,$infoparams);
                if(isset($get['uid'])){
                    $url='http://api.haqile.net/agencyApi/agency/add-agency';
                    $par['parent_id']=$get['uid'];
                    $par['child_id']=$uid;
                    $par['income']=$get['paymoney'];
                    $par['source']=1;
                    $par['content']='支付哈奇乐商学院学费';
                    $result=$wxapi->http_post($url,$par);
                }
                $url='http://api.haqile.net/memberApi/member/add-pay';
                    $pay['uid']=$uid;
                    $pay['income']=$get['paymoney'];
                    $pay['orderid']=$get['orderid'];
                    $pay['source']=1;
                    $pay['content']='支付哈奇乐商学院学费';
                    $result=$wxapi->http_post($url,$pay);
            }
        }
        $weixincode=$session['weixincode'];
        $studyid=$session['studyid'];
        return $this->render('reg_succeed',['studyid'=>$studyid,'weixincode'=>$weixincode]);
    }
    public function createimg($uid,$uname){
        $logo = "logo.png";//准备好的logo图片 
        $test = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx771185e82a1c60af&redirect_uri=http://www.haqile.net/weixin/handle/weixin-reg?uid='.$uid.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect'; //二维码内容   
        $imgname="image/QRcode/qrcode_".$uname."_img.png";  
        QRcode::getLogoImage($test,$logo,$imgname);
        $imgurl='http://'.$_SERVER['HTTP_HOST']."/".$imgname;
        return $imgurl;
    }
    public function actionPromote(){
        $get = Yii::$app->request->get();
        $uid=$get['uid'];
        $info = AppUserinfo::find()->where(['uid'=>$uid])->one();
        return $this->render('promote',['info'=>$info,'uid'=>$uid]);
    }
    public function actionAgency(){
        $get = Yii::$app->request->get();
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
        //print_r($income);
        $info = AppUserinfo::find()->where(['uid'=>$uid])->one();
        return $this->render('agency',['info'=>$info,'first'=>$first,'second'=>$second]);
    }
    public function actionAgencyDetail(){
        $get = Yii::$app->request->get();
        $uid=$get['uid'];
        $level=$get['level'];
        $wxapi=new WeixinApi();
        $url='http://api.haqile.net/agencyApi/agency/get-income-by-level';
        $par['userid']=$uid;
        $par['level']=$level;
        $result=$wxapi->http_post($url,$par);
        $result=json_decode($result);
        return $this->render('agency-detail',['result'=>$result,'level'=>$level]);
    }
    public function actionProfit(){
        $get = Yii::$app->request->get();
        $uid=$get['uid'];
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
        
        return $this->render('profit',['leveltwo'=>$leveltwo,'levelthree'=>$levelthree,'oneper'=>$oneper,'twoper'=>$twoper]);

    }
    public function actionProfitDetail(){
        $get = Yii::$app->request->get();
        $uid=$get['uid'];
        $wxapi=new WeixinApi();
        $url='http://api.haqile.net/agencyApi/agency/income-list';
        $par['userid']=$uid;
        $result=$wxapi->http_post($url,$par);
        $result=json_decode($result);
        return $this->render('profit-detail',['result'=>$result]);
    }
    public function actionClass(){
        $get = Yii::$app->request->get();
        $session = Yii::$app->session;
        $session->open();
        $wxapi=new WeixinApi();
        if($get['code']){
            if(isset($session['weixincode'])&&$session['weixincode']){
                if($get['code']==$session['weixincode']){
                    $openid=$session['useropenid'];
                }else{
                    $result=$wxapi->getaccess($get['code']);
                    $access_token=$result->access_token;
                    $openid=$result->openid;
                    $session['weixincode']=$get['code'];
                    $session['useropenid']=$openid;
                }
            }else{
                $result=$wxapi->getaccess($get['code']);
                $access_token=$result->access_token;
                $openid=$result->openid;
                $session['weixincode']=$get['code'];
                $session['useropenid']=$openid;
            }
            $code=$get['code'];
            $user=User::find()->where(['openid'=>$openid])->one();
            if(empty($user)){
                $isreg=0;
            }else{
                $isreg=1;
            }
            $time=time();
            $class=CourseNotice::find()->where(['status'=>0])->andOnCondition('share_time > '.$time)->orderby('share_time',SORT_ASC)->all();
            return $this->render('class',['class'=>$class,'code'=>$code,'isreg'=>$isreg,'openid'=>$openid]);
        }
        
    }
    public function actionClassDetail(){
        $get = Yii::$app->request->get();
        $id=$get['id'];
        $openid=$get['openid'];
        $user=User::find()->joinWith('info')->where(['openid'=>$openid])->one();
        $class=CourseNotice::find()->joinWith('lecturer')->where(['cnid'=>$id])->one();
        return $this->render('class-detail',['class'=>$class,'user'=>$user,'id'=>$id,'openid'=>$openid]);
    }
    public function actionNewsDetail(){
        $get = Yii::$app->request->get();
        $id=$get['id'];
        $news=WeixinNews::find()->where(['id'=>$id])->one();
        return $this->render('news-detail',['news'=>$news]);
    }
    public function actionHelp(){
        return $this->render('help');
    }
    
    // public function actionNotify(){
    //     //接受微信支付结果通知，返回数组,http://mch.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_7
    //     $arr = $this->getNotify();
    //     //处理
    // }
    //调用查询接口http://mch.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_2
    // public function actionOrderInfo(){
    //     //$type=1 传入$oprderId 微信订单号;0:商户订单号
    //     $orderId = '';
    //     $this->getOrderInfo($orderId,$type=0);
    // }
    
}
?>