<?php
require_once dirname(__FILE__) . "/Spyc.php";

class DataLoader {
	private static $cache = array();

	public static function load($data) {
		if (!isset(DataLoader::$cache[$data])) {
			DataLoader::$cache[$data] = Spyc::YAMLLoad(dirname(__FILE__) . "/../Data/" . $data . ".yaml");
		}
		return DataLoader::$cache[$data];
	}
}