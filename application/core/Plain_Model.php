<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Model extends CI_Model
{

    const SELECT_ALL = '*';

    // Public properties
    public $data_types      = array();
    public $sort            = null;
    public $sort_options    = array();
    public $table           = null;

    // Protected properties
    protected $cache_id     = 'unmark-';
    protected $db_error     = false;
    protected $delimiter    = '~*~';
    protected $dont_cache   = false;
    protected $id_column    = null;
    protected $num_rows     = 0;
    protected $read_method  = 'read';

    public function __construct()
    {
        parent::__construct();

        // Reflect
        // Get constants
        $model             = new ReflectionClass($this);
        $constants         = $model->getConstants();

        // Figure the correct table to use
        // If table constant is found, set it
        // Otherwise find it
        if (isset($constants['TABLE']) && ! empty($constants['TABLE'])) {
            $this->table = $constants['TABLE'];
        }
        else {
            $model        = (isset($model->name)) ? str_replace('_model', '', strtolower($model->name)) : null;
            $this->table  = (! is_null($model) && $model != 'plain') ? $model : $this->table;
        }

        // Figure the id column for the table
        $this->id_column    = (substr($this->table, strlen($this->table)-3, 3) == 'ies') ? strtolower(substr($this->table, 0, strlen($this->table) - 3)) . 'y' : strtolower($this->table);
        $this->id_column    = (substr($this->table, strlen($this->table)-1, 1) == 's') ? strtolower(substr($this->table, 0, strlen($this->table) - 1)) : strtolower($this->table);
        $this->id_column    .= '_id';

        // Reset don't cache
        $this->dont_cache = false;
    }

    public function checkForHit($query)
    {
        if ( strpos($query,'COUNT') === false ) {
            // print_r($query);
            // exit;
        }
        $cache_key      = $this->getCacheKey($query);
        $data           = $this->plain_cache->read($cache_key);
        $this->num_rows = 0;

        if ( strpos($query,'COUNT') === false ) {
            if ( ! empty($data) ) {
                // print_r($data);
                // exit;
            }
        }

        //$this->plain_cache->delete($cache_key);


        //print $cache_key . PHP_EOL;
        // If data is found, just return it
        if (! empty($data)) {
            $data           = unserialize($data);
            $this->num_rows = (is_array($data)) ? count($data) : 1;
            //print 'CACHED' . PHP_EOL . PHP_EOL;
            return $data;
        }

        // Cache miss, hit DB
        $q = $this->db->query($query);
        //print 'NOT CACHED' . PHP_EOL . PHP_EOL;

        if ( strpos($query,'COUNT') === false ) {
            // print_r($q);
            // exit;
        }

        // Check for errors
        $this->sendException();

        // if ( strpos($query,'COUNT') === false ) {
        //     print_r($this->db->error());
        // }

        // If no DB error, ready results and add to cache
        if ($this->db_error == false) {
            $this->num_rows = $q->num_rows();
            $result         = $this->stripSlashes($q->result());

            if ($this->dont_cache === false) {
                $this->plain_cache->add($cache_key, serialize($result), true);
            }
        }
        else {
            $result = array();
        }

        if ( strpos($query,'COUNT') === false ) {
            // print_r($result);
            // exit;
        }

        // Return result
        return $result;
    }

    public function count($where=null, $join=null)
    {
        $where  = (! empty($where)) ? ' WHERE ' . $where : null;
        $join   = (! empty($join)) ? ' ' . $join : null;
        $result = $this->checkForHit("
            SELECT
            COUNT(" . $this->table . '.' . $this->id_column . ") AS total
            FROM `" . $this->table . "`" . $join . $where
        );

        return (isset($result[0]->total)) ? (integer) $result[0]->total : 0;
    }

    public function delete($where)
    {
        return self::update($where, array('active'=>'0'));
    }

    protected function getCacheKey($query)
    {
        // Set the tables not to cache results for
        // If the current table is one of the list, return null
        $no_cache = array('marks', 'plain_sessions', 'tags', 'tokens');
        if (in_array($this->table, $no_cache)) {
            return null;
        }

        $id = null;
        if ($this->table == 'labels') {
            $id = (stristr($query, 'user_id IS NULL') && stristr($query, 'smart_key IS NULL')) ? 'labels-system' : null;
            $id = (empty($id) && stristr($query, 'user_id IS NULL')) ? 'labels-smart' : $id;
        }
        elseif ($this->table == 'tags') {
            $id = 'tags';
        }

        $id = (empty($id)) ? $this->getCacheID($query, 'user_id') : $id;

        // If user id is found, set cache key
        if (! empty($id)) {
            return $this->cache_id . $id . '-' . md5($query);
        }

        // Return null by default
        return null;
    }

    private function getCacheID($query, $column)
    {
        // Extract the value sent
        // If not found, return null
        preg_match('/.*?WHERE.*?' . $column . '\)?.*?=.*?(\'|")(.*?)\\1.*?/im', $query, $m);
        return (isset($m[2]) && ! empty($m[2])) ? $m[2] : null;
    }

    public function getTotals($where, $page, $limit, $data=array(), $join=null)
    {
        $total            = $this->count($where, $join);
        $total_pages      = ($total > 0) ? ceil($total / $limit) : 0;
        $data['total']    = $total;
        $data['page']     = $page;
        $data['per_page'] = $limit;
        $data['pages']    = $total_pages;

        return $data;
    }

    public function read($where, $limit=1, $page=1, $select=self::SELECT_ALL, $sort = null)
    {
        $where      = (is_numeric($where)) ? $this->id_column . " = '$where'" : trim($where);
        $where      = (empty($where)) ? '1=1' : $where;
        $page       = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit      = ((is_numeric($limit) && $limit > 0) || $limit == 'all') ? $limit : 1;
        if ( is_numeric($limit)) {
            $start      = $limit * ($page - 1);
        }
        $q_limit    = ($limit != 'all') ? ' LIMIT ' . $start . ',' . $limit : null;
        $sortSelected = (empty($sort) ? $this->sort : $sort );
        $sort       = (! empty($sortSelected)) ? ' ORDER BY ' . $sortSelected : null;
        $result     = $this->checkForHit("SELECT " . $select . " FROM `" . $this->table . "` WHERE " . $where . $sort . $q_limit);

        if ($this->num_rows < 1) {
            return false;
        }

        return ($limit == 1) ? $result[0] : $result;
    }

    protected function removeCacheKey($key)
    {
        if (substr_count($key, '-') >= 2) {
            $tmp = explode('-', $key);
            $key = $tmp[0] . '-' . $tmp[1] . '-*';
        }
        $this->plain_cache->delete($key);
    }

    protected function sendException()
    {
        $err            = $this->db->error(); // Changed in CI 3.x
        $this->db_error = false;

        // Exceptional!
        if ( is_array($err) && $err['message'] != '' ) {
            $query          = $this->db->last_query();
            $err_no         = $err['code'];
            $err_msg        = $err['message'];
            // Taken out in CI 3.x $err_no         = $this->db->_error_number();
            $this->db_error = true;

            // Remove column information we don't want logged
            $columns = array('session_id', '.*?_token', 'password', 'email', 'session_data');
            foreach ($columns as $column) {
                $query = preg_replace('/(' . $column . '\s*?=\s*?("|\'))(.*?)\\2/ism', "$1***$2", $query);
            }

            // Send exception
            $this->exceptional->createTrace(E_ERROR, 'Database Error (' . $err_no . ')', __FILE__, __LINE__, array(
                'query'     => $query,
                'message'   => $err_msg,
                'error-num' => $err_no
            ));
        }
    }

    public function stripSlashes($result)
    {
        if (! is_array($result) && ! is_object($result)) {
            $result = (is_string($result)) ? stripslashes($result) : $result;
        }
        else {
            foreach ($result as $k => $row) {
                if (is_array($result)) {
                    foreach ($row as $key => $value) {
                        $result[$k]->{$key} = (is_string($value)) ? stripslashes($value) : $value;
                    }
                }
                else {
                    $result->{$k} = (is_string($row)) ? stripslashes($row) : $row;
                }
            }
        }

        return $result;
    }


    public function update($where, $options=array())
    {

        $where    = (is_numeric($where)) ? $this->id_column . " = '$where'" : trim($where);
        $valid    = validate($options, $this->data_types);

        if ($valid === true) {
            $q   = $this->db->update_string($this->table, $options, $where);
            $res = $this->db->query($q);

            // Check for errors
            $this->sendException();

            if ($res) {
                $cache_key = $this->getCacheKey($q);
                $this->removeCacheKey($cache_key);
                //$this->dont_cache = true;
                $method = $this->read_method;
                return $this->{$method}($where);
            }
            else {
                return formatErrors(500);
            }
        }

        return formatErrors($valid);
    }
}