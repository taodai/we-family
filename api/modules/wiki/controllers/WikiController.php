<?php

namespace api\modules\wiki\controllers;

use Yii;
use api\controllers\AbstractController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\web\Controller;
use yii\helpers\Json;
use common\models\AppWiki;
use common\models\AppTips;
use common\models\AppPregnanttips;
use common\models\AppWarmtips;
use common\models\AppUserinfo;
use common\models\AppCategory;
class WikiController extends AbstractController
{

    public function actionIndex()
    {
        return $this->render('index');
    }
    public function beforeAction($action)
    {
        header("Access-Control-Allow-Origin: *");
        parent::beforeAction($action);
        return true;
    }
    public function actionFound()
    {
    	$params = Yii::$app->request->post();

    	$user = AppUserinfo::find()->where(['uid'=>$params['uid'],'uname'=>$params['uname']])->one();
    	$info=array();
        $sql="";
    	if($user->userType==2){
    		$info['userType']=2;
    		$info['babyName']=$user->babyName;

    		$duedate=$user->babyDueDate;
            if($duedate==0){
                $sql="status = 0";
                $info['tips']="";
                $info['baby_height']="0";
                $info['baby_weight']="0";
                $info['babyage']="0";
            }else{
                $today=(strtotime(date("Y-m-d"))-($duedate-280*24*60*60))/(24*60*60);
                $info['babyage']=$today;
                $pre=AppPregnanttips::find()->where(['day'=>$today])->one();
                if(!empty($pre)){
                    $info['tips']=$pre->tips;
                    $info['baby_height']=$pre->baby_height."毫米";
                    $info['baby_weight']=$pre->baby_weight."克";
                }else{
                    $info['tips']="";
                    $info['baby_height']="";
                    $info['baby_weight']="";
                }
                

                $month=intval($today/30);
                $sql="pregnancy like '%".$month."%' and status = 0";
            }
    		
    		
			
			
    	}else if($user->userType>=3){
    		$info['userType']=3;
    		$info['babyName']=$user->babyName;
    		$birdate=$user->babyBirthday;

    		if($birdate==0){
                $sql="status = 0";
                $info['tips']="";
                $info['baby_height']="0";
                $info['baby_weight']="0";
                $info['babyage']="0";
            }else{
                $today=(strtotime(date("Y-m-d"))-($birdate))/(24*60*60);
            
                $info['babyage']=$today;
                $pre=AppWarmtips::find()->where(['day'=>$today])->one();
                
                if(!empty($pre)){
                    if($user->babySex==1){
                    $info['baby_height']=$pre->boy_height."厘米";
                    $info['baby_weight']=$pre->boy_weight."千克";
                }else if($user->babySex==2){
                    $info['baby_height']=$pre->girl_height."厘米";
                    $info['baby_weight']=$pre->girl_weight."千克";
                }
                }else{
                    $info['tips']="";
                    $info['baby_height']="";
                    $info['baby_weight']="";
                }
                
                $month=intval($today/30)+10;
                $sql="month like '%".$month."%' and status = 0";
            }
    		
    		
			
			
    	}else if($user->userType<=1){
    		$info['userType']=1;
    		$sql="month = '' and status = 0";
    		
			
			
    	}
        
    	$data=$this->getcatenews($sql,1);
    	
    	$i=0;
    	foreach($data['news'] as $key=>$val){
    		$news[$i]['type']=$data['news'][$key][0]['type'];
    		$news[$i]['id']=$data['news'][$key][0]['id'];
    		$news[$i]['title']=$data['news'][$key][0]['title'];
    		$i++;
    	}
    	$info['news']=$news;
        $this->_formatResponse(1,'正常',$info);
        
    }
    function actionNewsdetail(){
    	$params = Yii::$app->request->post();
    	$wiki = AppWiki::find()->where(['id'=>$params['newsid']])->one();
    	$info['title']=$wiki->title;
    	$info['time']=date("Y-m-d H:i:s",$wiki->addtime);
    	$info['tag']=$wiki->tag;
    	$info['picUrl']=$wiki->picUrl;
    	$info['content']=$wiki->content;
    	$info['videoUrl']=$wiki->videoUrl;
    	//echo json_encode($info);exit;
    	$this->_formatResponse(1,'正常',$info);
    }
    function actionWiki(){
    	$params = Yii::$app->request->post();

    	$user = AppUserinfo::find()->where(['uid'=>$params['uid'],'uname'=>$params['uname']])->one();
    	$info=array();
    	if($user->userType<=1){
    		$info['userType']=1;
    		$sql="month = '' and status = 0";
		}else if($user->userType==2){
			$info['userType']=2;
			$duedate=$user->babyDueDate;
            if($duedate==0){
                $sql="status = 0";
                $info['tips']="";
                $info['babyage']="0";
            }else{
                $today=(strtotime(date("Y-m-d"))-($duedate-280*24*60*60))/(24*60*60);
                $month=intval($today/30);
                $pre=AppTips::find()->where(['month'=>$month])->one();
                
                if(!empty($pre)){
                    $info['tips']=$pre->notice;
                }else{
                    $info['tips']="";
                }
                $info['babyage']=$month;
                $sql="month like '%".$month."%' and status = 0";
            }
    		
		}else if($user->userType>=3){
			$info['userType']=3;
			$birdate=$user->babyBirthday;
            if($birdate==0){
                $sql="status = 0";
                $info['tips']="";
                $info['babyage']="0";
            }else{
                $today=(strtotime(date("Y-m-d"))-($birdate))/(24*60*60);
                $month=intval($today/30)+10;
                $pre=AppTips::find()->where(['month'=>$month])->one();
                
                if(!empty($pre)){
                    $info['tips']=$pre->notice;
                }else{
                    $info['tips']="";
                }
                $info['babyage']=($month-10);
                $sql="month like '%".$month."%' and status = 0";
            }
    		
    		
		}
			$data=$this->getcatenews($sql,5);	  
			        
			
			$info['news']=$data['news'];
			$info['cate']=$data['cate'];

			$session = Yii::$app->session;
            $session->open();
			$session['cate'.$params['uname']]=$data['cate'];
    		
    	
    	$this->_formatResponse(1,'正常',$info);
    }
    function getcatenews($sql,$limit){

    	$cates=AppCategory::find()->where(['status'=>0,'pid'=>0])->all();
    	foreach($cates as $key=>$val){
    		$catearr=array();
    		array_push($catearr,$val->id);
    		$session = Yii::$app->session;
            $session->open();
			$session['categorys']=$catearr;
    		$typearr=$this->getcatetypes($val->id);
    		$typestr=implode(',',$session['categorys']);
    		$wiki = AppWiki::find()->andOnCondition($sql." and category in (".$typestr.")")
						    	   ->orderBy(['addtime'=>SORT_DESC])
						    	   ->limit($limit)
						           ->all();
    		if(!empty($wiki)){
    			foreach($wiki as $k=>$v){
    				$data['news'][$val->category][$k]['type']=$val->category;
	    			$data['news'][$val->category][$k]['id']=$v->id;
	    			$data['news'][$val->category][$k]['title']=$v->title;
    			}
    			$data['cate'][$key]['name']=$val->category;
    			$data['cate'][$key]['id']=$val->id;
    		}
    	}
    	return $data;
    }
    function getcatetypes($cid){
    	$cate=AppCategory::find()->where(['pid'=>$cid,'status'=>0])->all();
    	if(!empty($cate)){
    		foreach($cate as $key=>$val){
    			$session = Yii::$app->session;
                $session->open();
				$catearr=$session['categorys'];
    			array_push($catearr,$val->id);
    			$session['categorys']=$catearr;
    			$this->getcatetypes($val->id);
    		}
    	}
    }
    function actionCateWiki(){
    	$params = Yii::$app->request->post();

    	$page = $params['page'] ? $params['page'] : 1;
    	$session = Yii::$app->session;
        $session->open();
		$newcates=$session['cate'.$params['uname']];
    	
    	$user = AppUserinfo::find()->where(['uid'=>$params['uid'],'uname'=>$params['uname']])->one();
    	$cate=AppCategory::find()->where(['category'=>$params['catename']])->one();
    	$catearr=array();
		array_push($catearr,$cate->id);
		$session['categorys']=$catearr;
		$typearr=$this->getcatetypes($cate->id);
		$typestr=implode(',',$session['categorys']);
    	//echo $cate->id;exit;
    	$info=array();
    	if($user->userType<=1){
    		$info['userType']=1;
    		$sql="month = '' and status = 0 and category in(".$typestr.")";
    		
						           
		}else if($user->userType==2){
			$info['userType']=2;
			$duedate=$user->babyDueDate;
            if($duedate==0){
                $sql="status = 0 and category in(".$typestr.")";
                $info['tips']="";
                $info['babyage']="0";
            }else{
                $today=(strtotime(date("Y-m-d"))-($duedate-280*24*60*60))/(24*60*60);
                $month=intval($today/30);
                $pre=AppTips::find()->where(['month'=>$month])->one();
                
                if(!empty($pre)){
                    $info['tips']=$pre->notice;
                }else{
                    $info['tips']="";
                }
                $info['babyage']=$month;
                $sql="month like '%".$month."%' and status = 0 and category in(".$typestr.")";
            }
    		
    		
		}else if($user->userType>=3){
			$info['userType']=3;
			$birdate=$user->babyBirthday;
            if($birdate==0){
                $sql="status = 0 and category in(".$typestr.")";
                $info['tips']="";
                $info['babyage']="0";
            }else{
                $today=(strtotime(date("Y-m-d"))-($birdate))/(24*60*60);
                $month=intval($today/30)+10;
                $pre=AppTips::find()->where(['month'=>$month])->one();
                
                if(!empty($pre)){
                    $info['tips']=$pre->notice;
                }else{
                    $info['tips']="";
                }
                $info['babyage']=$month;
                $sql="month like '%".$month."%' and status = 0 and category in(".$typestr.")";
            }
    		
    		
		}
		$wiki = AppWiki::find()->andOnCondition($sql)
							   ->offset(20*($page-1))
					    	   ->orderBy(['addtime'=>SORT_DESC])
					    	   ->limit(20)
					           ->all();
		if($wiki){

			foreach($wiki as $key=>$val){
				$news[$key]['type']=$val->cate->category;
				$news[$key]['info']=$val->info;
				$news[$key]['picUrl']=$val->picUrl;
				$news[$key]['id']=$val->id;
				$news[$key]['title']=$val->title;
			}	
		}else{
			$wiki = AppWiki::find()->andOnCondition("status = 0 and category in(".$typestr.")")
								   ->offset(20*($page-1))
						    	   ->orderBy(['addtime'=>SORT_DESC])
						    	   ->limit(20)
						           ->all();
			foreach($wiki as $key=>$val){
				$news[$key]['type']=$val->cate->category;
				$news[$key]['info']=$val->info;
				$news[$key]['picUrl']=$val->picUrl;
				$news[$key]['id']=$val->id;
				$news[$key]['title']=$val->title;
			}
		}
		$info['news']=$news;
		$info['cate']=$newcates;

		$this->_formatResponse(1,'正常',$info);
    }
    
    function actionAllCate(){
    	$cates=AppCategory::find()->where(['status'=>0])->all();
    	foreach($cates as $key=>$val){
    		$catearr[$key]['name']=$val->category;
    		$soncates=AppCategory::find()->where(['pid'=>$val->id])->all();
    		if(!empty($soncates)){
    			foreach($soncates as $k=>$v){
    				$sonarr[$k]=$v->category;
    			}
    			$catearr[$key]['son']=$sonarr;
    		}else{
    			$catearr[$key]['son']="";
    		}
    	}
    	//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    	//print_r($catearr);exit;
    	$this->_formatResponse(1,'正常',$catearr);
    }
}
