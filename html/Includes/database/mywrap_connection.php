<?php
/**
 * Interface for connecting to a database and doing queries, a wrapper for MySQLi
 */
final class mywrap_con {

  /* The mysql database connection information */
  private $DB_SERVER;
  private $DB_USER;
  private $DB_PASS;
  private $DB_NAME;

  /* The mysqli link */
  private $link;

  /**
   * Constructor -
   * opens a new database connection object
   * @param string DB_SERVER name of database server to connect to
   * @param string DB_USER name of database user
   * @param string DB_PASS password to database
   * @param string DB_NAME name of database
   */
  public function __construct($DB_SERVER = null, $DB_USER = null, $DB_PASS = null, $DB_NAME = null) {
    if ($DB_SERVER && $DB_USER && $DB_PASS && $DB_NAME) {
      $this->DB_SERVER = $DB_SERVER;
      $this->DB_USER   = $DB_USER;
      $this->DB_PASS   = $DB_PASS;
      $this->DB_NAME   = $DB_NAME;
    } else {
      $this->DB_SERVER = DB_SERVER;
      $this->DB_USER   = DB_USER;
      $this->DB_PASS   = DB_PASS;
      $this->DB_NAME   = DB_NAME;
    }
    $this->open();
  }

  /**
   * Destructor -
   * called as soon as there are no references left to this object
   * closes the mysqli connection
   */
  public function __destruct() {
    $this->close();
  }

  /**
   * Run a raw query on the database
   * It's suggested to use run() instead
   */
  public function query($sql) {
    return $this->link->query($sql);
  }

  /**
   * Get a prepared statement object from the database, suggested to use run() instead
   * @param string $statement a MySQL statement to run - use ?'s for parameters
   */
  public function prepare($statement) {
    return $this->link->prepare($statement);
  }

  /**
   * Bind arguments to a statement and execute the statement
   *
   * @param string statement a MySQL statement to run - use ?'s for parameters
   * @param string arg_types types of arguments
   *  i  for integer value
   *  s  for string value
   *  b  for blob values
   *  d  for double values
   * @param array||mixed params list of parameters to pass to bind_param
   */
  public function run($statement, $arg_types = null, $params = null) {
    if ($stmnt = $this->link->prepare($statement)) {

      if ($arg_types && $params) {
        $params = is_array($params) ? array_merge(array($arg_types), $params) : array_merge(array($arg_types), array($params));
        $refs   = array();
        foreach($params as $key => $value) {
          $refs[$key] = &$params[$key];
        }
        $bind = call_user_func_array(array($stmnt, 'bind_param'), $refs);
        if (false === $bind) {
          throw new Exception('bind_param() failed: ' . $this->link->error);
        }
      }
      if ($stmnt->execute()) {
        $stmnt->store_result();
        $result = new mywrap_result($stmnt);
        return $result;
      }
      throw new Exception('execute() failed: ' . htmlspecialchars($this->link->error));
    }
    throw new Exception('prepare() failed: ' . htmlspecialchars($this->link->error));
  }

  /**
   * retrieve the last id that was inserted
   */
  public function last_id() {
    return $this->link->insert_id;
  }

  /**
   * retrieve the number of affected rows on last INSERT, UPDATE, DELETE, or REPLACE query
   */
  public function affected_rows() {
    return $this->link->affected_rows;
  }

  /**
   * retrieve the last error code, if any
   */
  public function errno() {
    return $this->link->errno;
  }

  /**
   * close the connection
   * - This method is called on __destruct()
   * attempts to completely close it
   */
  public function close() {
    if ($this->link) {
      $this->link->kill($this->link->thread_id);
      $this->link->close();
      $this->link = null;
    }
    return $this;
  }

  /**
   * Open a connection to a database - if it needs to
   * @return $this - a connection, it will open a new one if it needs to
   */
  public function open() {
    if ($this->link == null) {
      $this->link = new mysqli($this->DB_SERVER, $this->DB_USER, $this->DB_PASS, $this->DB_NAME);
    }
    if ($this->link->connect_error) {
      die('Connect Error (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
    }
    return $this;
  }

  /**
   * get the actual mysqli link object
   */
  public function link() {
    return $this->link;
  }

  /**
   * Sanitize a numeric input
   * @param integer $number the integer you want sanitized
   */
  public function clean_int($integer) {
    return is_numeric($integer) ? intval($integer) : false;
  }

  /**
   * Sanitize a string input
   * @param integer $string the string you want sanitized
   */
  public function clean_str($string) {
    if ($this->link) {
      return mysqli_real_escape_string($this->link, $string);
    } else {
      $this->open();
      $str = mysqli_real_escape_string($this->link, $string);
      $this->close();
      return $str;
    }
  }

  /**
   * Count the number of rows in a result set
   * @param MYSQLi Result $result the result set to count
   */
  public function num_rows($result) {
    return $result ? mysqli_num_rows($result) : 0;
  }

  /**
   * Fetch array result set row, very useful for looping through a result set.
   * @param MYSQLi Result $result the result set to retrieve a row from
   */
  public function row($result) {
    return mysqli_fetch_array($result);
  }
}