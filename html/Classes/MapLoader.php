<?php

class MapLoader {
	private static $cache = array();

	public static function load($data) {
		if (!isset(MapLoader::$cache[$data])) {
			MapLoader::$cache[$data] = MapLoader::load_file(dirname(__FILE__) . "/../Data/" . $data . ".json");
		}
		return MapLoader::$cache[$data];
	}

	private static function load_file($file_name) {
		$file = fopen($file_name, "r");
		$lines = "";
		while ($line = fgets($file)) {
			$lines .= $line;
		}
		fclose($file);
		return strlen($lines) ? json_decode($lines, true) : array();
	}

	public static function save($map, $data) {
		$file = fopen(dirname(__FILE__) . "/../Data/" . $data . ".json", "w");
		fwrite($file, json_encode($map));
		fclose($file);
	}
}