<?php

class MysqlAdapter
{
    protected $_config = array();
    protected $_link;
    protected $_result;

    public function __construct(array $config)
    {
        if (count($config) !== 4) {
            throw new \http\Exception\InvalidArgumentException("Invalid DB Configuration Array");
        }

        $this->_config = $config;
    }

    /**
     * Connect to Mysql
     */
    public function connect()
    {
        if ($this->_link === null) {
            list($host, $username, $password, $database) = $this->_config;
            if (!$this->_link = @mysqli_connect($host, $username, $password, $database)) {
                throw new \http\Exception\RuntimeException("Unable to connect to database : " . mysqli_connect_error());
            }
            unset($host, $username, $password, $database);
        }
        return $this->_link;
    }

    /**
     * Execute Query
     */
    public function query($query)
    {
        if (!is_string($query) || empty($query)) {
            throw new \http\Exception\InvalidArgumentException("Invalid Query");
        }
        // lazy connect to database
        $this->connect();
        if (!$this->_result = mysqli_query($this->_link, $query)) {
            throw new \http\Exception\RuntimeException("Unable to execute query : " . $query . " - " . mysqli_error($this->_link));
        }
        return $this->_result;
    }

    /**
     * Perform a select statement on the database
     */
    public function select($table, $where = '', $fields = '*', $order = '', $limit = '', $offset = '')
    {
        $query = 'select ' . $fields . ' from ' . $table
            . (($where) ? ' where ' . $where : '')
            . (($limit) ? ' limit ' . $limit : '')
            . (($offset && $limit) ? ' offset ' . $offset : '')
            . (($order) ? ' order by ' . $order : '');
        $this->query($query);
        return $this->countRows();
    }

    /**
     * Perform an insert statement on the database
     */
    public function insert($table, array $data)
    {
        if (empty($data)) {
            throw new \http\Exception\InvalidArgumentException("Invalid Data");
        }
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(array($this, 'quoteValue'), array_values($data)));
        $query = 'insert into ' . $table . ' (' . $fields . ') values (' . $values . ')';
        $this->query($query);
        return $this->getInsertId();
    }

    /**
     * Perform an update statement on the database
     */
    public function update($table, array $data, $where = '')
    {
        if (empty($data)) {
            throw new \http\Exception\InvalidArgumentException("Invalid Data");
        }
        $set = array();
        foreach ($data as $field => $value) {
            $set[] = $field . '=' . $this->quoteValue($value);
        }
        $query = 'update ' . $table . ' set ' . implode(',', $set)
            . (($where) ? ' where ' . $where : '');
        $this->query($query);
        return $this->getAffectedRows();
    }

    /**
     * Perform a delete statement on the database
     */
    public function delete($table, $where = '')
    {
        $query = 'delete from ' . $table
            . (($where) ? ' where ' . $where : '');
        $this->query($query);
        return $this->getAffectedRows();
    }

    /**
     * Fetch a row from the result set as an associative array
     */
    public function fetch(){
        if($this->_result){
            if(($row = mysqli_fetch_array($this->_result,MYSQLI_ASSOC) === false)){
                $this->freeResult();
            }
            return $row;
        }
        return false;
    }

    /**
     * Fetch all rows from the result set as an associative array
     */
    public function fetchAll(){
        if($this->_result !== null) {
            if(($all = mysqli_fetch_all($this->_result,MYSQLI_ASSOC) === false)){
                $this->freeResult();
            }
            return $all;
        }
        return [];
    }

    /**
     * Get the number of rows in the result set
     */
    public function countRows()
    {
        return $this->_result !== null
            ? mysqli_num_rows($this->_result)
            : 0;
    }

    /**
     * Get the number of affected rows
     */
    public function getAffectedRows()
    {
        return $this->_link !== null ? mysqli_affected_rows($this->_link):0;
    }

    /**
     * Escape the specified value
     */
    public function quoteValue($value)
    {
        $this->connect();
        if ($value === null) {
            return 'null';
        }
        if (!is_numeric($value)) {
            return "'" . mysqli_real_escape_string($this->_link, $value) . "'";
        }
        return $value;
    }

    /**
     *  Get the last insert id
     */
    public function getInsertId()
    {
        return $this->_link !== null
            ? mysqli_insert_id($this->_link)
            : null;
    }

    public function freeResult(){
        if($this->_result){
            mysqli_free_result($this->_result);
        }
        return false;
    }

    /**
     * Close explicitly the connection
     */
    public function disconnect()
    {
        if ($this->_link !== null) {
            @mysqli_close($this->_link);
            $this->_link = null;
            return true;
        }
        return false;
    }

    /**
     * Close automatically the connection when the object is destroyed
     */
    public function __destruct()
    {
        $this->disconnect();
    }

}

?>
