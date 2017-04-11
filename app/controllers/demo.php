<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/8
 * Time: 14:12
 * QQ: 84855512
 */
namespace app\controllers;
use lib\controller;

class demo extends controller
{

    public function test()
    {
        $this->push('test', $this->frame->fd, ['data' => 'test~~~~111']);
    }

    public function test2()
    {
        $this->push('test2', $this->frame->fd, ['data' => $this->data, 'msg' => 'okok']);
    }

    /**任务投递
     *
     */
    public function task()
    {
        $this->_task('test@test1', ['data' => 'test_task']);
    }

}