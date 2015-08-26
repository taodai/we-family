<?php
namespace common\components;
use Yii;
/**
 * 搜索引擎封装
 * 
 */

class OrderCode{

    const ORDERPREFIX = 'S';
    const BACKPREFIX  = 'R';
    const LENGTH      = 6;
    /*订单编号*/
    public $orderCode;
    /*退单编号*/
    public $chargeBackCode;

    public function rangeOrderCode()
    {
        $this->orderCode = self::ORDERPREFIX.date('YmdHis').$this->_rangeCode(self::LENGTH);
    }

    public function rangeChargeBackCode()
    {
        $this->orderCode = self::BACKPREFIX.date('YmdHis').$this->_rangeCode(self::LENGTH);
    }

    private function _rangeCode($length)
    {
        $chars = '0123456789';  
        $code = '';
        for ( $i = 0; $i < $length; $i++ )  
        {  
            $code .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
        }
        return $code;
    }
}
?>