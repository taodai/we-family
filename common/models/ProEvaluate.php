<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pro_evaluate}}".
 *
 * @property integer $id
 * @property integer $pro_id
 * @property integer $uid
 * @property string $desc
 * @property integer $add_time
 */
class ProEvaluate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pro_evaluate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pro_id', 'uid', 'desc', 'add_time'], 'required'],
            [['pro_id', 'uid', 'add_time'], 'integer'],
            [['desc'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'pro_id' => Yii::t('app', '商品ID'),
            'uid' => Yii::t('app', '用户ID'),
            'desc' => Yii::t('app', '商品评价详情'),
            'add_time' => Yii::t('app', '添加时间'),
        ];
    }

    public function addEvaluate($params)
    {
        if(is_array($params)){
            foreach($params as $param)
            {
                if($param){
                    $_model = clone $this;
                    $_model->pro_id = $param->pro_id;
                    $_model->uid = $param->uid;
                    $_model->desc = $param->desc;
                    $_model->add_time = time();
                    $_model->save();
                }
            }
            return true;
        }
    }
}
