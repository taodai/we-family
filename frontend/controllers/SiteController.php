<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
// use common\library\wechat;
use yii\helpers\Url;
use common\models\User;
use common\models\SmsInfo;
use common\components\Sms;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    public $session = null;
    /**
     * @inheritdoc
     */
    private $wechat;
    
    public function init(){
        $configs = array(
            'token'  => 'haqile',
            'appid'  => 'wx771185e82a1c60af',
            'secret'  => 'cf3cab2d3f485f811c3d6079c5a7d4a6',
            'mch_id'=>'1239208502',
            'payKey'=>'c4a5e8b9b36bae0da7b46aa6e6042a71'
        );
        $this->session = Yii::$app->session;
        // $this->wechat = new wechat($configs);
        //$this->wechat->validate(); //验证URL，验证完注释 
        // $this->wechat->access_token = $this->wechat->getToken();
        
    }
    public function actionIndex(){
        //跳到授权页面
        // $callback='http://'.$_SERVER['HTTP_HOST'].url::toRoute('site/pay');
        // $url = $this->wechat->getOAuthRedirect($callback,$state='', $scope='snsapi_userinfo');
        // $this->redirect($url);
        return $this->render('index');
    }
    
    public function actionPay()
    {
    
        $arr = $this->wechat->getOauthAccessToken();
        $userinfo = $this->wechat->getOauthUserInfo($arr['access_token'], $arr['openid']);//获取微信用户信息
        $orderId = date("YmdHis");
        $body='会员支付费用';
        $notify_url='http://'.$_SERVER['HTTP_HOST'].Url::toRoute('site/notify');
        $openid=$arr['openid'];
        $jsApiParameters =  $this->wechat->unifiedOrder($openid, $body, $orderId, 0.1,$notify_url);
        echo $jsApiParameters;
        return $this->renderPartial('pay',['jsApiParameters'=>$jsApiParameters]);
    }
    public function actionNotify(){
        //接受微信支付结果通知，返回数组,http://mch.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_7
        $arr = $this->wechat->getNotify();
        //处理
    }
    //调用查询接口http://mch.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_2
    public function actionOrderInfo(){
        //$type=1 传入$oprderId 微信订单号;0:商户订单号
        $orderId = '';
        $this->wechat->getOrderInfo($orderId,$type=0);
    }
    
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionChoose()
    {
        $params = Yii::$app->request->get();
        if(isset($params['type'])){
            $this->session->set('type',$params['type']);
            $this->redirect(['site/signup']);
        }else{
            return $this->render('choose');
        }
    }

    public function actionSignup()
    {
        $params = Yii::$app->request->post();
        if($params){
            $model = new User();
            if ($user = $model->signup(Yii::$app->request->post())) {
                $arr = ['info'=>'','status'=>'n'];
            }
        }else{
            return $this->render('signup');
        }

    }

    public function actionCheck()
    {
        $params = Yii::$app->request->post();
        $bool = $this->_checkUname($params['param']);
        if($bool){
            $arr = ['info'=>'手机号码已存在','status'=>'n'];
        }else{
            $arr = ['status'=>'y'];
        }
        echo json_encode($arr);
    }

    private function _checkUname($uname)
    {
        $model = new User();
        return $model->checkUname($uname);
    }

    public function actionTest()
    {
        $model = new SmsInfo();
        $model->smsSave('13858044009','123456','123456');
        echo 'suc';
    }


    public function actionSmsSend()
    {
        $session = Yii::$app->session;
        $code = mt_rand(100000,999999);
        $session->set('codeNo',$code);
    }

    public function actionTestL()
    {
        $params = Yii::$app->request->post();
        $sms = new Sms();
        $model = new SmsInfo();
        $code = mt_rand(111111,999999);
        $sms->smsTpl = $code;
        $sms->mobile = $params['uname'];
        $model->smsSave($params['uname'],$code,$sms->smsTpl);
        $return = $sms->regSms();

    }

    private function _randStr($length)
    {
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionError()
    {
        echo "yes";exit;
    }
}
