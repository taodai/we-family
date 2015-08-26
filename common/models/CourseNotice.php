<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%course-notice}}".
 *
 * @property integer $cnid
 * @property string $theme
 * @property string $theme_pic
 * @property integer $share_lecturer
 * @property string $share_desc
 * @property integer $share_time
 * @property string $share_qrcode
 * @property integer $status
 * @property integer $createtime
 */
class CourseNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course_notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme','share_lecturer'],'required'],
            [['share_lecturer', 'share_time', 'status', 'createtime'], 'integer'],
            [['share_desc'], 'string'],
            [['share_content'], 'string'],
            [['real_name'], 'string'],
            [['theme_pic','share_qrcode'], 'string', 'max' => 100],
            [['theme'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cnid' => 'Cnid',
            'theme' => 'Theme',
            'theme_pic' => 'Theme Pic',
            'share_lecturer' => 'Share Lecturer',
            'share_desc' => 'Share Desc',
            'share_time' => 'Share Time',
            'share_qrcode' => 'Share Qrcode',
            'status' => 'Status',
            'createtime' => 'Createtime',
        ];
    }

    public function getLecturer(){
        return $this->hasOne(Lecturer::className(),['leid'=>'share_lecturer']);
    }
    public function getCourseReview(){
        return $this->hasOne(CourseReview::className(),['cnid'=>'cnid']);
    }
}
