<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/12
 * Time: 9:55
 * QQ: 84855512
 */

namespace lib;

abstract class store
{
    public static function _init()
    {
        static $class;
        if (!isset($class)) {
            $class_str = 'lib\\store\\' . config('app.store');
            $class = new $class_str;
        }
        return $class;
    }

    /**绑定fd跟uuid
     * @param $fd
     * @param $uuid
     * @param int $room
     */
    public function bind($fd, $uuid, $room = 0)
    {
        $this->addFd($fd, $uuid);
        $this->intoRoom($fd, $room);
        $this->addOrUpdateUuid($fd, $uuid, $room);
    }


    /**用户退出
     * @param $fd
     */
    public function out($fd)
    {
        if ($uuid = $this->getUuid($fd)) {
            $room = $this->getRoom($uuid);
            $this->removeFd($fd);
            $this->removeUuid($uuid);
            $this->outRoom($fd, $room);
        }
    }

    /**重复登录
     * @param $old_fd
     * @param $new_fd
     * @param $uuid
     */
    public function rebind($old_fd, $new_fd, $uuid)
    {
        $room = $this->getRoom($uuid);
        $this->removeFd($old_fd);
        $this->removeUuid($uuid);
        $this->outRoom($old_fd, $room);

        $this->addFd($new_fd, $uuid);
        $this->intoRoom($new_fd, $room);
        $this->addOrUpdateUuid($new_fd, $uuid, $room);
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
     * @param $fd
     * @param int $room
     */
    protected function intoRoom($fd, $room = 0)
    {
    }

    /**退出房间
     * @param $fd
     * @param $room
     */
    protected function outRoom($fd, $room)
    {
    }

    /**添加或更新用户信息
     * @param $fd
     * @param $uuid
     * @param $room
     */
    protected function addOrUpdateUuid($fd, $uuid, $room = 0)
    {
    }

    /**删除用户信息
     * @param $uuid
     */
    protected function removeUuid($uuid)
    {
    }

    /**更换房间
     * @param $fd
     * @param $old_room
     * @param $new_room
     */
    public function changeRoom($fd, $old_room, $new_room)
    {
        $uuid = $this->getUuid($fd);
        $this->outRoom($fd, $old_room);
        $this->intoRoom($fd, $new_room);
        $this->addOrUpdateUuid($fd, $uuid, $new_room);
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

    /**房间所有的fd
     * @param int $room
     * @return array
     */
    public function getRoomFds($room = 0)
    {
    }

    /**获取当前fd房间所有fd
     * @param $fd
     * @return array
     */
    public function getMyRoomFds($fd)
    {
        $room = $this->getMyRoom($fd);
        return $this->getRoomFds($room);
    }


    public function gc()
    {
    }
}