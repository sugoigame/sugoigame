<?php
class MapLoader
{
    private static $cache = [];

    public static function load($data)
    {
        if (! isset(MapLoader::$cache[$data])) {
            MapLoader::$cache[$data] = MapLoader::load_file(dirname(__FILE__) . "/../Data/" . $data . ".json");
        }
        return MapLoader::$cache[$data];
    }

    private static function load_file($file_name)
    {
        $file = fopen($file_name, "r");
        $lines = "";
        while ($line = fgets($file)) {
            $lines .= $line;
        }
        fclose($file);
        return strlen($lines) ? json_decode($lines, true) : [];
    }

    public static function save($data, $filename)
    {
        MapLoader::save_full_path($data, dirname(__FILE__) . "/../Data/" . $filename . ".json");
    }
    public static function save_full_path($data, $fullpath)
    {
        $file = fopen($fullpath, "w");
        fwrite($file, json_encode($data, JSON_PRETTY_PRINT));
        fclose($file);
    }
    public static function filter($map, $func)
    {
        $data = MapLoader::load($map);
        return array_filter($data, $func);
    }

    public static function find($map, $search)
    {
        $data = MapLoader::load($map);
        return array_find($data, $search);
    }
}
