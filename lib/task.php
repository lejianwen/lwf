<?php
/**
 *
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/14
 * Time: 17:23
 * QQ: 84855512
 */

namespace lib;

/**任务运行
 * Class task
 * @package lib
 */
class task
{
    use service;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data['data'];
    }


}