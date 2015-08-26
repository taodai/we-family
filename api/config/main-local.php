<?php
use yii\web\Response;
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - thi s is required by cookie validation
            'cookieValidationKey' => 'mOfe242T8Wpt8HBUzb8t4H4dQ-tn-pP2',
            // 'enableCsrfValidation' => false,
        ],
    ],
    'on beforeRequest' => function ($event) {
        $pathInfo       = Yii::$app->request->getPathInfo();
        $pathInfoArr    = explode('/', $pathInfo);
        $num            = count($pathInfoArr);
        $action         = $pathInfoArr[$num-1];
        unset($pathInfoArr[$num-1]);
        $route          = implode('/', $pathInfoArr);
        $control        = Yii::$app->createController($route); 
        $methodName     = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $action))));
        // var_dump($methodName);exit;
        $methodsArr = get_class_methods($control[0]);
        // var_dump($methodsArr);exit;
        if(!in_array($methodName, $methodsArr)){
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = array(
                'code'  => 0,
                'status'=> 404,
                'msg'   => '非法请求'
            );
            echo json_encode(Yii::$app->response->data);exit;
        }
        // var_dump($control[0]->module->getInstance()->beforeRequest());exit;
        // echo $url;exit;
        // var_dump(new ActiveController());exit;
        // var_dump($event);exit;
            // var_dump(Yii::$app->request->action);exit;
            // $l_saved = null;
            // if (true){
            //     # use cookie to store language
            //     $l_saved = Yii::$app->request->cookies->get('locale');
            // }else{
            //     # use session to store language
            //     $l_saved = Yii::$app->session['locale'];
            // }
            // $l = ($l_saved)?$l_saved:'en-US';

            // Yii::$app->sourceLanguage = 'en';
            // Yii::$app->language = $l;
            return; 
        },  
];
