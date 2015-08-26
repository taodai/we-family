<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%weixin_input}}".
 *
 * @property integer $id
 * @property string $input
 * @property integer $type
 * @property integer $return_id
 */
class WeixinInput extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_input}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'return_id'], 'integer'],
            [['input'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'input' => Yii::t('app', '用户输入信息'),
            'type' => Yii::t('app', '回复类型（1：消息，2：图文）'),
            'return_id' => Yii::t('app', '回复对应的表id'),
        ];
    }
}
