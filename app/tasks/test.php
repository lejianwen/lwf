<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/15
 * Time: 15:48
 * QQ: 84855512
 */

namespace app\tasks;

use lib\task;

class test extends task
{
    public function test1()
    {
        $data = [
            'data'     => $this->data,
            'task_msg' => 'test task success!'
        ];
        $this->push($this->frame->fd, $data);
    }
}