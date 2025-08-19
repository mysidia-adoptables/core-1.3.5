<?php

use Resource\Native\Objective;
use Resource\Native\MysString;
use Resource\Collection\LinkedList;
use Resource\Collection\LinkedHashMap;

/**
 * The Database Class, extending from the PDO class and implementing Objective interface
 * It adds new features beyond PDO's capability, and implements the object's interface to be used in Collections.
 * @category Resource
 * @package Core
 * @author Fadillzzz
 * @copyright Mysidia Adoptables Script
 * @link http://www.mysidiaadoptables.com
 * @since 1.3.2
 * @todo Not much at this point.
 *
 */

class Database extends PDO implements Objective
{
    /**
     * Keep track of total rows from each query
     *
     * @access private
     * @var array
     */
    private $_total_rows = [];

    /**
     * Stores join table
     *
     * @access private
     * @var array
     */
    private $_joins = [];

    /**
     * If you don't know what this is, you shouldn't be here
     *
     * @param MysString $dbname
     * @param MysString $host
     * @param MysString $user
     * @param MysString $password
     * @param MysString $_prefix Tables' prefix
     * @access public
     */
    public function __construct($dbname, $host, $user, $password, /**
     * Tables' prefix
     *
     * @access private
     */
        private $_prefix = 'adopts_')
    {
        parent::__construct('mysql:host=' . $host . ';dbname=' . $dbname, $user, $password);
    }

    /**
     * The equals method, checks whether target object is equivalent to this one.
     * @param Objective  $object
     * @access public
     * @return Boolean
     */
    public function equals(Objective $object)
    {
        return ($this == $object);
    }

    /**
     * The getClassName method, returns class name of an instance.
     * @access public
     * @return MysString
     */
    public function getClassName()
    {
        return new MysString(static::class);
    }

    /**
     * The hashCode method, returns the hash code for the very Database.
     * @access public
     * @return Int
     */
    public function hashCode()
    {
        return hexdec(spl_object_hash($this));
    }

    /**
     * The serialize method, serializes this Database Object into string format.
     * @access public
     * @return MysString
     */
    public function serialize()
    {
        return serialize($this);
    }

    /**
     * The unserialize method, decode a string to its object representation.
     * @param MysString  $string
     * @access public
     * @return MysString
     */
    public function unserialize($string)
    {
        return unserialize($string);
    }

    /**
     * Basic INSERT operation
     *
     * @param MysString $tableName
     * @param array  $data         A key-value pair with keys that correspond to the fields of the table
     * @access public
     * @return object
     */
    public function insert($tableName, array $data)
    {
        return $this->_query($tableName, $data, 'insert');
    }

    /**
     * Basic UPDATE operation
     *
     * @param MysString $tableName
     * @param array  $data         A key-value pair with keys that correspond to the fields of the table
     * @access public
     * @return object
     */
    public function update($tableName, array $data, $clause = null)
    {
        return $this->_query($tableName, $data, 'update', $clause);
    }

    /**
     * Basic SELECT operation
     *
     * @param MysString $tableName
     * @param array  $data        A key-value pair with values that correspond to the fields of the table
     * @param MysString $clause    Clauses for creating advance queries with JOINs, WHERE conditions, and whatnot
     * @access public
     * @return object
     */
    public function select($tableName, array $data = [], $clause = null)
    {
        return $this->_query($tableName, $data, 'select', $clause);
    }

    /**
     * Basic DELETE operation
     *
     * @param MysString $tableName
     * @param MysString $clause    Clauses for creating advance queries with JOINs, WHERE conditions, and whatnot
     * @access public
     * @return object
     */
    public function delete($tableName, $clause = null)
    {
        return $this->_query($tableName, [], 'delete', $clause);
    }

    /**
     * Adds JOIN to the next SELECT operation
     *
     * @param MysString $tableName
     * @param MysString $cond
     * @access public
     * @return object
     */
    public function join($tableName, $cond)
    {
        $this->_joins[] = [$tableName, $cond];
        return $this;
    }

    /**
     * Get total rows affected by previous queries
     *
     * @param int    $index
     * @return int
     */
    public function get_total_rows($index)
    {
        if ($index < 0) {
            return $this->_total_rows[count($this->_total_rows) + $index];
        }
        return $this->_total_rows[$index];
    }

