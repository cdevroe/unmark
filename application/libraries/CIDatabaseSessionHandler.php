<?php
class CIDatabaseSessionHandler implements SessionHandlerInterface {
    
    private $db;
    
    private $dbTable = 'ci_sessions';
    private $idColumn = 'session_id';
    private $dataColumn = 'session_data';
    
    /*
     * Table structure
     CREATE TABLE IF NOT EXISTS  `ci_sessions` (
	       session_id varchar(40) DEFAULT '0' NOT NULL,
	       session_data text NOT NULL,
	       PRIMARY KEY (session_id)
     ); 
     */
    
    /**
     * Creates session handler.
     * Requires CodeIgniter database library object reference
     * @param unknown $CodeIgniter
     */
    public function __construct(& $db){
        $this->db = $db;
    }
    
    public function close(){
        $this->_log('Close called');
    }
    
    public function destroy ( $session_id ){
        $this->_log('Destroy called');
    }
    
    public function gc ( $maxlifetime ){
        $this->_log('GC called');
    }
    
    public function open ( $save_path , $name ){
        $this->_log('Open called');
        return true;
    }
    
    public function read ( $session_id ){
         $this->_log('Read called');
         $session = $this->db->get_where($this->dbTable, array($this->idColumn => $session_id));
         $this->_log('Session read: '.print_r($session, true));
         if (isset($session) && $session->num_rows() > 0){
             $row = $session->row();
             $this->_log('Session query result: ' . print_r($row, true));
             return $row->{$this->dataColumn};
         }
        return null;
    }
    
    public function write ( $session_id , $session_data ){
        $this->_log('Write called');
        $session = $this->db->get_where($this->dbTable, array($this->idColumn => $session_id));
        if ($this->read($session_id) !== null){
             $this->db->where($this->idColumn, $session_id);
             $this->db->update($this->dbTable, array($this->dataColumn => $session_data));
        } else{
            $this->db->insert($this->dbTable, array($this->idColumn => $session_id, $this->dataColumn => $session_data));
        }
    }
    
    private function _log($message, $level = 'debug'){
        log_message($level, '[CIDatabaseSessionHandler] '.$message);
    }
}