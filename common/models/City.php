<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $city_id
 * @property string $city_code
 * @property string $city_name
 * @property string $province_code
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_code', 'province_code'], 'string', 'max' => 6],
            [['city_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => Yii::t('app', 'City ID'),
            'city_code' => Yii::t('app', 'City Code'),
            'city_name' => Yii::t('app', 'City Name'),
            'province_code' => Yii::t('app', 'Province Code'),
        ];
    }
}
