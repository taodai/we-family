<?php

namespace common\models;

use Yii;
use common\models\AbstractModel;
/**
 * This is the model class for table "{{%app_params}}".
 *
 * @property integer $id
 * @property integer $appId
 * @property string $appName
 * @property string $appKey
 * @property string $appSecret
 * @property string $appToken
 * @property integer $p_status
 * @property integer $time_on
 * @property integer $time_off
 */
class AppParams extends AbstractModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%app_params}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appId', 'appName', 'appKey', 'appSecret', 'appToken', 'time_on'], 'required'],
            [['p_status', 'time_on', 'time_off'], 'integer'],
            [['appId'], 'string', 'max' => 20],
            [['appName', 'appToken'], 'string', 'max' => 50],
            [['appKey', 'appSecret'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'appId' => Yii::t('app', 'appId'),
            'appName' => Yii::t('app', 'APP名称'),
            'appKey' => Yii::t('app', '密钥'),
            'appSecret' => Yii::t('app', '密钥字符串'),
            'appToken' => Yii::t('app', '应用token'),
            'p_status' => Yii::t('app', '状态：1正常；2停用'),
            'time_on' => Yii::t('app', '开通时间'),
            'time_off' => Yii::t('app', '停止时间'),
        ];
    }

    public function getAll()
    {
        $return = $this->find()->orderBy('time_on')->asArray()->all();
        return $return;
    }

    public function checkApp($params)
    {
        if(is_array($params)){
            
        }else{
            return 1;
        }
    }
}
