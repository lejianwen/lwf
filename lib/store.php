<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2017/11/16
 * Time: 17:12
 */

namespace lib;

class store
{
    protected $client;

    public static function init()
    {
        static $store;
        if (!$store) {
            $store = new self();
        }
        return $store;
    }

    public function __construct()
    {
        if (!$this->client) {
            $this->client = new \Redis();
            $this->client->connect(config('redis.host'), config('redis.port'));
            if ($password = config('redis.password')) {
                $this->client->auth($password);
            }
        }
        return $this;
    }

    public static function __callStatic($func, $params)
    {
        return static::init()->$func(...$params);
    }

    public function __call($func, $params)
    {
        return $this->client->$func(...$params);
    }
}