<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;

/**
 * This is the model class for table "{{%pro_tags}}".
 *
 * @property integer $pt_id
 * @property string $pt_name
 * @property integer $pt_status
 * @property integer $pt_time
 */
class ProTags extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pro_tags}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pt_name', 'pt_status', 'pt_time'], 'required'],
            [['pt_status', 'pt_time'], 'integer'],
            [['pt_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pt_id' => Yii::t('app', '标签ID'),
            'pt_name' => Yii::t('app', '标签名称'),
            'pt_status' => Yii::t('app', 'Pt Status'),
            'pt_time' => Yii::t('app', 'Pt Time'),
        ];
    }
}
