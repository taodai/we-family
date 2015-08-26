<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_category}}".
 *
 * @property integer $id
 * @property string $pid
 * @property string $category
 * @property string $rank
 * @property integer $module
 */
class AppCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'rank', 'module'], 'integer'],
            [['category'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'category' => 'Category',
            'rank' => 'Rank',
            'module' => 'Module',
        ];
    }

    public function getAllCate($module=2){
        return $this->find()->where(['module'=>$module])->asArray()->all();
    }
    /**
     * @param $id 获取子分类
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChild($id){
        return $this->find()->where(['pid'=>$id])->asArray()->all();
    }


}

