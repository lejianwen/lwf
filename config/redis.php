<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/11/18
 * Time: 10:43
 */
//redis的配置
return [
    'default' => [
        'host'     => '127.0.0.1',
        'pwd'      => null,
        'port'     => 6379,
        'database' => 0,
        'prefix'   => 'fmz:'
    ],
    'other'   => [
        'host'     => '127.0.0.1',
        'pwd'      => null,
        'port'     => 6379,
        'database' => 1,
    ]
];