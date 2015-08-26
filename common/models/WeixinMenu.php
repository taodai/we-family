<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%weixin_menu}}".
 *
 * @property integer $id
 * @property string $menu_name
 * @property string $menu_type
 * @property integer $pid
 * @property integer $status
 * @property string $key
 * @property string $url
 */
class WeixinMenu extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'status','sort','key'], 'integer'],
            [['url'], 'string'],
            [['menu_name'], 'string', 'max' => 40],
            [['menu_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'menu_name' => Yii::t('app', '菜单名称'),
            'menu_type' => Yii::t('app', '事件类型'),
            'pid' => Yii::t('app', '父类菜单id'),
            'status' => Yii::t('app', '菜单状态（1：删除，0：正常）'),
            'key' => Yii::t('app', '自定义菜单key'),
            'url' => Yii::t('app', '菜单跳转路径'),
            'sort' => Yii::t('app', '排序'),
        ];
    }
}
