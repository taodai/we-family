<?php

namespace app\modules\system\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Menu;
use yii\helpers\Url;

class PermissionController extends Controller
{
    // public $layout = false;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        // var_dump(Yii::getAlias('@backViews'));exit;
        // return $this->renderFile('@backend/modules/system/views/menu/menu.php');
        return $this->render('@sysViews/menu');
    }

    public function actionList()
    {
        $tag = !empty(Yii::$app->request->get()) ? Yii::$app->request->get()['tag'] : 'sys';
        $model = new Menu();
        $result = $model->getAll($tag);
        $assResult = array();
        foreach ($result as $key => $value) {
            $assResult[$value['sm_parent_id']][] = $value;
        }
        $menus  = $this->_ass(0, $assResult);
        echo json_encode($menus);
    }

    public function actionParentMenu()
    {
        $menu = new Menu();
        $menu->sm_status = 1;
        $menu->sm_parent_id = 0;

        $response = $menu->getParents();
        $response = array(array('id'=>0,'text'=>'菜单列表','children'=>$response));
        // array_unshift($response,$arr);

        echo json_encode($response);
    }

    public function actionMenuAdd()
    {
        $menu = new Menu();
        $menu->loadValue( Yii::$app->request->post() );
        $menu->sm_time = date( 'Y-m-d H:i:s' );
        if( !empty( $menu->sm_menu_png ) ){
            $png = explode( '.' , $menu->sm_menu_png );
            $menu->sm_menu_icon = 'icon-'.$png[0];
        }
        if( $menu->save() ) {
            $out['success'] = true;
        }else{
            $out['success'] = false;
            $out['errorMsg'] = '数据有误，请检查';
        }
        echo json_encode($out);
    }

    public function actionMenuEdit()
    {
        $get = Yii::$app->request->get();
        $sm_id = $get['id'];
        $menu = Menu::findOne($sm_id);
        if ($menu === null) {
            $out['success'] = false;
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $menu->loadValue(Yii::$app->request->post());
            if( !empty($menu->sm_menu_png) ){
                $png = explode('.',$menu->sm_menu_png);
                $menu->sm_menu_icon = 'icon-'.$png[0];
            }
            if ($menu->save()) {
                $out['success'] = true;
            }
        }
        echo json_encode($out);
    }

    public function actionMenuDel()
    {
        $post = Yii::$app->request->post();
        $sm_id = $post['id'];
        $menu = Menu::findOne($sm_id);
        if ( $menu == null ) {
            $out['errorMsg'] = '请选择一行或该行不存在';
        }else{
            $menu->sm_status = 2;
            if ( $menu->save() ) {
                $out['success'] = true;
            }else{
                $out['success'] = false;
                $out['errorMsg'] = '数据保存失败，请检查';
            }
        }
        echo json_encode($out);
    }

    public function actionPngList()
    {
        $imgPath = '/theme/easyui131/themes/icons/';
        $path = Yii::getAlias('@backend').'/web'.$imgPath;

        $map = $this->directory_map($path, 0, TRUE);
        $arr = array();
        foreach ($map as $key => $value) {
            if(!is_array($value)){
            $arr[$key]['name'] = $value;
            $arr[$key]['image'] = Url::base().$imgPath.$value;
            }
        }
        echo json_encode($arr);
        // var_dump($arr);
    }

    private function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir))
        {
            $filedata   = array();
            $new_depth  = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
                {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
                {
                    $filedata[$file] = $this->directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
                }
                else
                {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return false;
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

    private function arr2tree($tree, $rootId = 0) {  
        $return = array();  
        foreach($tree as $leaf) {  
            if($leaf['sm_parent_id'] == $rootId) {  
                foreach($tree as $subleaf) {  
                    if($subleaf['sm_parent_id'] == $leaf['sm_id']) {  
                        $leaf['children'] = $this->arr2tree($tree, $leaf['sm_id']);  
                        break;  
                    }  
                }  
                $return[] = $leaf;  
            }  
        }  
        return $return;  
    }
}
