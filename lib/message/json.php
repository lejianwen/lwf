<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2017/11/9
 * Time: 18:55
 */

namespace lib\message;


use lib\message;

class json extends message
{

    /**
     * encode
     * @param $data
     * @return string
     * @author Lejianwen
     */
    public function encode($data)
    {
        return json_encode($data);
    }

    /**
     * decode
     * @param $data
     * @return mixed
     * @author Lejianwen
     */
    public function decode($data)
    {
        return json_decode($data, true);
    }
}