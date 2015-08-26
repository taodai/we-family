<?php

namespace common\models;

use Yii;
use common\models\User;
use common\models\AppUserinfo;
/**
 * This is the model class for table "{{%agency}}".
 *
 * @property integer $agency_id
 * @property integer $parent_id
 * @property integer $child_id
 * @property integer $level
 */
class Agency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'child_id', 'level'], 'integer'],
            [['child_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agency_id' => Yii::t('app', 'Agency ID'),
            'parent_id' => Yii::t('app', '上级代理商ID，对应会员ID'),
            'child_id' => Yii::t('app', '下级代理商ID，对应会员表ID'),
            'level' => Yii::t('app', '级别(1,一级代理；2,二级代理；3,三级代理)'),
        ];
    }

    public function getAgencyByParentId($uid,$level)
    {
        if($uid && $level){
            $response = Agency::find()
                    ->select(['agency_id','parent_id','child_id','uname','real_name','level'])
                    ->joinWith('user')
                    ->where(['parent_id'=>$uid,'level'=>$level])
                    ->asArray()
                    ->all();
            return $response;
        }
    }

    public function getAgencyAll($uid)
    {
        if($uid){
            $response = Agency::find()
                    ->select(['agency_id','parent_id','child_id','uname','real_name','level'])
                    ->joinWith('user')
                    ->where(['parent_id'=>$uid])
                    ->asArray()
                    ->all();
            $response = $this->_unsetKey($response,['password','auth_key']);
            return $response;
        }
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['uid'=>'child_id']);
    }

    public function getInfo()
    {
        return $this->hasOne(AppUserinfo::className(),['uid'=>'child_id']);
    }

    public function findAgencyUpByLevel($uid,$level)
    {
        return $this::find()->where(['child_id'=>$uid,'level'=>$level])->asArray()->one();
    }

    //返回数组
    public function findAgencyById($uid)
    {
        $response = $this::find()->where(['parent_id'=>$uid])->asArray()->all();
        $firstAgencyCount = 0;
        $secondAgencyCount = 0;
        if($response){
            foreach ($response as $key => $value) {
                if(is_array($value)){
                    $value['level'] == 2 ? $firstAgencyCount++ : $secondAgencyCount++;
                }
            }
        }
        if($firstAgencyCount || $secondAgencyCount){
            return [
                'first'=>$firstAgencyCount,
                'second'=>$secondAgencyCount
                ];
        }else{
            return false;
        }
    }

    public function findAgencyUpAll($uid)
    {
        return $this::find()->where(['child_id'=>$uid])->asArray()->all();
    }

    public function addAgency($parent_id,$child_id)
    {
        //判断是否存在代理关系
        $isEx = $this->findAgencyUpAll($child_id);
        if($isEx){
            return 1;
        }else{
            //查询$parent_id代理关系
            $agencyParent = $this->findAgencyUpAll($parent_id);
            if($agencyParent){
                if(is_array($agencyParent)){
                    //增加二级代理关系
                    $this->_add($parent_id,$child_id,2);
                    foreach ($agencyParent as $agency) {
                        if($agency['level'] == 2){
                            $this->_add($agency['parent_id'],$child_id,3);
                        }
                    }
                    return 2;
                }
            }else{
                $this->_add($parent_id,$child_id,2);
                return 2;
            }
        }
    }

    //查找是否有上级，根据级别：如果是2级返回上级ID，无返回FALSE;
    public function findByChild($uid)
    {
        $response = $this->find()->where(['child_id'=>$uid])->one();
        if($response){
            return ($response->level == 2) ? $response->parent_id : false;
        }
        return false;
    }

    private function _add($parent_id,$child_id,$level)
    {
        $model = new Agency();
        $model->parent_id = $parent_id;
        $model->child_id = $child_id;
        $model->level = $level;
        return $model->save();
    }


    public function _unsetKey($arr,$key)
    {
        if(is_array($arr) && is_array($key))
        {
            foreach ($arr as $k=> $value) {
                    foreach ($value['user'] as $j => $v) {
                        if(in_array($j, $key)){
                            unset($arr[$k]['user'][$j]);
                        }
                    }
            }
        }
        return $arr;
    }
}
