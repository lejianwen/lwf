<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/11
 * Time: 11:06
 * QQ: 84855512
 */
namespace app\controllers;
use lib\controller;

class system extends controller
{

    public function reload()
    {
        $this->serverConsole($this->frame, 'reload');
    }

    public function close()
    {
        $this->serverConsole($this->frame, 'close');
    }

    public function status()
    {
        $this->serverConsole($this->frame, 'status');
    }

    /**鉴权
     * @return bool
     */
    public function checkAuth()
    {
        if($this->data['token'] == '111111')
            return true;
        return false;
    }


    /**服务控制
     * @param $server
     * @param $frame
     * @param null $cmd
     */
    public function serverConsole($frame, $cmd = null)
    {
        if(!$this->checkAuth())
        {
            server()->close($frame->fd);
            return;
        }
        if (!$cmd || empty($cmd))
            return;
        switch ($cmd)
        {
            case 'reload':  //重启
                server()->reload();
                break;
            case 'close':  //关闭
                server()->shutdown();
                break;
            case 'status':  //状态
                server()->push($frame->fd, self::encode(server()->stats()));
                break;
            default:
                //$server->task($frame->data);
                break;
        }
    }
}