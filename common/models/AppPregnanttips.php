<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%app_pregnanttips}}".
 *
 * @property integer $id
 * @property string $tips
 * @property string $baby_height
 * @property string $baby_weight
 * @property integer $day
 */
class AppPregnanttips extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_pregnanttips}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tips'], 'string'],
            [['day'], 'integer'],
            [['baby_height', 'baby_weight'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tips' => Yii::t('app', '每天提醒'),
            'baby_height' => Yii::t('app', '胎儿身高'),
            'baby_weight' => Yii::t('app', '胎儿体重'),
            'day' => Yii::t('app', '怀孕天数'),
        ];
    }
    public function getweeks()
    {
        for($i=0;$i<41;$i++){
            if($i==40){
                $num=1;
            }else{
                $num=7;
            }
            $day=array();
            for($j=0;$j<$num;$j++){
                if($i==0){
                    if($j>0){
                        $text="孕".$i."周+".$j."天";
                        $id=$i*7+$j;
                        $day[($j-1)]['id']=$id;
                        $day[($j-1)]['text']=$text;
                    }
                }else{
                    $text="孕".$i."周+".$j."天";
                    $id=$i*7+$j;
                    $day[$j]['id']=$id;
                    $day[$j]['text']=$text;
                }
                
            }
            $id=$i*1000;
            
            $text="孕".$i."周";
            
            $week[$i]['state']="closed";
            $week[$i]['id']=$id;
            $week[$i]['text']=$text;
            $week[$i]['children']=$day;

        }
        return $week;
    }
}
