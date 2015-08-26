<?php

namespace common\models;

use Yii;
use common\models\Agency;

/**
 * This is the model class for table "{{%agency_cout}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $first_agency
 * @property integer $second_agency
 */
class AgencyCout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agency_cout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'first_agency', 'second_agency'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'first_agency' => Yii::t('app', '一级代理数'),
            'second_agency' => Yii::t('app', '二级代理数'),
        ];
    }

    // public function addAgencyCount($uid)
    // {
    //     $agencyCount = AgencyCout::find()->where(['uid'=>$uid])->one();
    //     $firstAgencyCount = 0 ;
    //     $secondAgencyCount = 0 ;
    //     if($agencyCount){
    //         $model = $agencyCount;
    //     }else{
    //         $model = new AgencyCout();
    //     }
    //     $agency = new Agency();
    //     $agencyCountArr = $agency->findAgencyById($uid);
    //     if($agencyCountArr){
    //         $model->uid = $uid;
    //         $model->first_agency = $agencyCountArr['first'];
    //         $model->second_agency = $agencyCountArr['second'];
    //         $model->save();
    //     }
    // }



    public function addAgencyCountParent($uid)
    {
        // $agencyCount = AgencyCout::find()->
    }

    public function addAgencyCount($uid) //需要修改
    {
        $agencyCount = AgencyCout::find()->where(['uid'=>$uid])->one();
        $firstAgencyCount = 0 ;
        $secondAgencyCount = 0 ;
        if($agencyCount){
            $firstAgencyCount = ($step == 1) ? $model->first_agency + 1 : $model->first_agency;
            $secondAgencyCount = ($step == 1) ? $model->second_agency : $model->second_agency + 1;
        }else{
            $firstAgencyCount = ($step == 1) ? 1 : 0;
            $secondAgencyCount = ($step == 1) ? 0 : 1;
        }
        $this->_addAgencyCount($uid,$firstAgencyCount,$secondAgencyCount);
        $agency = new Agency();
        $parentId = $agency->findByChild($uid);
        if($parentId){
            $this->_addAgencyCount($parentId)
        }
    }

    public function _addAgencyCount($uid,$first = 0 ,$second = 0)
    {
        $agencyCount = AgencyCout::find()->where(['uid'=>$uid])->one();
        $model = $agencyCount ? $agencyCount : (new AgencyCout()) ;
        $model->uid = $uid;
        $model->first_agency += $first;
        $model->second_agency += $second;
        $model->save();
    }

}
