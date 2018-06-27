<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/14
 * Time: 13:55
 * QQ: 84855512
 */

namespace lib\guard;

use lib\guard;
use lib\redis as Manager;

class redis extends guard
{
    /** @var \lib\redis mixed */
    protected $client;

    protected $uuid_pre;
    protected $fd_pre;
    protected $room_pre;


    public function __construct()
    {

        $this->client = Manager::_instance(config('store.redis_driver'));
        $this->uuid_pre = config('store.uuid_pre');
        $this->fd_pre = config('store.fd_pre');
        $this->room_pre = config('store.room_pre');
    }


    /**
     * 添加fd信息
     * @param $fd
     * @param $uuid
     */
    protected function addFd($fd, $uuid)
    {
        $this->client->connect()->set($this->fd_pre . $fd, $uuid);
    }

    /**
     * 删除fd信息
     * @param $fd
     */
    public function removeFd($fd)
    {
        $this->client->connect()->del($this->fd_pre . $fd);
    }

    /**加入房间
     * @param $uuid
     * @param int $room
     */
    protected function intoRoom($uuid, $room = 0)
    {
        $this->client->connect()->sAdd($this->room_pre . $room, $uuid);
    }

    /**
     * 退出房间
     * @param $uuid
     */
    protected function outRoom($uuid)
    {
        $room = $this->getRoom($uuid);
        $this->client->connect()->sRem($this->room_pre . $room, $uuid);
    }

    /**
     * 添加或更新用户信息
     * @param $fd
     * @param $uuid
     * @param $room
     */
    protected function addOrUpdateInfo($uuid, $fd, $room = 0)
    {
        $this->client->connect()->hMset($this->uuid_pre . $uuid, ['fd' => $fd, 'room' => $room, 'time' => date('Y-m-d H:i:s')]);
    }

    /**
     * 更新用户房间
     * @param $uuid
     * @param $room
     * @author Lejianwen
     */
    protected function updateRoom($uuid, $room)
    {
        $this->client->connect()->hSet($this->uuid_pre . $uuid, 'room', $room);
    }

    /**
     * 更新用户fd
     * @param $uuid
     * @param $fd
     * @author Lejianwen
     */
    protected function updateFd($uuid, $fd)
    {
        $this->client->connect()->hSet($this->uuid_pre . $uuid, 'fd', $fd);
    }

    /**
     * 删除用户信息
     * @param $uuid
     */
    protected function removeUuid($uuid)
    {
        $this->client->connect()->del($this->uuid_pre . $uuid);
    }


    /**
     * 用户所在房间
     * @param $uuid
     * @return string
     */
    public function getRoom($uuid)
    {
        return $this->client->connect()->hGet($this->uuid_pre . $uuid, 'room');
    }

    /**通过uuid获取fd
     * @param $uuid
     * @return bool
     */
    public function getFd($uuid)
    {
        return $this->client->connect()->hGet($this->uuid_pre . $uuid, 'fd');
    }

    /**
     * 根据用户获取连接详细信息 ['fd' => '标识' , 'room' => '频道', time=>'']
     * @param $uuid
     * @return bool
     */
    public function getInfo($uuid)
    {
        $info = $this->client->connect()->hGetAll($this->uuid_pre . $uuid);
        return $info ?: false;
    }

    /**
     * 通过fd获取uuid
     * @param $fd
     * @return bool
     */
    public function getUuid($fd)
    {
        $uuid = $this->client->connect()->get($this->fd_pre . $fd);
        return $uuid ?: false;
    }

    /**
     * 房间所有的uuid
     * @param int $room
     * @return array
     */
    public function getRoomUuid($room = 0)
    {
        return $this->client->connect()->sMembers($this->room_pre . $room);
    }

    public function getUuidFromRoom($room)
    {
        $this->client->connect()->sPop($this->room_pre . $room);
    }

    public function gc()
    {
        //$this->client->connect()->flushDB();
    }
}