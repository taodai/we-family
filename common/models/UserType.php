<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;

/**
 * This is the model class for table "{{%user_type}}".
 *
 * @property integer $ut_id
 * @property string $ut_name
 * @property integer $ut_weight
 * @property integer $ut_status
 * @property integer $ut_createtime
 */
class UserType extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ut_name', 'ut_status', 'ut_createtime'], 'required'],
            [['ut_weight', 'ut_status', 'ut_createtime'], 'integer'],
            [['ut_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ut_id' => Yii::t('app', 'Ut ID'),
            'ut_name' => Yii::t('app', 'Ut Name'),
            'ut_weight' => Yii::t('app', '排序'),
            'ut_status' => Yii::t('app', '状态1正常2停用'),
            'ut_createtime' => Yii::t('app', '创建时间'),
        ];
    }
}
