<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_status}}".
 *
 * @property integer $id
 * @property integer $status_val
 * @property string $status_name
 * @property string $status_return
 */
class OrderStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_val', 'status_name', 'status_return'], 'required'],
            [['status_val'], 'integer'],
            [['status_name', 'status_return'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status_val' => Yii::t('app', '状态值'),
            'status_name' => Yii::t('app', '状态名称'),
            'status_return' => Yii::t('app', '状态提示值'),
        ];
    }
}
