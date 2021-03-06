<?php
require_once dirname(__FILE__) . "//DB_Result.php";
class DB {
	private $instance;

	public function __construct() {
		if (!extension_loaded("mysqli"))
			trigger_error("You must have the mysqli extension installed.", E_USER_ERROR);
		else {
			$this->instance = @new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($this->instance->connect_errno)
				throw new Exception(sprintf("Connect Error (%s): %s", $this->instance->connect_errno, $this->instance->connect_error));

			$this->instance->set_charset('utf8');
			$this->instance = $this->instance;
		}
	}
	public function query($sql) {
		if(!($result = $this->instance->query($sql)))
			throw new Exception('query() failed: ' . $this->instance->error);

		return $result;
	}
	public function run($sql, $types = NULL, $params = NULL) {
		if ($stmnt = $this->instance->prepare($sql)) {
			if ($types && $params) {
				$refs	= [];
				$params	= is_array($params) ? array_merge([$types], $params) : array_merge([$types], [$params]);
				foreach($params as $key => $value) {
					$refs[$key]	= &$params[$key];
				}

				$bind = call_user_func_array([$stmnt, 'bind_param'], $refs);
				if ($bind === FALSE)
					throw new Exception('bind_param() failed: ' . $this->instance->error);
			}
			if ($stmnt->execute()) {
				$stmnt->store_result();
				$result = new DB_Result($stmnt);
				return $result;
			}
			throw new Exception('execute() failed: ' . $this->instance->error);
		}
		throw new Exception('prepare() failed: ' . $this->instance->error);
	}
	public function insertID() {
		return $this->instance->insert_id;
	}
	public function affectedRows() {
		return $this->instance->affected_rows;
	}
	public function __call($name, $args) {
		if (method_exists($this->instance, $name))
			return call_user_func_array([$this->instance, $name], $args);
		else {
			trigger_error('Unknown Method ' . $name . '()', E_USER_WARNING);
			return;
		}
    }
}