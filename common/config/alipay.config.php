<?php
return   [
    'alipay_config'=>[
        'partner'=>'2088911646604683', //合作身份者id，以2088开头的16位纯数字
        'private_key_path'=>'key/rsa_private_key.pem',//商户的私钥（后缀是.pem）文件相对路径
        'ali_public_key_path'=>'key/alipay_public_key.pem',//支付宝公钥（后缀是.pem）文件相对路径
        'sign_type'=>strtoupper('RSA'),//签名方式 不需修改
        'input_charset'=>strtolower('utf-8'),//字符编码格式 目前支持 gbk 或 utf-8
        'cacert'=>getcwd().'\\cacert.pem',//ca证书路径地址，用于curl中ssl校验
                                            //请保证cacert.pem文件在当前文件夹目录中
        'transport'=>'http' //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        
    ]
];

?>