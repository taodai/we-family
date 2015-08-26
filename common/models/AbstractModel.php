<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%menu}}".
 *
 *
 */
class AbstractModel extends \yii\db\ActiveRecord
{

    public $_out = array();
    //加载FORM数据
    public function loadValue($values)
    {
        if (is_array($values)) {
            $attributes = $this->attributes();
            foreach ($values as $name => $value) {
                if (in_array($name,$attributes)) {
                    $this->$name = $value;
                }
            }
        }
    }

    //检查字段是否唯一
    public function checkUniq($id,$key,$value)
    {
        $primaryKey = $this->primaryKey()[0];
        if($key){
            if($id){
                $model = $this->find()->where([$key=>$value])->one();
                if($model->$primaryKey != $id){
                    return true;
                }
            }else{
                $model = $this->find()->where([$key=>$value])->one();
                if($model){
                    return true;
                }
            }
        }
        return false;
    }

    //格式化返回信息
    public function formatMsg($msg,$code = true)
    {
        if(!$code){
            $this->_out['errorMsg'] = $msg;
        }
        $this->_out['success'] = $code;
    }

}
?>