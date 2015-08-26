<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%weixin_key}}".
 *
 * @property integer $id
 * @property string $key
 * @property integer $type
 * @property integer $return_id
 */
class WeixinKey extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_key}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'return_id'], 'integer'],
            [['key'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'key值'),
            'type' => Yii::t('app', '回复类型（1：消息，2：图文）'),
            'return_id' => Yii::t('app', '回复对应的表id'),
        ];
    }
}
