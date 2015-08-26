<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
// use common\models\LoginForm;
use yii\filters\VerbFilter;
use backend\models\Manager;
use backend\models\Menu;
use backend\models\SysModule;
use linslin\yii2\curl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    public $session = false;

    public function behaviors()
    {
        return [
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'only' => ['logout','menu'],
            //     'rules' => [
            //         [
            //             'actions' => ['login', 'error','doLogin'],
            //             'allow' => true,
            //         ],
            //         [
            //             'actions' => ['logout','menu'],
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }


    public function init()
    {
        $this->session = Yii::$app->session;
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if(isset($this->session->get('manager')->mid)){
            $this->redirect('site/main');
        }
        return $this->render('login');
    }

    public function actionTest()
    {
        $curl = new curl\Curl();

        //get http://example.com/
        $response = $curl->get(
            'http://api.hql.com/index.php/memberApi/member/get-one?id=1'
        );
        var_dump($response);
    }

    public function actionLogin()
    {
        $params = Yii::$app->request->post();
        if($params){
            $model = new Manager();
            $manager = $model->login($params);
            if($manager){
                $sessionData = [
                    'mid' => $manager->mid,
                    'realName' => $manager->real_name
                ];
                $this->session->set('manager',(object)$sessionData);
                $out = [
                    'suc'=>true,
                ];
            }else{
                $out = [
                    'suc'=>false,
                    'errorMsg'=>$model->_msg
                ];
            }
            echo json_encode($out);
        }else{
            return $this->goHome();
        }
    }

    public function actionMain()
    {
        $this->layout = 'system';
        $module = new SysModule();
        $mod = $module->getAll();
        if (!isset(Yii::$app->session->get('manager')->mid)) {
            return $this->goHome();
        }
        return $this->render('index',['mod'=>$mod]);
    }

    public function actionEditPassword()
    {
        
    }

    public function actionLogout()
    {
        // $session = Yii::$app->session;
        $this->session->destroy();
        return $this->goHome();
    }

    public function actionMenu()
    {
        $model = new Menu();
        $params = Yii::$app->request->post();
        $result = $model->getByTag($params['type']);
        $assResult = array();
        foreach ($result as $key => $value) {
            $assResult[$value['sm_parent_id']][] = $value;
        }
        $menus  = $this->_ass(0, $assResult);
        echo json_encode($menus);
    }

    private function _ass($index, $ass){
        if (!array_key_exists($index, $ass)) {
            return array();
        }
        $_ass = $ass[$index];
        foreach ($_ass as $i => $val) {
            $children = $this->_ass($val['sm_id'], $ass);
            if (!empty($children)) {
                $_ass[$i]['children'] = $children;
            }
        }
        return $_ass;
    }
}
