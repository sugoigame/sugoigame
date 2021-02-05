<?php
/**
 * Used as a wrapper for prepared statements after they're executed.
 */
class mywrap_result {

  private $bound_variables;
  private $results;
  private $statement;

  /**
   * Constructor -
   * Creates a result object from a prepared statement
   */
  public function __construct($statement) {
    $this->statement       = $statement;
    $this->bound_variables = array();
    $this->results         = array();
    $this->columns         = array();
    $meta                  = $this->statement->result_metadata();
    if ($meta) {
      while ($column = $meta->fetch_field()) {
        if (isset($this->columns[$column->name])) {
          $this->columns[$column->name . '_copy'] = $column;
          $this->bound_variables[$column->name . '_copy'] =& $this->results[$column->name . '_copy'];
        } else {
          $this->columns[$column->name] = $column;
          $this->bound_variables[$column->name] =& $this->results[$column->name];
        }
      }
      call_user_func_array(array($this->statement, 'bind_result'), $this->bound_variables);
      $meta->close();
    }
}

  /**
   * Destructor -
   * called as soon as there are no references left to this object
   * closes the mysqli statement
   */
  public function __destruct() {
    $this->statement->close();
  }

  /**
   * Returns the original statement object
   */
  public function get_statement() {
    return $this->statement;
  }

  /**
   * count the number of rows in the result
   */
  public function count() {
    return $this->statement->num_rows();
  }

  /**
   * get the column names for this result
   */
  public function columns() {
    return $this->columns;
  }

  /**
   * get the number of rows affected by this prepared statement
   */
  public function affected_rows() {
    return $this->statement->affected_rows;
  }

  /**
   * Fetch result while it can, returns false when finished
   */
  public function fetch() {
    return $this->statement->fetch() ? $this->results : false;
  }

  /**
   * Fetch a result row as an associative array, returns false when finished
   */
  public function fetch_array() {
    $results = $this->fetch();
    if ($results) {
      $row = array();
      foreach($results as $key=>$value) {
        $row[$key] = $value;
      }
      return $row;
    }
    return false;
  }

  /**
   * Fetch all results in a multi-level array
   */
  public function fetch_all_array() {
    /* return cached version if exists */
    if (isset($this->cached)) return $this->cached;

    $results = array();
    while ($result = $this->fetch_array()) {
      array_push($results, $result);
    }
    $this->cached = $results;
    return $results;
  }

  /**
   * retrieve the last id that was inserted
   */
  public function last_id() {
    return $this->statement->insert_id;
  }
}