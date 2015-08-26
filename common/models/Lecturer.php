<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lecturer}}".
 *
 * @property integer $leid
 * @property string $le_name
 * @property string $le_pic
 * @property string $le_desc
 * @property string $le_title
 * @property integer $le_status
 * @property integer $le_weight
 * @property integer $le_time
 */
class Lecturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lecturer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['le_name', 'le_pic', 'le_status', 'le_weight', 'le_time'], 'required'],
            [['le_desc'], 'string'],
            [['le_area'], 'string',],
            [['le_status', 'le_weight', 'le_time'], 'integer'],
            [['le_name'], 'string', 'max' => 20],
            [['le_pic'], 'string', 'max' => 200],
            [['le_title'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'leid' => 'Leid',
            'le_name' => 'Le Name',
            'le_pic' => 'Le Pic',
            'le_desc' => 'Le Desc',
            'le_title' => 'Le Title',
            'le_status' => 'Le Status',
            'le_weight' => 'Le Weight',
            'le_time' => 'Le Time',
        ];
    }
}
