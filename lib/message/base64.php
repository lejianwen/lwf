<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2017/11/9
 * Time: 18:58
 */

namespace lib\message;

use lib\message;

class base64 extends message
{
    /**
     * encode
     * @param $data
     * @return string
     * @author Lejianwen
     */
    public function encode($data)
    {
        return base64_encode($data);
    }

    /**
     * decode
     * @param $data
     * @return mixed
     * @author Lejianwen
     */
    public function decode($data)
    {
        return base64_decode($data, true);
    }
}