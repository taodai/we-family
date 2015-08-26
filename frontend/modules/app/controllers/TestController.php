<?php

namespace app\modules\app\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\PBooking;
use common\models\PBox;
use common\models\PZx;
use common\models\PZx2;
use common\library\WeixinApi;
use common\library\JPush;
class TestController extends Controller
{
    public function actionIndex()
    {   
        $user=User::find()->andOnCondition("is_author = 1 and openid !='' and status = 1")->all();
        if(count($user)>1999){
            echo 1999;
        }else{
            echo count($user);
        }
        
    }
    public function actionLoaddata()
    {
    	set_time_limit(0);
    	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";
    	echo "<table  style='border:1px;'>";
    	echo "<tr>";
    	echo "<td width='170'>目的港</td>";
    	echo "<td width='80'>船公司</td>";
    	echo "<td width='120'>船名/航次</td>";
    	echo "<td width='100'>提单号</td>";
    	echo "<td width='80'>箱号</td>";
    	echo "<td width='150'>箱型</td>";
    	echo "<td width='100'>开航日</td>";
    	echo "</tr>";
    	$pbook=PBooking::find()->andOnCondition("other_type = 1 and order_del<>2 and load_name = 'NINGBO' and status = 2 and plan_send >=1409414400 and plan_send <1435593600")->orderby('plan_send',SORT_ASC)->all();
    	echo count($pbook);exit;
    	foreach($pbook as $key){
    		echo "<tr>";
    		echo "<td>".$key->unload_name."</td>";
    		echo "<td>".$key->carrier_name."</td>";
    		echo "<td>".$key->ship_name."/".$key->voyage."</td>";
    		echo "<td>".$key->MB_L."</td>";
    		$boxnum=$this->boxnum($key->b_id);
    		echo "<td>".$boxnum."</td>";
    		$str=$this->boxtype2($key->b_id);
    		echo "<td>".$str."</td>";
    		echo "<td>".date("Y-m-d",$key->plan_send)."</td>";
    		echo "</tr>";
    	}
    	echo "</table>";
    }
    public function boxtype2($b_id){//配箱转换代码
    	//echo $b_id;exit;
		$boxtypes=array();
		$model=PBox::find()->andOnCondition("b_id = '$b_id' and is_del=2")->all();
		$str="";
		if (!empty($model)){
			$total = count($model)-1;
			foreach ($model as $key=>$val){
				if (!array_key_exists($val->size.$val->box_type,$boxtypes)){
					$boxtypes[$val->size.$val->box_type]=$val->num;
				}else {
					$boxtypes[$val->size.$val->box_type]=$boxtypes[$val->size.$val->box_type]+$val->num;
				}
				//$str.= $val->num."X".$val->size.$val->box_type." ";
			}
			foreach ($boxtypes as $key=>$val){
				$str.=$key."*".$val." ";
			}
		}else{
			$str="无";
		}
		return $str;
	}
	public function boxnum($b_id){
		$zx=PZx::find()->where(['is_del'=>2,'b_id'=>$b_id])->one();
		if(empty($zx)){
			$zx2=PZx2::find()->where(['is_del'=>2,'b_id'=>$b_id])->one();
			if(empty($zx2)){
				return "无";
			}else{
				if($zx2->zx_boxnum!=""){
					return $zx2->zx_boxnum;
				}else{
					return "无";
				}
			}
		}else{
			if($zx->zx_boxnum!=""){
				return $zx->zx_boxnum;
			}else{
				return "无";
			}
		}
	}
    public function actionTestpay(){
    	$wxapi=new WeixinApi();
    	
    	$openid = 'oxUQauERpokFzy40YC7fcgGKM3sE';
    	$orderId = "PayCash".date("YmdHis").rand(1000,9999);
    	$desc = '用户提现';
    	$money = '1';
    	$data = $wxapi->EnterprisePay($openid,$orderId,$desc, $money);
    	print_r($data);
    }
    public function actionTestpush(){
        echo "<meta charset='utf-8' />";
        $pushObj = new JPush();
        //组装需要的参数
        //$receive = 'all';     //全部
        //$receive = array('tag'=>array('2401','2588','9527'));      //标签
        //$receive = array('alias'=>array('93d78b73611d886a74*****88497f501'));    //别名
        $receive = "all";
        $content = '这是一个测试的推送数据....测试....Hello World...';
        $m_type = 'http';
        $m_txt = 'http://www.iqujing.com/';
        $m_time = '600';        //离线保留时间
     
        //调用推送,并处理
        $result = $pushObj->push($receive,$content,$m_type,$m_txt,$m_time);
        if($result){
            $res_arr = json_decode($result, true);
            if(isset($res_arr['error'])){                       //如果返回了error则证明失败
                echo $res_arr['error']['message'];          //错误信息
                echo $res_arr['error']['code'];             //错误码
                return false;       
            }else{
                //处理成功的推送......
                echo '推送成功.....';
                return true;
            }
        }else{      //接口调用失败或无响应
            echo '接口调用失败或无响应';
            return false;
        }
    }
}
?>