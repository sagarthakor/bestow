<?php
/**
 * Created by PhpStorm.
 * User: sid
 * Date: 14/7/18
 * Time: 6:47 PM
 */

namespace App\Library;


class Laravel
{
    /**
     * Update Laravel Env file Key's Value
     * @param string $key
     * @param string $value
     * @param mixed $type
     */
    public static function env($key, $value, $type=null)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);

            $oldValue = env($key);


            if ($type == 'string')
                $str = str_replace("{$key}='{$oldValue}'", "{$key}='{$value}'", $str);
            else
                $str = str_replace("{$key}={$oldValue}", "{$key}={$value}", $str);

            $fp = fopen($envFile, 'w');
            fwrite($fp, $str);
            fclose($fp);

        }
    }

    /**
     * Update Laravel Env file Key's Value
     * @param string $key
     * @param string $value
     */
    public static function projectConfig($key, $value)
    {
        $path = base_path('config/project.php');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                "'". $key ."' => '".config("project." . $key)."'",
                "'". $key ."' => '".$value."'",
                file_get_contents($path)
            ));
        }
    }
}