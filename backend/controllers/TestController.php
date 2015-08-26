<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\components\Search;
use common\models\UserPayment;
/**
 * Site controller
 */
class TestController extends Controller
{
    // public $xsPath = "/components/xunsearch/sdk/php/lib/XS.php";

    public function init()
    {
        // require(Yii::getAlias('@common').$this->xsPath);
    }

    public function actionTest()
    {
        $search = new Search();
        $search->searchByParam();
        // $search->createIni('test.ini',true);
        exit;
        //$search->createIni('test.ini');exit;
        // echo Yii::getAlias('@common').$this->xsPath;exit;
        try {
           $xs = new XS('wiki');    // demo  为项目名称，配置文件是：$sdk/app/demo.ini
           // $index = $xs->index;
           // $doc = new \XSDocument(array(  //  创建 XSDocument  
           //        'p_id' => 123,  //  主键字段，必须指定
           //        'subject' => ' 测试文档标题', 
           //        'message' => ' 测试文档内容',
           //        'chrono' => time(),
           //        'abc' => time()
           //    ));
           //    // $index->add($doc);  //  添加文档，不检测便索引库内是否已有同一主键数据
           //    $index->update($doc); //  更新文档，若有同主键数据则替换之
            $search = $xs->search;
            $search->setLimit(20,0);
            // $a = $search->search('测试');
            $docs = $search->setQuery('饮食')->search();
            // foreach ($docs as $doc) {
            //     // $subject = $search->highlight($doc->subject); //  高亮处理标题
            //     echo $doc->rank() . '. ' . $doc->subject . ' [' . $doc->percent() . '%] - ' . date('Y-m-d') . "n";
            //     echo $doc->message . "nn";
            // }
            var_dump($docs);
           // var_dump($xs);
            // ... 此外为其它 XSIndex/XSSearch  的相关功能代码
        } catch (XSException $e) {
            echo $e . "n" . $e->getTraceAsString() . "n"; //  发生异常，输出描述
        }
    }

    public function actionMoney()
    {
        $model = UserPayment::find()->where(['money'=>'48.00'])->asArray()->all();
        if($model){
            foreach ($model as $key => $value) {
                echo date('Y-m-d',$value['pay_time']),"<br>";
            }
        }
    }
}
?>