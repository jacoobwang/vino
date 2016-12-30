<?php

namespace Mphp;

class Config implements IConfig
{
    private $config_info = array();
    private $config_dir = '';


    public function __construct($dir)
    {
        if (!file_exists($dir)) {
            throw ExceptionHelper::create('Config directory not found: '.$dir, 123104);
        }
        $this->config_dir = $dir;
    }


    public function get($key, $default=null)
    {
        if (!is_array($key)) {
            $key = explode('/', $key);
        }

        $i = 0;
        $cfg = null;
        foreach($key as $k) {
            if (0 === $i) {
                if (!array_key_exists($k, $this->config_info)) {
                    $f = $this->config_dir . '/' . "$k.php";
                    if (!file_exists($f)) {
                        if ($default !== null) {
                            return $default;
                        }
                        throw ExceptionHelper::create('Config file is missed', 316882);
                    }
                    $this->config_info[$k] = include($f);
                }
                $cfg = $this->config_info[$k];
            } else {
                if (!array_key_exists($k, $cfg)) {
                    if ($default !== null) {
                        return $default;
                    }
                    throw ExceptionHelper::create('Config file is missed', 368722);
                }
                $cfg = $cfg[$k];
            }
            ++$i;
        }
        return $cfg;
    }


}
