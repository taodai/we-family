<?php
namespace common\components;
use Yii;
use common\components\FileUtil;
/**
 * 搜索引擎封装
 * 
 */

class Search{

    /* 预设迅搜库文件路径*/
    const XSPATH    = "/sdk/php/lib/XS.php";
    /* 预设迅搜配置文件路径*/
    const XSINIPATH = "/sdk/php/app/";
    /* 配置文件后缀*/
    const SUFFIX    = ".ini";
    /* 实际迅搜库文件路径*/
    public $xsPath = '';
    /* 实际配置文件路径*/
    public $iniPath = '';
    /* 需加载配置文件名，PS：wiki*/
    public $iniFile;
    /* 搜索字符串*/
    public $queryStr;

    public function __construct($path = NULL)
    {
        $this->xsPath = empty($path) ? Yii::getAlias('@common')."/components/xunsearch" : $path;
        $this->iniPath = Yii::getAlias('@common')."/components/xunsearch".self::XSINIPATH;
        require($this->xsPath.self::XSPATH);
    }

    public function __set($key, $value) 
    {
        $this->$key = $value;
    }

    public function __get($key) 
    {
        return $this->$key;
    }

    public function createIni($name)
    {
        $fileUtil = new FileUtil();

        if($fileUtil->createFile($this->iniPath.$name.self::SUFFIX,true))
        {
            // code
        }
    }

    public function searchByParam()
    {
        try {
            $xs = new \XS($this->iniFile);    // demo  为项目名称，配置文件是：$sdk/app/demo.ini
            $search = $xs->search;
            $search->setLimit(20,0);
            // $a = $search->search('测试');
            $docs = $search->setQuery('饮食')->search();
            // foreach ($docs as $doc) {
            //     // $subject = $search->highlight($doc->subject); //  高亮处理标题
            //     echo $doc->rank() . '. ' . $doc->subject . ' [' . $doc->percent() . '%] - ' . date('Y-m-d') . "n";
            //     echo $doc->message . "nn";
            // }
            $arr = array();
            foreach ($docs as $key=>$doc) {
                foreach ($doc as $k => $v) {
                    $arr[$key][$k] = $v;
                    // echo $key."=>";
                    // print_r($value);
                    // echo "\n";
                }
            }
            // print_r($docs);
            // echo json_encode($arr);
            var_dump($arr);
        } catch (XSException $e) {
            echo $e . "n" . $e->getTraceAsString() . "n"; //  发生异常，输出描述
        }
    }
    
}
?>