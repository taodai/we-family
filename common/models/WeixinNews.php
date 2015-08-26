<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%weixin_news}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $description
 * @property string $picName
 * @property string $picUrl
 * @property integer $status
 */
class WeixinNews extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'description'], 'string'],
            [['status','addtime'], 'integer'],
            [['title', 'picName'], 'string', 'max' => 100],
            [['picUrl'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '图文标题'),
            'content' => Yii::t('app', '正文内容'),
            'description' => Yii::t('app', '图文描述'),
            'picName' => Yii::t('app', '图片名称'),
            'picUrl' => Yii::t('app', '图片路径'),
            'addtime' => Yii::t('app', '添加时间'),
            'status' => Yii::t('app', '状态（1：已删除，0：未删除）'),
        ];
    }
}
