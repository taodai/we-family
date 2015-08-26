<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_favorite}}".
 *
 * @property integer $id
 * @property integer $userid
 * @property string $username
 * @property string $favorite_type
 * @property integer $favorite_id
 * @property integer $addtime
 * @property integer $status
 */
class AppFavorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_favorite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid', 'favorite_id', 'addtime', 'status'], 'integer'],
            [['username'], 'string', 'max' => 100],
            [['favorite_type'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userid' => Yii::t('app', '用户id'),
            'username' => Yii::t('app', '用户名'),
            'favorite_type' => Yii::t('app', '收藏类型（百科：wiki；产品：mall；）'),
            'favorite_id' => Yii::t('app', '收藏品的表id'),
            'addtime' => Yii::t('app', '收藏时间'),
            'status' => Yii::t('app', '收藏状态（0：已收藏，1：未收藏）'),
        ];
    }
}
