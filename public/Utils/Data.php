<?php
namespace Utils;

class Data
{
    private static $cache = array();

    public static function load($data)
    {
        if (! isset(self::$cache[$data])) {
            $file_name = dirname(__FILE__) . "/../Data/" . $data;
            $file_yaml = $file_name . ".yaml";
            $file_json = $file_name . ".json";

            if (file_exists($file_yaml)) {
                self::$cache[$data] = self::load_yaml($file_yaml);
            } else {
                self::$cache[$data] = self::load_json($file_json);
            }
        }
        return self::$cache[$data];
    }

    private static function load_yaml($file_name)
    {
        return \Classes\Spyc::YAMLLoad($file_name);
    }

    private static function load_json($file_name)
    {
        $file = fopen($file_name, "r");
        $lines = "";
        while ($line = fgets($file)) {
            $lines .= $line;
        }
        fclose($file);
        return strlen($lines) ? json_decode($lines, true) : [];
    }

    public static function filter($map, $func)
    {
        $data = self::load($map);
        return array_filter($data, $func);
    }

    public static function find($map, $search)
    {
        $data = self::load($map);
        return array_find($data, $search);
    }

    public static function find_inside($map, $key, $search)
    {
        $data = self::load($map);
        return array_find($data[$key], $search);
    }
}
