<?php

/**
* @property CI_DB_query_builder $db
* @property CI_DB_forge $dbforge
* @property CI_Benchmark $benchmark
* @property CI_Calendar $calendar
* @property CI_Cart $cart
* @property CI_Config $config
* @property CI_Controller $controller
* @property CI_Email $email
* @property CI_Encrypt $encrypt
* @property CI_Exceptions $exceptions
* @property CI_Form_validation $form_validation
* @property CI_Ftp $ftp
* @property CI_Hooks $hooks
* @property CI_Image_lib $image_lib
* @property CI_Input $input
* @property CI_Language $language
* @property CI_Loader $load
* @property CI_Log $log
* @property CI_Model $model
* @property CI_Output $output
* @property CI_Pagination $pagination
* @property CI_Parser $parser
* @property CI_Profiler $profiler
* @property CI_Router $router
* @property CI_Session $session
* @property CI_Sha1 $sha1
* @property CI_Table $table
* @property CI_Trackback $trackback
* @property CI_Typography $typography
* @property CI_Unit_test $unit_test
* @property CI_Upload $upload
* @property CI_URI $uri
* @property CI_User_agent $user_agent
* @property CI_Validation $validation
* @property CI_Xmlrpc $xmlrpc
* @property CI_Xmlrpcs $xmlrpcs
* @property CI_Zip $zip
*/

class CI_Controller {};

/**
* @property CI_DB_query_builder $db
* @property CI_DB_forge $dbforge
* @property CI_Config $config
* @property CI_Loader $load
* @property CI_Session $session
*/

class CI_Model {};

class CI_DB_driver{

	/**
	 * Execute the query
	 *
	 * Accepts an SQL string as input and returns a result object upon
	 * successful execution of a "read" type query. Returns boolean TRUE
	 * upon successful execution of a "write" type query. Returns boolean
	 * FALSE upon failure, and if the $db_debug variable is set to TRUE
	 * will raise an error.
	 *
	 * @param	string	$sql
	 * @param	array	$binds = FALSE		An array of binding data
	 * @param	bool	$return_object = NULL
	 * @return	CI_DB_result
	 */
	public function query($sql, $binds = FALSE, $return_object = NULL);
};

class CI_DB_query_builder{

    /**
	 * Get
	 *
	 * Compiles the select statement based on the other functions called
	 * and runs the query
	 *
	 * @param	string	the table
	 * @param	string	the limit clause
	 * @param	string	the offset clause
	 * @return	CI_DB_result
	 */
    public function get($table = '', $limit = NULL, $offset = NULL);


	/**
	 * Get_Where
	 *
	 * Allows the where clause, limit and offset to be added directly
	 *
	 * @param	string	$table
	 * @param	string	$where
	 * @param	int	$limit
	 * @param	int	$offset
	 * @return	CI_DB_result
	 */
	public function get_where($table = '', $where = NULL, $limit = NULL, $offset = NULL);
}
