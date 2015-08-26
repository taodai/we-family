<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pro_category}}".
 *
 * @property integer $pc_id
 * @property string $pc_name
 * @property integer $pc_fid
 * @property integer $pc_status
 */
class ProCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pro_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pc_name', 'pc_fid'], 'required'],
            [['pc_fid', 'pc_status'], 'integer'],
            [['pc_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pc_id' => 'Pc ID',
            'pc_name' => 'Pc Name',
            'pc_fid' => 'Pc Fid',
            'pc_status' => 'Pc Status',
        ];
    }

    public function getAllCate(){
        $result = $this->find()->select(['pc_id as id','pc_name as text','pc_fid as pid'])->where(['pc_status'=>1])->orderBy(['pc_id'=>SORT_DESC])->asArray()->all();
        return $result;
    }

    //格式化分类树
    public function node_merge($array,$pid = 0){
        $arr = array();
        foreach ($array as $value){
            if($value['pc_fid'] == $pid){
                $value['children'] = $this::node_merge($array,$value['pc_id']);
                $arr[] = $value;
            }
        }
        return $arr;
    }

    /**
     * @param $id 获取子分类
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChild($id){
        return $this->find()->where(['pid'=>$id])->asArray()->all();
    }
}
