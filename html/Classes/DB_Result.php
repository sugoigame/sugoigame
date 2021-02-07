<?php
class DB_Result {
    private $bound_variables, $results, $statement;

    public function __construct($stmt) {
        $this->statement       = $stmt;
        $this->bound_variables = [];
        $this->results         = [];
        $this->columns         = [];

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
            call_user_func_array([$this->statement, 'bind_result'], $this->bound_variables);
            $meta->close();
        }
    }
    public function __destruct() {
        $this->statement->close();
    }
    public function getStatement() {
        return $this->statement;
    }
    public function count() {
        return $this->statement->num_rows();
    }
    public function columns() {
        return $this->columns;
    }
    public function affectedRows() {
        return $this->statement->affected_rows;
    }
    public function lastID() {
        return $this->statement->insert_id;
    }
    public function fetch() {
        return $this->statement->fetch() ? $this->results : FALSE;
    }
    public function getResult() {
        $results = $this->fetch();
        if ($results) {
            $row = new stdClass();
            foreach($results as $key=>$value)
                $row->$key = $value;

            return $row;
        }
        return FALSE;
    }
    public function getAllResults() {
        if (isset($this->cached))
            return $this->cached;
    
        $results = [];
        while ($result = $this->getResult())
          array_push($results, $result);

        $this->cached = $results;
        return $results;
    }
    public function getArrayResult() {
        $results = $this->fetch();
        if ($results) {
            $row = [];
            foreach($results as $key=>$value)
                $row[$key] = $value;

            return $row;
        }
        return FALSE;
    }
    public function getArrayAllResults() {
        if (isset($this->cached))
            return $this->cached;
    
        $results = [];
        while ($result = $this->getArrayResult())
          array_push($results, $result);

        $this->cached = $results;
        return $results;
    }
}