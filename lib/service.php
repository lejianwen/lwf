<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2017/11/17
 * Time: 17:45
 */

namespace lib;

trait service
{
    /**
     * push信息
     * @param $fd
     * @param $uri
     * @param $data
     */
    protected function push($fd, $uri, $data = [])
    {
        $data['uri'] = $uri;
        server()->push($fd, msg_encode($data));
    }

    /**
     * 发送信息给用户
     * @param $user_id
     * @param $uri
     * @param array $data
     * @author Lejianwen
     */
    public function sendTo($user_id, $uri, $data = [])
    {
        $fd = guard()->getFd($user_id);
        $this->push($fd, $uri, $data);
    }

    /**
     * 投递任务
     * 会自动带上 \swoole_websocket_frame $this->frame
     * 实现方法在tasks中
     * @param $task_uri string 任务的uri
     * 比如'test/demo' 表示在tasks目录下test中的demo方法
     * @param array $data 数据
     * 可以是处理后的数据，也可以不传，因为frame会传过去
     */
    protected function task($task_uri, $data = [])
    {
        server()->task(['task' => $task_uri, 'data' => $data]);
    }
}