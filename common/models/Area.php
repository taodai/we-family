<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%area}}".
 *
 * @property integer $area_id
 * @property string $area_code
 * @property string $area_name
 * @property string $city_code
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%area}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['area_code', 'city_code'], 'string', 'max' => 6],
            [['area_name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'area_id' => Yii::t('app', 'Area ID'),
            'area_code' => Yii::t('app', 'Area Code'),
            'area_name' => Yii::t('app', 'Area Name'),
            'city_code' => Yii::t('app', 'City Code'),
        ];
    }
}
