<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/8
 * Time: 15:04
 * QQ: 84855512
 */
namespace app\controllers;
use lib\controller;

class heart extends controller
{
    public function ping()
    {
        $this->push($this->frame->fd, ['uri' => 'pong']);
    }
}