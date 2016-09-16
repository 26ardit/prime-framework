<?php namespace core;

use PDO;

class DB
{
    private static $_instance = null;

    private $_pdo;
    private $_query;
    private $_table;
    private $_params = [];
    private $_where;
    private $_results;
    private $_error = false;
    private $_count = 0;

    public function __construct()
    {
        $db       = Config::get('mysql:db');
        $host     = Config::get('mysql:host');
        $user     = Config::get('mysql:username');
        $password = Config::get('mysql:password');

        if (!empty($this->table)) {
            $this->_table = $this->table;
        } else {
            $called_class = get_called_class();
            $table = strtolower(pathinfo($called_class)['basename']);

            $this->_table = "{$table}s";
        }

        try {
            $this->_pdo = new PDO("mysql:host={$host};dbname={$db}", $user, $password);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function run()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        
        return self::$_instance;
    }

    public function query($sql, $params = [])
    {
        $this->_error = false;
        $this->_query = $this->_pdo->prepare($sql);
        
        if ($this->_query->execute($params)) {
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        } else {
            $this->_error = true;
        }

        return $this;
    }

    public function where(array $where)
    {
        $operators = ['=', '>', '<', '>=', '<=', '!=', 'LIKE'];
        
        $field = $where[0];

        if (count($where) === 2) {
            $operator = '=';
            $value = $where[1];
        } else {
            $operator = $where[1];
            $value = $where[2];
        }
        
        if (in_array($operator, $operators)) {
            $this->_params[$field] = $value;

            if (empty($this->_where)) {
                $this->_where = "WHERE {$field} {$operator} :{$field}";
            } else {
                $this->_where .= " AND {$field} {$operator} :{$field}";
            }
        }

        return $this;
    }

    public function get($query = '')
    {
        $sql = "SELECT * FROM {$this->_table} {$this->_where} {$query}";
        $this->query($sql, $this->_params);
        
        return $this->_results;
    }

    public function delete()
    {
        $sql = "DELETE FROM {$this->_table} {$this->_where}";

        if (!$this->query($sql)->error()) {
            return true;
        }

        return false;
    }

    public function insert(array $fields)
    {
        if (count($fields)) {
            
            $keys = array_keys($fields);

            foreach ($keys as $key) {
                $values[] = ":{$key}";
            }
            
            $index = implode(', ', $keys);
            $value = implode(', ', $values);

            $sql = "INSERT INTO {$this->_table} ({$index}) VALUES ({$value})";

            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }

        return false;
    }

    public function update($fields)
    {
        if (count($fields)) {

            foreach ($fields as $key => $field) {
                $values[] = "{$key} = :{$key}";
            }

            $values = implode(', ', $values);

            $sql = "UPDATE {$this->_table} SET {$values} {$this->_where}";
            $fields = array_merge($fields, $this->_params);

            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }

        return false;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->_table} WHERE id = :id";
        $this->query($sql, ['id' => $id]);

        return $this->first();
    }

    public function first()
    {
        $this->get();
        
        return isset($this->_results[0]) ? $this->_results[0] : null;
    }

    public function count()
    {
        $this->get();

        return $this->_count;
    }

    public function error()
    {
        return $this->_error;
    }
}
