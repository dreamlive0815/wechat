<?php

$baseDir = 'D:/Site/wechat/overtrue3.1';
return [
    /* 个人号
    'app_id' => 'wxec6559280db2592b',
    'secret' => '6b5a3de5b3bd1ee8c7dcf62382386464',
    */
    /* 测试号 */
    'app_id' => 'wx6f2b3c73147cce5e',
    'secret' => '759502f6fee18d38b2d0037aeb493c69',

    'token' => 'lonewanderer',
    'debug' => true,

    'log' => [
        'level' => 'debug',
        'file'  => $baseDir . '/log/wechat_beta.log',
    ],

    'guzzle' => [
        'timeout' => 3.0,
    ]
];

