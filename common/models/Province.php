<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%province}}".
 *
 * @property integer $province_id
 * @property string $province_code
 * @property string $province_name
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%province}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_code'], 'string', 'max' => 6],
            [['province_name'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'province_id' => Yii::t('app', 'Province ID'),
            'province_code' => Yii::t('app', 'Province Code'),
            'province_name' => Yii::t('app', 'Province Name'),
        ];
    }
}
