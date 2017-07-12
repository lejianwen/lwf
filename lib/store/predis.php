<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/4/14
 * Time: 13:55
 * QQ: 84855512
 */

namespace lib\store;

use lib\store;

class predis extends store
{
    static $client;

    const UUID_PRE = 'USER:';
    const FD_PRE = 'FD:';
    const ROOM_PRE = 'ROOM:';


    public function __construct()
    {
        if (!self::$client) {
            self::$client = new \Predis\Client(config('redis'));
        }
        return $this;
    }

    /**添加fd信息
     * @param $fd
     * @param $uuid
     */
    protected function addFd($fd, $uuid)
    {
        self::$client->set(self::FD_PRE . $fd, $uuid);
    }

    /**删除fd信息
     * @param $fd
     */
    protected function removeFd($fd)
    {
        self::$client->del(self::FD_PRE . $fd);
    }

    /**加入房间
     * @param $fd
     * @param int $room
     */
    protected function intoRoom($fd, $room = 0)
    {
        self::$client->sadd(self::ROOM_PRE . $room, $fd);
    }

    /**退出房间
     * @param $fd
     * @param $room
     */
    protected function outRoom($fd, $room)
    {
        self::$client->srem(self::ROOM_PRE . $room, $fd);
    }

    /**添加或更新用户信息
     * @param $fd
     * @param $uuid
     * @param $room
     */
    protected function addOrUpdateUuid($fd, $uuid, $room = 0)
    {
        self::$client->hmset(self::UUID_PRE . $uuid, ['fd' => $fd, 'room' => $room, 'time' => date('Y-m-d H:i:s')]);
    }

    /**删除用户信息
     * @param $uuid
     */
    protected function removeUuid($uuid)
    {
        self::$client->del(self::UUID_PRE . $uuid);
    }


    /**用户所在房间
     * @param $uuid
     * @return string
     */
    public function getRoom($uuid)
    {
        return self::$client->hget(self::UUID_PRE . $uuid, 'room');
    }

    /**通过uuid获取fd
     * @param $uuid
     * @return bool
     */
    public function getFd($uuid)
    {
        $fd = self::$client->hget(self::UUID_PRE . $uuid, 'fd');
        return $fd ?: false;
    }

    /**根据用户获取连接详细信息 ['fd' => '标识' , 'room' => '频道', time=>'']
     * @param $uuid
     * @return bool
     */
    public function getInfo($uuid)
    {
        $info = self::$client->hgetall(self::UUID_PRE . $uuid);
        return $info ?: false;
    }

    /**通过fd获取uuid
     * @param $fd
     * @return bool
     */
    public function getUuid($fd)
    {
        $uuid = self::$client->get(self::FD_PRE . $fd);
        return $uuid ?: false;
    }

    /**房间所有的fd
     * @param int $room
     * @return array
     */
    public function getRoomFds($room = 0)
    {
        return self::$client->smembers(self::ROOM_PRE . $room);
    }

    public function gc()
    {
        self::$client->flushdb();
    }
}