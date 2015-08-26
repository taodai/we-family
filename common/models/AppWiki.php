<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%app_wiki}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $addtime
 * @property string $tag
 * @property integer $status
 * @property integer $category
 * @property integer $month
 * @property string $picUrl
 * @property string $videoUrl
 * @property integer $recommend
 * @property string $issue
 */
class AppWiki extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_wiki}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['addtime', 'status', 'category',  'recommend'], 'integer'],
            [['title'], 'string', 'max' => 100,'message'=>'标题最多100个字符'],
            [['tag'], 'string', 'max' => 100,'message'=>'标签最多100个字符'],
            [['picUrl', 'videoUrl'], 'string', 'max' => 255],
            [['issue'], 'string', 'max' => 30],
            [['info'], 'string','max' => 120, 'message'=>'概述最多120个字符'],
            [['month'], 'string', 'max' => 255],
            [['real_name'], 'string'],
            [['pregnancy','pic'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '标题'),
            'content' => Yii::t('app', '内容'),
            'addtime' => Yii::t('app', '添加时间'),
            'tag' => Yii::t('app', '标签'),
            'status' => Yii::t('app', '状态（0：正常，1：删除）'),
            'category' => Yii::t('app', '百科类型'),
            'pregnancy' => Yii::t('app', '月份推荐关联'),
            'month' => Yii::t('app', '月份推荐关联'),
            'picUrl' => Yii::t('app', '图片路径'),
            'videoUrl' => Yii::t('app', '视频路径'),
            'recommend' => Yii::t('app', '是否推荐（0：否，1：是）'),
            'issue' => Yii::t('app', '课堂期数'),
        ];
    }

    public function getCate(){
        return $this->hasOne(AppCategory::className(),['id'=>'category']);
    }
}
