<?php

namespace common\models;

use Yii;
use backend\models\Manager;
use common\models\AbstractModel;

/**
 * This is the model class for table "{{%group_type}}".
 *
 * @property integer $gt_id
 * @property string $gt_name
 * @property integer $gt_time
 * @property integer $gt_status
 * @property integer $gt_creater
 */
class GroupType extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gt_name', 'gt_time', 'gt_creater'], 'required'],
            [['gt_time', 'gt_status', 'gt_creater'], 'integer'],
            [['gt_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gt_id' => Yii::t('app', 'Gt ID'),
            'gt_name' => Yii::t('app', '圈子类型名称'),
            'gt_time' => Yii::t('app', '创建时间'),
            'gt_status' => Yii::t('app', '1,正常显示2,禁止显示'),
            'gt_creater' => Yii::t('app', '添加人员'),
        ];
    }

    public function getManager()
    {
        return $this->hasOne(Manager::className(),['mid'=>'gt_creater']);
    }
}
