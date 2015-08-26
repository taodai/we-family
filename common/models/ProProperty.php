<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pro_property}}".
 *
 * @property integer $pp_id
 * @property integer $pp_pro_id
 * @property string $pp_name
 * @property string $pp_value
 */
class ProProperty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pro_property}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pp_pro_id', 'pp_name', 'pp_value'], 'required'],
            [['pp_pro_id'], 'integer'],
            [['pp_value'], 'string'],
            [['pp_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pp_id' => 'Pp ID',
            'pp_pro_id' => 'Pp Pro ID',
            'pp_name' => 'Pp Name',
            'pp_value' => 'Pp Value',
        ];
    }
}
