<?php 
namespace app\modules\weixin\controllers;
use Yii;
use yii\web\Controller;
use common\weixin\HaqileWechat;
use common\models\AppWiki;
use common\models\WeixinMenu;
use common\models\WeixinNews;
use common\models\WeixinInput;
use common\models\WeixinKey;
use common\models\WeixinMessage;
class WeiXinController extends Controller{
	public $enableCsrfValidation = false;
	public function actionHaqile(){
		$options = array(
			'token'=>'haqilezkytest', //填写你设定的key
				'debug'=>true,
				'logcallback'=>'logdebug',
				'appid'=>'wx335a9eae94cd7f92',
				'appsecret'=>'9b14fb9ab259188a0f740744879f5349'
		);
		$weObj = new HaqileWechat($options);
		//$weObj->valid();
		if (isset($_GET['echostr'])) {
			
	    	$weObj->valid();
		}else{
			// $fp=fopen('log.txt',"a+");
			// fwrite($fp,"\n test");
			// fclose($fp);
			// $weObj->responseMsg();
			$menu=$this->getmenu();
	    	$this->responseMsg($weObj,$menu);
		}
	}
	/**
     * 处理消息方法
     */
    public function responseMsg($weObj,$newmenu){
    	//$weObj = new Wechat($options);
	    $result = $weObj->createMenu($newmenu);
	    
	    
		
		$type = $weObj->getRev()->getRevType();
		
		switch($type) {
			case HaqileWechat::MSGTYPE_TEXT:
					$content = $weObj->getRev()->getRevContent();
					$input = WeixinInput::find()->where(['input'=>$content])->one();
					if(empty($input)){
						$weObj->text("no inputs match")->reply();
						exit;
					}else{
						if($input->type==1){
							$message=WeixinMessage::find()->where(['id'=>$input->return_id])->one();
							$weObj->text($message->message)->reply();
							exit;
						}else if($input->type==2){
							$news=WeixinNews::find()->where(['id'=>$input->return_id])->all();
							$news=$this->newsdata($news);
							$weObj->news($news)->reply();
							exit;
						}
					}
					break;
			case HaqileWechat::MSGTYPE_EVENT:
					$type = $weObj->getRev()->getEventType();
					//$weObj->text($type)->reply();
					switch ($type){
						case HaqileWechat::EVENTTYPE_SUBSCRIBE:
							$weObj->text("欢迎关注哈奇乐早教公众平台")->reply();
							break;
						case HaqileWechat::EVENTTYPE_CLICK:
							$event=$weObj->getRev()->getRevEvent();
							$keys=WeixinKey::find()->where(['key'=>$event['key']])->one();
							if(empty($keys)){
								$weObj->text("no keys match")->reply();
								exit;
							}else{
								if($keys->type==1){
									$message=WeixinMessage::find()->where(['id'=>$keys->return_id])->one();
									$weObj->text($message->message)->reply();
									exit;
								}else if($keys->type==2){
									$news=WeixinNews::find()->where(['id'=>$keys->return_id])->all();
									$news=$this->newsdata($news);
									$weObj->news($news)->reply();
									exit;
								}
							}
							break;
					}
					break;
			case HaqileWechat::MSGTYPE_IMAGE:
					break;
			default:
					$weObj->text("help info")->reply();
		}
		
    }
    /**		
     * 资讯内容
     */
    public function newsdata($news){
        foreach($news as $key=>$val){
            $content=mb_substr(trim($val->description),0,40);
            $pic=$val->picUrl;
            $newsarr[$key]=array(
                'Title'=>$val->title,
                'Description'=>$content,
                'PicUrl'=>$pic,
                'Url'=>'http://wxtest.haqile.net/weixin/handle/news-detail?id='.$val->id
            );
        }
        return $newsarr;
    }
    public function getmenu(){
        $result=WeixinMenu::find()->where(['status'=>0,'pid'=>0])->orderby('sort',SORT_ASC)->all();
        foreach($result as $key=>$val){
            $sonresult=WeixinMenu::find()->where(['status'=>0,'pid'=>$val->id])->orderby('sort',SORT_ASC)->all();
            if(!empty($sonresult)){
                $menu['button'][$key]['name']=$val->menu_name;
                foreach($sonresult as $k=>$v){
                    $menu['button'][$key]['sub_button'][$k]['type']=$v->menu_type;
                    $menu['button'][$key]['sub_button'][$k]['name']=$v->menu_name;
                    if($v->menu_type=='view'){
                        $menu['button'][$key]['sub_button'][$k]['url']=$v->url;
                    }else if($v->menu_type=='click'){
                        $keydata=WeixinKey::find()->where(['id'=>$v->key])->one();
                        $menu['button'][$key]['sub_button'][$k]['key']=$keydata->key;
                    }
                }
            }else{
                $menu['button'][$key]['type']=$val->menu_type;
                $menu['button'][$key]['name']=$val->menu_name;
                if($val->menu_type=='view'){
                    $menu['button'][$key]['url']=$val->url;
                }else if($val->menu_type=='click'){
                    $keydata=WeixinKey::find()->where(['id'=>$val->key])->one();
                    $menu['button'][$key]['key']=$keydata->key;
                }
                
            }
        }
        return $menu;
    }
};
?>