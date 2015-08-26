<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use common\models\AbstractModel;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $sm_id
 * @property integer $sm_parent_id
 * @property string $sm_menu_title
 * @property string $sm_menu_icon
 * @property string $sm_menu_png
 * @property string $sm_menu_url
 * @property integer $sm_weight
 * @property integer $sm_status
 * @property string $sm_time
 */
// class Menu extends \yii\db\ActiveRecord
class Menu extends AbstractModel
{
    const ACTIVE_MENU = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sm_parent_id', 'sm_menu_title', 'sm_time'], 'required'],
            [['sm_parent_id', 'sm_weight', 'sm_status'], 'integer'],
            [['sm_time'], 'safe'],
            [['sm_menu_title', 'sm_menu_icon'], 'string', 'max' => 50],
            [['sm_menu_png'], 'string', 'max' => 100],
            [['sm_menu_url'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sm_id' => Yii::t('app', 'Sm ID'),
            'sm_parent_id' => Yii::t('app', '上级菜单ID'),
            'sm_menu_title' => Yii::t('app', '菜单名称'),
            'sm_menu_icon' => Yii::t('app', '菜单图标样式'),
            'sm_menu_png' => Yii::t('app', '图标名称'),
            'sm_menu_url' => Yii::t('app', '菜单地址'),
            'sm_weight' => Yii::t('app', '显示顺序'),
            'sm_status' => Yii::t('app', '菜单状态（1，启用；2，停用）'),
            'sm_time' => Yii::t('app', 'Sm Time'),
        ];
    }

    public function getByTag($sm_tag)
    {
        // $menu = $this->find()->where(['sm_tag'=>$sm_tag])->orderBy('sm_weight')->asArray()->all();
        $menu = $this->find()->where(['sm_tag'=>$sm_tag,'sm_status'=>self::ACTIVE_MENU])->orderBy('sm_weight')->asArray()->all();
        return $menu;
    }

    public function getAll($sm_tag)
    {
        $menu = $this->find()->where(['sm_tag'=>$sm_tag])->orderBy('sm_weight')->asArray()->all();
        // $menu = $this->find()->where(['sm_status'=>self::ACTIVE_MENU])->orderBy('sm_weight')->asArray()->all();
        // $menu = $this->find()->where(['sm_status'=>self::ACTIVE_MENU,'sm_tag'=>$sm_tag])->orderBy('sm_weight')->asArray()->all();
        return $menu;
    }

    public function getParents()
    {
        $menu = (new Query())
                ->select(['sm_id as id','sm_menu_title as text','sm_parent_id'])
                ->from($this->tableName())
                ->where(['sm_status'=>$this->sm_status,'sm_parent_id'=>$this->sm_parent_id])
                ->orderBy('sm_weight asc')
                ->all();
        return $menu;
    }
}