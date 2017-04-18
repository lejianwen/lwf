<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/14
 * Time: 10:23
 * QQ: 84855512
 */
namespace lib;

use lib\traits\message;

class controller
{
    use message;
    protected $frame;
    //数据
    protected $data;
    //来源uri
    protected $refer;
    //当前fd
    protected $fd;

    public function __construct(\swoole_websocket_frame $frame)
    {
        $this->frame = $frame;
        $data = self::decode($frame->data);
        $this->data = $data['data'];
        $this->refer = $data['uri'];
        $this->fd = $frame->fd;
    }

    /**投递任务
     * 会自动带上 \swoole_websocket_frame $this->frame
     * 实现方法在tasks中
     * @param $task_uri string 任务的uri
     * 比如'test/demo' 表示在tasks目录下test中的demo方法
     * @param array $data 数据
     * 可以是处理后的数据，也可以不传，因为frame会传过去
     */
    protected function _task($task_uri, $data = [])
    {
        server()->task(['task' => $task_uri, 'frame' => $this->frame, 'data' => $data]);
    }

    /**push信息
     * @param $uri
     * @param $fd
     * @param $data
     */
    protected function push($uri, $fd, $data = [])
    {
        $data['uri'] = $uri;
        server()->push($fd, self::encode($data));
    }

    /**回复当前fd信息
     * @param $uri
     * @param array $data
     */
    public function reply($uri, $data = [])
    {
        $this->push($uri, $this->fd, $data);
    }
}