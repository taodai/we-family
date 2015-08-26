<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%app_warmtips}}".
 *
 * @property integer $id
 * @property string $day
 * @property string $tips
 * @property string $boy_height
 * @property string $boy_weight
 * @property string $boy_circumference
 * @property string $girl_height
 * @property string $girl_weight
 * @property string $girl_circumference
 */
class AppWarmtips extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_warmtips}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tips'], 'string'],
            [['boy_height', 'boy_weight', 'boy_circumference', 'girl_height', 'girl_weight', 'girl_circumference'], 'string', 'max' => 20],
            [['id', 'day'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'day' => Yii::t('app', '天数'),
            'tips' => Yii::t('app', '每天提醒'),
            'boy_height' => Yii::t('app', '男孩身高'),
            'boy_weight' => Yii::t('app', '男孩体重'),
            'boy_circumference' => Yii::t('app', '男孩头围'),
            'girl_height' => Yii::t('app', '女孩身高'),
            'girl_weight' => Yii::t('app', '女孩体重'),
            'girl_circumference' => Yii::t('app', '女孩头围'),
        ];
    }
    public function getAll($num)
    {
        $menu = $this->find()->offset($num)->orderBy('day')->asArray()->all();
        return $menu;
    }
    public function getdays()
    {
        for($i=0;$i<73;$i++){
            for($j=1;$j<31;$j++){
                if($i==0){
                    if($j==1){
                        $text="宝宝出生了";
                    }else{
                        $text="宝宝出生第".$j."天";
                    }
                }else if($i>12){
                    $year=intval($i/12);
                    $mon=$i%12;
                    if($mon==0){
                        $text="宝宝".($year-1)."周岁12个月".$j."天";
                    }else{
                        $text="宝宝".$year."周岁".$mon."个月".$j."天";
                    }
                }else{
                    $text="宝宝".$i."个月".$j."天";
                }
                $id=$i*30+$j;
                $day[($j-1)]['id']=$id;
                $day[($j-1)]['text']=$text;
            }
            $id=$i*20000;
            if($i==0){
                $text="宝宝刚出生";
            }else if($i>12){
                $year=intval($i/12);
                $mon=$i%12;
                if($mon==0){
                    $text=($year-1)."周岁12个月";
                }else{
                    $text=$year."周岁".$mon."个月";
                }
                
            }else{
                $text="宝宝".$i."个月";
            }
            $month[$i]['state']="closed";
            $month[$i]['id']=$id;
            $month[$i]['text']=$text;
            $month[$i]['children']=$day;

        }
        return $month;
    }
}
