<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pro_brand}}".
 *
 * @property string $pb_id
 * @property string $pb_name
 * @property integer $pb_status
 * @property integer $pb_time
 */
class ProBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pro_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pb_status', 'pb_time'], 'integer'],
            [['pb_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pb_id' => 'Pb ID',
            'pb_name' => 'Pb Name',
            'pb_status' => 'Pb Status',
            'pb_time' => 'Pb Time',
        ];
    }
}
