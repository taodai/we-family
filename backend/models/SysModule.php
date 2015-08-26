<?php

namespace backend\models;

use Yii;
use common\models\AbstractModel;

/**
 * This is the model class for table "{{%module}}".
 *
 * @property integer $moid
 * @property string $mo_title
 * @property string $mo_tag
 * @property integer $mo_weight
 * @property integer $mo_status
 * @property integer $mo_time
 */
class SysModule extends AbstractModel
{

    const ACTIVE_MODULE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mo_title', 'mo_tag', 'mo_time'], 'required'],
            [['mo_weight', 'mo_status', 'mo_time'], 'integer'],
            [['mo_title', 'mo_tag'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'moid' => Yii::t('app', 'Moid'),
            'mo_title' => Yii::t('app', '模块名称'),
            'mo_tag' => Yii::t('app', '模块标签'),
            'mo_weight' => Yii::t('app', '权重-排序'),
            'mo_status' => Yii::t('app', '状态1开启；2关闭'),
            'mo_time' => Yii::t('app', '创建时间'),
        ];
    }

    public function getAll()
    {
        return $this->find()->where(['mo_status'=>self::ACTIVE_MODULE])->orderBy('mo_weight')->asArray()->all();
    }
}
