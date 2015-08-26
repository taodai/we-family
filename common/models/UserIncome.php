<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
use common\models\User;
use common\models\AppUserinfo;
/**
 * This is the model class for table "{{%user_income}}".
 *
 * @property integer $id
 * @property integer $userid
 * @property integer $child_id
 * @property double $income
 * @property integer $level
 * @property integer $income_time
 * @property integer $source
 * @property string $content
 */
class UserIncome extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_income}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'child_id', 'income', 'level', 'income_time', 'source'], 'required'],
            [['userid', 'child_id', 'level', 'income_time', 'source'], 'integer'],
            [['income'], 'number'],
            [['content'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userid' => Yii::t('app', '关联用户ID'),
            'child_id' => Yii::t('app', '代理ID（用户ID）'),
            'income' => Yii::t('app', 'Income'),
            'level' => Yii::t('app', '收益等级1，一级，2，二级'),
            'income_time' => Yii::t('app', '收益时间'),
            'source' => Yii::t('app', '来源（1微信,2，APP,3）'),
            'content' => Yii::t('app', '收益说明'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['uid'=>'child_id']);
    }
    public function getUserinfo()
    {
        return $this->hasOne(AppUserinfo::className(),['uid'=>'child_id']);
    }
    //查询收益明细
    public function getIncomeList($uid)
    {
        $lists = $this::find()->joinwith('user')->where(['userid'=>$uid])->asArray()->all();
        foreach ($lists as $key => $list) {
            if(is_array($list)){
                unset($lists[$key]['user']['password']);
                unset($lists[$key]['user']['auth_key']);
            }
        }
        return $lists;
    }

    // 按代理等级查询收益明细列表
    public function getIncomeListByLevel($uid,$level)
    {
        $lists = $this::find()->joinwith('userinfo')->where(['userid'=>$uid,'level'=>$level])->asArray()->all();
        $sumIdArr = array();
        $newLists = array();
        foreach ($lists as $key => $list) {
            unset($lists[$key]['user']);
            if(in_array($list['child_id'],$sumIdArr)){
                 $newLists[$list['child_id']]['income'] = $newLists[$list['child_id']]['income'] + $list['income'];
            }else{
                unset($list['user']);
                $newLists[$list['child_id']] = $list;
                array_push($sumIdArr,$list['child_id']);
            }
            // if(is_array($list)){
            //     unset($lists[$key]['user']['password']);
            //     unset($lists[$key]['user']['auth_key']);
            // }
        }
        // print_r($newLists);exit;
        return $newLists;
    }    

    //查询收益总计 返回代理等级总额数组
    public function getIncomeAllByLevel($uid)
    {
        $lists = $this::find()->where(['userid'=>$uid])->asArray()->all();
        $newLists = array();
        $levelTwo = 0;
        $levelThree = 0;
        foreach ($lists as $key => $list) {
            if($list['level'] == 2){
                $levelTwo += $list['income'];
            }else{
                $levelThree += isset($list['income']) ? $list['income'] : 0;
            }
        }
        $newLists['leveltwo'] = $levelTwo;
        $newLists['levelthree'] = $levelThree;
        return $newLists;
    }
}
