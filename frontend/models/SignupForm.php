<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $uname;
    public $code;
    public $password;
    public $password_confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['uname', 'filter', 'filter' => 'trim'],
            ['uname', 'required'],
            ['uname','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}必须为1开头的11位纯数字'],
            ['uname', 'unique', 'targetClass' => '\common\models\User', 'message' => '手机号码已存在.'],

            [['password','code','password_confirm'], 'required'],
            ['password', 'string', 'min' => 6],
            ['password_confirm', 'compare','compareAttribute'=>'password']
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
}
