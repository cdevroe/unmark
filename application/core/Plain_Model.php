<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plain_Model extends CI_Model
{
    // Public properties
    public $CI              = null;
    public $data_types      = array();
    public $sort            = null;
    public $sort_options    = array();
    public $table           = null;

    // Protected properties
    protected $delimiter    = '~*~';
    protected $dont_cache   = false;
    protected $id_column    = null;
    protected $read_method  = false;

    public function __construct()
    {
        parent::__construct();

        // Reflect
        // Get constants
        $model             = new ReflectionClass($this);
        $constants         = $model->getConstants();
        $this->read_method = ($model->hasMethod('readComplete')) ? 'readComplete' : 'read';

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

    public function count($where=null, $join=null)
    {
        $where = (! empty($where)) ? ' WHERE ' . $where : null;
        $join  = (! empty($join)) ? ' ' . $join : null;

        $q = $this->db->query("
            SELECT
            COUNT(" . $this->table . '.' . $this->id_column . ") AS total
            FROM `" . $this->table . "`" . $join . $where
        );

        // Check for errors
        $this->sendException();

        $row = $q->row();
        return (integer) $row->{'total'};
    }

    public function delete($where)
    {
        return self::update($where, array('active'=>'0'));
    }

    protected function getCacheKey($query)
    {
        // Set the tables not to cache results for
        // If the current table is one of the list, return null
        $no_cache = array();
        if (in_array($this->table, $no_cache)) {
            return null;
        }

        // Get user ID, query
        // $_SESSION['user_id'] . '-' . md5($query);

        // Will add caching later
        return null;
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

    public function read($where, $limit=1, $page=1, $select='*')
    {
        $where      = (is_numeric($where)) ? $this->id_column . " = '$where'" : trim($where);
        $where      = (empty($where)) ? '1=1' : $where;
        $page       = (is_numeric($page) && $page > 0) ? $page : 1;
        $limit      = ((is_numeric($limit) && $limit > 0) || $limit == 'all') ? $limit : 1;
        $start      = $limit * ($page - 1);
        $q_limit    = ($limit != 'all') ? ' LIMIT ' . $start . ',' . $limit : null;
        $sort       = (! empty($this->sort)) ? ' ORDER BY ' . $this->sort : null;

        $query     = "SELECT " . $select . " FROM `" . $this->table . "` WHERE " . $where . $sort . $q_limit;
        $cache_key = $this->getCacheKey($query);
        $data      = $this->cache->read($cache_key);

        if (! empty($data)) {
            return unserialize($data);
        }
        else {
            $q = $this->db->query($query);

            // Check for errors
            $this->sendException();

            if ($q->num_rows() <= 0) {
                return false;
            }

            $result = ($limit == 1) ? $q->row() : (array) $q->result();
            if ($this->dont_cache === false) {
                $this->cache->add($cache_key, serialize($result), true);
            }
            else {
                $this->dont_cache = false;
            }
            return $result;
        }
    }

    protected function removeCacheKey($key, $single=false)
    {
        // If single, delete only single entry
        if ($single === true) {
            $this->cache->delete($key);
        }
        // else, delete all entries for the domain token
        else {
            $tmp = explode('-', $key);
            if (isset($tmp[0]) && ! empty($tmp[0])) {
                $this->cache->deleteAll($tmp[0] . '*');
            }
        }
    }

    protected function sendException()
    {
        /*$err_msg = $this->db->_error_message();

        // Exceptional!
        if (! empty($err_msg)) {
            $query  = $this->db->last_query();
            $err_no = $this->db->_error_number();

            // Remove column information we don't want logged
            $columns = array('session_id', '.*?_token', 'password', 'email', 'config', 'transaction_data', 'data');
            foreach ($columns as $column) {
                $query = preg_replace('/(' . $column . '\s*?=\s*?("|\'))(.*?)\\2/ism', "$1***$2", $query);
            }

            // Send exception
            $this->exceptional->createTrace(E_ERROR, 'Database Error (' . $err_no . ')', __FILE__, __LINE__, array(
                'query'     => $query,
                'message'   => $err_msg,
                'error-num' => $err_no
            ));
        }*/
        // Not used yet
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
                $this->dont_cache = true;
                $method = $this->read_method;
                return $this->{$method}($where);
            }
            else {
                return $this->formatErrors('Eek this is akward, sorry. Something went wrong. Please try again.');
            }
        }

        return $this->formatErrors($valid);
    }
}