<?php

namespace common\models;


use Yii;

/**
 * This is the model class for table "{{%course_review}}".
 *
 * @property string $id
 * @property string $cid
 * @property string $content
 */
class CourseReview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course_review}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cnid'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cnid' => 'Cnid',
            'content' => 'Content',
        ];
    }

    public function getCourseNotice(){

        return $this->belongTo(CourseReview::className(),['cnid'=>'cnid']);
    }
}