    /**
     * Handles queries
     *
     * @param MysString $tableName
     * @param array  $data         A key-value pair with keys that correspond to the fields of the table
     * @param MysString $operation Defines what kind of operation we'll carry on with the database
     * @access private
     * @return object
     */
    private function _query($tableName, array $data, $operation, $clause = null)
    {
        if (! is_string($tableName)) {
            throw new Exception('Argument 1 to ' . self::class . '::' . __METHOD__ . ' must be a string');
        }

        if (! in_array($operation, ['insert', 'update', 'select', 'delete'])) {
            throw new Exception('Unknown database operation.');
        }

        $query = call_user_func_array([&$this, '_' . $operation . '_query'], [$tableName, &$data]);

        if (! empty($clause)) {
            $query .= ' WHERE ' . $clause;
        }
        //The comments can be removed for debugging purposes.
        //echo $query;
        $stmt = $this->prepare($query);
        $this->_bind_data($stmt, $data);

        if (! $stmt->execute()) {
            $error = $stmt->errorInfo();
            throw new Exception('Database error ' . $error[1] . ' - ' . $error[2]);
        }

        $this->_total_rows[] = $stmt->rowCount();
        return $stmt;
    }

    /**
     * Generates prepared INSERT query string
     *
     * @param MysString $tableName
     * @param array  $data         A key-value pair with keys that correspond to the fields of the table
     * @access private
     * @return MysString
     */
    private function _insert_query($tableName, &$data)
    {
        $tableFields = array_keys($data);
        return 'INSERT INTO ' . $this->_prefix . $tableName . ' 
                  (`' . implode('`, `', $tableFields) . '`) 
                  VALUES (:' . implode(', :', $tableFields) . ')';
    }

    /**
     * Generates prepared UPDATE query string
     *
     * @param MysString $tableName
     * @param array  $data         A key-value pair with keys that correspond to the fields of the table
     * @access private
     * @return MysString
     */
    private function _update_query($tableName, &$data)
    {
        $setQuery = [];
        foreach ($data as $field => &$value) {
            $setQuery[] = '`' . $field . '` = :' . $field;
        }
        return 'UPDATE ' . $this->_prefix . $tableName . '
                  SET ' . implode(', ', $setQuery);
    }

    /**
     * Generates prepared SELECT query string
     *
     * @param MysString $tableName
     * @param array  $data         A key-value pair with values that correspond to the fields of the table
     * @access private
     * @return MysString
     */
    private function _select_query($tableName, &$data)
    {
        $joins = '';
        if (! empty($this->_joins)) {
            foreach ($this->_joins as $k => &$join) {
                $exploded = explode('=', (string) $join[1]);
                $join_cond = '`' . $this->_prefix . implode('`.`', explode('.', trim($exploded[0]))) . '` = `' . $this->_prefix . implode('`.`', explode('.', trim($exploded[1]))) . '`';
                $joins .= ' INNER JOIN `' . $this->_prefix . $join[0] . '` ON ' . $join_cond;
            }
            $this->_joins = null;
            $this->_joins = [];
        }
        $fields = empty($data) ? '*' : '`' . implode('`, `', array_values($data)) . '`';
        return 'SELECT ' . $fields . '
                  FROM `' . $this->_prefix . $tableName . '`' . $joins;
    }

    /**
     * Generates prepared DELETE query string
     *
     * @param MysString $tableName
     * @access private
     * @return MysString
     */
    private function _delete_query($tableName)
    {
        return 'DELETE FROM `' . $this->_prefix . $tableName . '`';
    }

    /**
     * Binds data to the prepared statement
     *
     * @param object $stmt A PDOStatement object
     * @param array  $data A key-value pair to be bound with the statement
     * @access private
     * @return object
     */
    private function _bind_data(&$stmt, &$data)
    {
        if (! empty($data)) {
            foreach ($data as $field => &$value) {
                $stmt->bindParam(':' . $field, $value);
            }
        }
        return $this;
    }

    /**
     * The fetchList method, fetches a LinkedList of column data.
     * @param PDOStatement  $stmt
     * @access public
     * @return LinkedList
     */
    public function fetchList(PDOStatement $stmt)
    {
        $list = new LinkedList();
        while ($field = $stmt->fetchColumn()) {
            $list->add(new MysString($field));
        }
        return $list;
    }

    /**
     * The fetchMap method, fetches a LinkedHashMap of column data.
     * @param PDOStatement  $stmt
     * @access public
     * @return LinkedHashMap
     */
    public function fetchMap(PDOStatement $stmt)
    {
        $map = new LinkedHashMap();
        while ($fields = $stmt->fetch(PDO::FETCH_NUM)) {
            if (count($fields) == 1) {
                $fields[1] = $fields[0];
            }
            $map->put(new MysString($fields[0]), new MysString($fields[1]));
        }
        return $map;
    }

    /**
     * Magic method __toString() for Database class, returns database information.
     * @access public
     * @return MysString
     */
    public function __toString(): MysString
    {
        return "Database Object.";
    }
}
