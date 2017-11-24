<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2017/11/16
 * Time: 17:10
 */

namespace app\stores;

use app\models\Robot;
use app\models\UserData;
use app\models\UserGame;

class User
{
    public $id;

    public static function info($id)
    {
        $user = new static();
        $user->id = $id;
        $user->info = store()->get(config('store.user_info') . $id);
        return $user;
    }

}