<?php

namespace App\Library\UserWebsite;


class UwSession
{

    /**
     * UwSession has
     *
     * @param $key
     * @return bool
     * @static
     */
    public static function has($key)
    {
        return \Session::has($key . '_' .app('user-website')->id);
    }

    /**
     * UwSession has
     *
     * @param $key
     * @return mixed
     * @static
     */
    public static function get($key)
    {
        return \Session::get($key . '_' .app('user-website')->id);
    }

    /**
     * Put a key / value pair or array of key / value pairs in the session.
     *
     * @param string|array $key
     * @param mixed $value
     * @return void
     * @static
     */
    public static function put($key, $value)
    {
        \Session::put($key . '_' .app('user-website')->id, $value);
    }

    /**
     * Remove one or many items from the session.
     *
     * @param string|array $keys
     * @return void
     * @static
     */
    public static function forget($keys)
    {
        \Session::forget($keys . '_' .app('user-website')->id);
    }
}