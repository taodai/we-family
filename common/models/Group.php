<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property integer $group_id
 * @property string $group_name
 * @property integer $group_type
 * @property string $group_icon_path
 * @property string $group_desc
 * @property integer $group_creater_user
 * @property integer $group_creater_manager
 * @property integer $group_time
 * @property integer $group_tag
 * @property integer $is_public
 * @property integer $is_recommend
 * @property integer $group_status
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name', 'group_icon_path', 'group_time'], 'required'],
            [['group_type', 'group_creater_user', 'group_creater_manager', 'group_time', 'group_tag', 'is_public', 'is_recommend', 'group_status'], 'integer'],
            [['group_name', 'group_icon_path'], 'string', 'max' => 50],
            [['group_desc'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => Yii::t('app', 'Group ID'),
            'group_name' => Yii::t('app', '圈子名称'),
            'group_type' => Yii::t('app', '圈子类型'),
            'group_icon_path' => Yii::t('app', '圈子图标路径'),
            'group_desc' => Yii::t('app', '圈子介绍'),
            'group_creater_user' => Yii::t('app', '圈子创建人员（关联用户ID）'),
            'group_creater_manager' => Yii::t('app', '圈子创建人员（关联管理员ID）'),
            'group_time' => Yii::t('app', '添加时间'),
            'group_tag' => Yii::t('app', '圈子标签'),
            'is_public' => Yii::t('app', '是否公有：1，公有；2，私有；'),
            'is_recommend' => Yii::t('app', '是否推荐:1，不推荐；2，推荐'),
            'group_status' => Yii::t('app', '状态：1，正常；2，关闭；'),
        ];
    }
}
