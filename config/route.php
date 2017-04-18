<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/14
 * Time: 9:40
 * QQ: 84855512
 */


/**路由配置
 *
 */
return [
    'system/heart'  => 'system@heart',     //心跳
    'system/status' => 'system@status',     //系统状态
    'system/reload' => 'system@reload',     //系统重启
    'system/close'  => 'system@close',      //系统关闭

    'demo/test'   => 'demo@test',
    'demo/test2'  => 'demo@test2',
    'demo/task'   => 'demo@task',
    'demo/toRoom' => 'demo@toRoom',

    'demo/test_c' => 'demo@test_c'
];