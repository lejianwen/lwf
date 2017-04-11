<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/30
 * Time: 14:28
 * QQ: 84855512
 */
namespace lib\traits;

/**主要用户对同性过程中数据的处理
 * Class message
 * @package lib\traits
 */
Trait message
{
    /**数据加密
     * @param $data
     * @return string
     */
    public static function encode($data)
    {
        return json_encode($data);
    }

    /**数据解密
     * @param $data
     * @return mixed
     */
    public static function decode($data)
    {
        return json_decode($data, true);
    }
}