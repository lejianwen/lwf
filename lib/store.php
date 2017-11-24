<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2017/11/16
 * Time: 17:12
 */

namespace lib;

/**
 * Class store
 * @package lib|\Redis
 */
class store
{
    /** @var  \Redis $client */
    protected $client;
    static $store;

    /**
     * init
     * @return store|\Redis
     * @author Lejianwen
     */
    public static function init()
    {
        if (!self::$store) {
            self::$store = new static();
        }
        return self::$store;
    }

    public function __construct()
    {
        $this->toConnect();
    }

    public function toConnect()
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

    public function disConnect()
    {
        if ($this->client) {
            $this->client = null;
        }
    }


    public static function __callStatic($func, $params)
    {
        return static::init()->$func(...$params);
    }

    public function __call($func, $params)
    {
        return $this->toConnect()->client->$func(...$params);
    }

    public function set($key, $value, $ttl = 0)
    {
        if (!is_numeric($value)) {
            $value = serialize($value);
        }
        $this->toConnect()->client->set($key, $value);
        if ($ttl) {
            $this->client->expire($key, $ttl);
        }
    }

    public function get($key)
    {
        $value = $this->toConnect()->client->get($key);
        if ($value && !is_numeric($value)) {
            $value = unserialize($value);
        }
        return $value;
    }
}