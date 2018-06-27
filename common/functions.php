<?php
/**
 * Created by PhpStorm.
 * 框架公用方法
 * User: lejianwen
 * Date: 2017/2/3
 * Time: 11:52
 * QQ: 84855512
 */

/**中文拼音首字母
 * @param $s0
 * @return null|string
 */
function getFirstChar($s0)
{
    $fchar = ord($s0{0});
    if ($fchar >= ord("A") and $fchar <= ord("z")) {
        return strtoupper($s0{0});
    }
    $s1 = iconv("UTF-8", "gb2312", $s0);
    $s2 = iconv("gb2312", "UTF-8", $s1);
    if ($s2 == $s0) {
        $s = $s1;
    } else {
        $s = $s0;
    }
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 and $asc <= -20284) {
        return "A";
    }
    if ($asc >= -20283 and $asc <= -19776) {
        return "B";
    }
    if ($asc >= -19775 and $asc <= -19219) {
        return "C";
    }
    if ($asc >= -19218 and $asc <= -18711) {
        return "D";
    }
    if ($asc >= -18710 and $asc <= -18527) {
        return "E";
    }
    if ($asc >= -18526 and $asc <= -18240) {
        return "F";
    }
    if ($asc >= -18239 and $asc <= -17923) {
        return "G";
    }
    if ($asc >= -17922 and $asc <= -17418) {
        return "I";
    }
    if ($asc >= -17417 and $asc <= -16475) {
        return "J";
    }
    if ($asc >= -16474 and $asc <= -16213) {
        return "K";
    }
    if ($asc >= -16212 and $asc <= -15641) {
        return "L";
    }
    if ($asc >= -15640 and $asc <= -15166) {
        return "M";
    }
    if ($asc >= -15165 and $asc <= -14923) {
        return "N";
    }
    if ($asc >= -14922 and $asc <= -14915) {
        return "O";
    }
    if ($asc >= -14914 and $asc <= -14631) {
        return "P";
    }
    if ($asc >= -14630 and $asc <= -14150) {
        return "Q";
    }
    if ($asc >= -14149 and $asc <= -14091) {
        return "R";
    }
    if ($asc >= -14090 and $asc <= -13319) {
        return "S";
    }
    if ($asc >= -13318 and $asc <= -12839) {
        return "T";
    }
    if ($asc >= -12838 and $asc <= -12557) {
        return "W";
    }
    if ($asc >= -12556 and $asc <= -11848) {
        return "X";
    }
    if ($asc >= -11847 and $asc <= -11056) {
        return "Y";
    }
    if ($asc >= -11055 and $asc <= -10247) {
        return "Z";
    }
    return null;
}

/**中文字符串的拼音首字母
 * @param $zh
 * @return string
 */
function getPinYinFirstChar($zh)
{
    $ret = "";
    $s1 = iconv("UTF-8", "gb2312", $zh);
    $s2 = iconv("gb2312", "UTF-8", $s1);
    if ($s2 == $zh) {
        $zh = $s1;
    }
    for ($i = 0; $i < strlen($zh); $i++) {
        $s1 = substr($zh, $i, 1);
        $p = ord($s1);
        if ($p > 160) {
            $s2 = substr($zh, $i++, 2);
            $ret .= getFirstChar($s2);
        } else {
            $ret .= $s1;
        }
    }
    return $ret;
}

/**获取配置参数
 * @param $key
 * @param null $value
 * @return null
 * @throws Exception
 */
if (!function_exists('config')) {
    function &config($key, $value = null)
    {
        if (CONFIG_STATIC) {
            static $config;
        } else {
            $config = [];
        }
        if ($key == '') {
            return $config;
        }
        $key_arr = explode('.', $key);
        $key_len = count($key_arr);
        if (!$config[$key_arr[0]]) {
            if (file_exists(CONFIG_PATH . $key_arr[0] . '.php')) {
                $config[$key_arr[0]] = require CONFIG_PATH . $key_arr[0] . '.php';
            } else {
                throw new Exception("{$key_arr[0]}.php not found!");
            }
        }
        //修改配置
        if ($value !== null) {
            switch ($key_len) {
                case 1:
                    $config[$key_arr[0]] = $value;
                    return true;
                    break;
                case 2:
                    $config[$key_arr[0]][$key_arr[1]] = $value;
                    return true;
                    break;
                case 3:
                    $config[$key_arr[0]][$key_arr[1]][$key_arr[2]] = $value;
                    return true;
                    break;
                default:
                    return false;
                    break;
            }
        }
        switch ($key_len) {
            case 1:
                return $config[$key_arr[0]];
                break;
            case 2:
                return $config[$key_arr[0]][$key_arr[1]];
                break;
            case 3:
                return $config[$key_arr[0]][$key_arr[1]][$key_arr[2]];
                break;
            default:
                return null;
                break;
        }
    }
}

function server()
{
    return bootstrap::$server;
}

function msg_encode($data)
{
    return \lib\message::init()->encode($data);
}

function msg_decode($data)
{
    return \lib\message::init()->decode($data);
}

function guard()
{
    return \lib\guard::init();
}


if (!function_exists('redis')) {
    /**
     * redis
     * @param null $name
     * @return lib\redis|\Redis
     * @author Lejianwen
     */
    function redis($name = null)
    {
        return \lib\redis::_instance($name);
    }
}

/**
 * 发送信息给用户
 * @param $user_id
 * @param $uri
 * @param array $data
 * @author Lejianwen
 */
function sendTo($user_id, $uri, $data = [])
{
    $fd = guard()->getFd($user_id);
    if (!$fd) {
        return;
    }
    if (server()->exist($fd)) {
        $data['uri'] = $uri;
        server()->push($fd, msg_encode($data));
    } else {
        //没有重新登陆
        if (guard()->getFd($user_id) == $fd) {
            guard()->out($fd);
        } else {
            guard()->removeFd($fd);
        }
    }

}