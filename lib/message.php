<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/30
 * Time: 14:28
 * QQ: 84855512
 */

namespace lib;

/**
 * Class message
 * 主要用户对同性过程中数据的处理
 * @package lib
 */
abstract class message
{
    public static function init()
    {
        static $message;
        if (!$message) {
            $class_str = 'lib\\message\\' . config('app.message');
            $message = new $class_str;
        }
        return $message;
    }

    /**数据加密
     * @param $data
     * @return string
     */
    public function encode($data)
    {
        return json_encode($data);
    }

    /**数据解密
     * @param $data
     * @return mixed
     */
    public function decode($data)
    {
        return json_decode($data, true);
    }
}