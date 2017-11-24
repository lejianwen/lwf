<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/14
 * Time: 10:23
 * QQ: 84855512
 */

namespace lib;

use app\stores\User;
use Illuminate\Database\Capsule\Manager as DB;

class controller
{
    use service;
    protected $frame;
    //数据
    protected $data;
    //来源uri
    protected $refer;
    //当前fd
    protected $fd;
    //当前用户id
    protected $user_id;

    public function __construct(\swoole_websocket_frame $frame)
    {
        $this->frame = $frame;
        $data = msg_decode($frame->data);
        $this->data = $data['data'];
        $this->refer = $data['uri'];
        $this->fd = $frame->fd;
        $this->user_id = guard()->getUuid($this->fd);
        if (!$this->user_id) {
            server()->close($this->fd);
        }
    }

    /**
     * 回复当前fd信息
     * @param $uri
     * @param array $data
     * @author Lejianwen
     */
    public function reply($uri, $data = [])
    {
        $this->push($this->fd, $uri, $data);
    }

    /**
     * curUser
     * @author Lejianwen
     */
    public function curUser()
    {
        return User::info($this->user_id);
    }

    /**
     * 注销关闭数据库
     */
    public function __destruct()
    {
        // store()->disConnect();
        DB::connection()->disconnect();
    }
}