<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%app_tips}}".
 *
 * @property integer $id
 * @property integer $month
 * @property string $tips
 * @property string $notice
 */
class AppTips extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_tips}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notice'], 'string'],
            [['month'], 'string', 'max' => 20],
            [['id', 'number'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'month' => Yii::t('app', '月份'),
            'notice' => Yii::t('app', '月份相关注意事项'),
            'number' => Yii::t('app', '月份编号'),
        ];
    }
    public function getAll($num)
    {
        $menu = $this->find()->offset($num)->orderBy('number')->asArray()->all();
        return $menu;
    }
    public function getmonths()
    {
        $type=array('备孕期','怀孕期','新生儿(0-1岁)','婴幼儿(1-3岁)','学龄前(3-6岁)');
        $daynum=1;
        for($i=0;$i<5;$i++){
            if($i==0){
                $month[$i]['state']="closed";
                $month[$i]['id']=$i;
                $month[$i]['text']=$type[$i];
                //$month[$i]['children']=$day;
            }else{
                if($i==1){
                    $num=11;
                }else if($i==2){
                    $num=13;
                }else if($i==3){
                    $num=25;
                }else if($i==4){
                    $num=37;
                }
                for($j=1;$j<$num;$j++){
                    $id=$daynum;
                    if($id>10){
                        $mid=$id-10;
                    }else{
                        $mid=$id;
                    }
                    if($mid>12){
                        $year=intval($mid/12);
                        $mon=$mid%12;
                        $text=$year."周岁".$mon."个月";
                    }else{
                        $text="第".$mid."个月";
                    }
                    
                    
                    $day[($j-1)]['id']=$id;
                    $day[($j-1)]['text']=$text;
                    $daynum++;
                }
                $id=$i*100;
                $text=$type[$i];
                $month[$i]['state']="closed";
                $month[$i]['id']=$id;
                $month[$i]['text']=$text;
                $month[$i]['children']=$day;
            } 

        }
        return $month;
    }
}
