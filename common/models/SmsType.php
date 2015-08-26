<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sms_type}}".
 *
 * @property integer $st_id
 * @property string $st_name
 * @property integer $st_status
 */
class SmsType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['st_name'], 'required'],
            [['st_status'], 'integer'],
            [['st_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'st_id' => Yii::t('app', 'St ID'),
            'st_name' => Yii::t('app', '类型名称'),
            'st_status' => Yii::t('app', '状态1，正常；2，停用'),
        ];
    }
}
