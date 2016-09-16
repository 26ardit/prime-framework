<?php namespace core;

class Config
{
    public static function get($path)
    {
        global $config;

        $configs = $config;
        $paths = explode(':', $path);
        
        foreach ($paths as $path) {
            if (isset($configs[$path])) {
                $configs = $configs[$path];
            }
        }
        
        return $configs;
    }
}
