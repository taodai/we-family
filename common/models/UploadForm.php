<?php

namespace common\models;

use Yii;
use yii\base\Model;


class UploadForm extends Model
{

    public $file;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }

}
