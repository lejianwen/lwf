<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/12
 * Time: 9:55
 * QQ: 84855512
 */

namespace lib;

use lib\guard\redis;

abstract class guard
{
    public static function init()
    {
        static $guard;
        if (!isset($guard)) {
            $guard = new redis();
        }
        return $guard;
    }

    /**
     * 绑定fd跟uuid
     * @param $fd
     * @param $uuid
     * @param int $room
     */
    public function bind($fd, $uuid, $room = 0)
    {
        $this->addFd($fd, $uuid);
        $this->intoRoom($uuid, $room);
        $this->addOrUpdateInfo($uuid, $fd, $room);
    }


    /**
     * 用户退出
     * @param $fd
     */
    public function out($fd)
    {
        if ($uuid = $this->getUuid($fd)) {
            $this->outRoom($uuid);
            $this->removeFd($fd);
            $this->removeUuid($uuid);
        }
    }

    /**
     * 重复登录
     * @param $new_fd
     * @param $uuid
     * @param $room
     */
    public function rebind($new_fd, $uuid, $room = 0)
    {
        if ($old_fd = $this->getFd($uuid)) {
            $this->removeFd($old_fd);
        }
        $this->bind($new_fd, $uuid, $room);
    }

    /**
     * 更换房间
     * @param $uuid
     * @param $new_room
     */
    public function changeRoom($uuid, $new_room)
    {
        $old_room = $this->getRoom($uuid);
        $this->outRoom($uuid, $old_room);
        $this->intoRoom($uuid, $new_room);
        $this->updateRoom($uuid, $new_room);
    }

    /**添加fd信息
     * @param $fd
     * @param $uuid
     */
    protected function addFd($fd, $uuid)
    {
    }

    /**删除fd信息
     * @param $fd
     */
    protected function removeFd($fd)
    {
    }


    /**加入房间
     * @param $uuid
     * @param int $room
     */
    protected function intoRoom($uuid, $room = 0)
    {
    }

    /**
     * 退出房间
     * @param $uuid
     */
    protected function outRoom($uuid)
    {
    }

    /**
     * 添加或更新用户信息
     * @param $uuid
     * @param $fd
     * @param $room
     */
    protected function addOrUpdateInfo($uuid, $fd, $room = 0)
    {
    }

    /**
     * 更新用户房间
     * @param $uuid
     * @param $room
     * @author Lejianwen
     */
    protected function updateRoom($uuid, $room)
    {
    }

    /**
     * 更新用户fd
     * @param $uuid
     * @param $fd
     * @author Lejianwen
     */
    protected function updateFd($uuid, $fd)
    {
    }

    /**
     * 删除用户信息
     * @param $uuid
     */
    protected function removeUuid($uuid)
    {
    }


    /**用户所在房间
     * @param $uuid
     * @return string
     */
    public function getRoom($uuid)
    {
    }

    /**根据fd获取房间
     * @param $fd
     * @return string
     */
    public function getMyRoom($fd)
    {
        $uuid = $this->getUuid($fd);
        return $this->getRoom($uuid);
    }

    /**通过uuid获取fd
     * @param $uuid
     * @return bool
     */
    public function getFd($uuid)
    {
    }

    /**根据用户获取连接详细信息 ['fd' => '标识' , 'room' => '频道', time=>'']
     * @param $uuid
     * @return bool
     */
    public function getInfo($uuid)
    {
    }

    /**通过fd获取uuid
     * @param $fd
     * @return bool
     */
    public function getUuid($fd)
    {
    }

    /**
     * 房间所有的uuid
     * @param int $room
     * @return array
     */
    public function getRoomUuid($room = 0)
    {
    }

    /**
     * 获取当前fd房间所有fd
     * @param $uuid
     * @return array
     */
    public function getAllUuidInMyRoom($uuid)
    {
        $room = $this->getMyRoom($uuid);
        return $this->getRoomUuid($room);
    }


    public function gc()
    {
    }
}