<?php
namespace common\library;
use yii;

class tool{

    /**
     * @param $array
     * @param int $pid 上级分类
     * @return array
     */
    public static function node_merge($array,$pid = 0){
        $arr = array();
        foreach ($array as $value){
            if($value['pid'] == $pid){
                $value['children'] = self::node_merge($array,$value['id']);
                $arr[] = $value;
            }
        }
        return $arr;
    }
    //获取子类
    public static function getChild($array,$id){
        $child = array();
        foreach($array as $value){
            if($value['pid'] == $id){
                $child[]=$value['id'];
                $child = array_merge($child,self::getChild($array,$value['id']));
            }
        }
        return $child;
    }
}