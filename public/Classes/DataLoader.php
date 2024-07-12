<?php

class DataLoader
{
    private static $cache = array();

    public static function load($data)
    {
        if (! isset(DataLoader::$cache[$data])) {
            DataLoader::$cache[$data] = \Classes\Spyc::YAMLLoad(dirname(__FILE__) . "/../Data/" . $data . ".yaml");
        }
        return DataLoader::$cache[$data];
    }
    public static function filter($map, $func)
    {
        $data = DataLoader::load($map);
        return array_filter($data, $func);
    }
    public static function find($map, $search)
    {
        $data = DataLoader::load($map);
        return array_find($data, $search);
    }
}
