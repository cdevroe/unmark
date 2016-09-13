<?php
class CIDatabaseSessionHandler {

    private $CI;
    private $db;

    private $dbTable = 'plain_sessions';
    private $idColumn = 'session_id';
    private $dataColumn = 'session_data';

    /*
     * Table structure
     CREATE TABLE IF NOT EXISTS  `plain_sessions` (
	           session_id varchar(40) NOT NULL COMMENT 'Unique session identifier',
	           session_data text NOT NULL COMMENT 'Serialized session data',
               last_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last updated at',
	           PRIMARY KEY (session_id)
               )  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
     */

    /**
     * Creates session handler.
     * Requires CodeIgniter database library object reference
     * @param unknown $CodeIgniter
     */
    public function __construct(){
        $this->CI = & get_instance();
        $this->db = $this->CI->load->database('default', true);
        foreach(array('sess_table_name') as $var){
            $configItem = $this->CI->config->item($var);
            if(!empty($configItem)){
                $this->{$var} =  $configItem;
            }
        }
    }

    private function checkInstall (){
      // Added in 1.7.1
      // This will check to see if the plain_sessions table exists.
      // If not, likely showing up before installation.
      if ( !$this->db->table_exists($this->dbTable) ) :
        $this->_log('Sessions table does not exist.');
        return false;
      endif;
      return true;
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::close()
     */
    public function close(){
        $this->_log('Close called');
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::destroy()
     */
    public function destroy ( $session_id ){
        $this->_log('Destroy called');
        if ( !$this->checkInstall() ) :
          return false;
        endif;
        $this->db->where($this->idColumn, $session_id);
        $this->db->delete($this->dbTable);
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::gc()
     */
    public function gc ( $maxlifetime ){
        $this->_log('GC called');
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::open()
     */
    public function open ( $save_path , $name ){
        $this->_log('Open called');
        return true;
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::read()
     */
    public function read ( $session_id ){
         $this->_log('Read called');
         if ( !$this->checkInstall() ) :
           return null;
         endif;
         $session = $this->db->get_where($this->dbTable, array($this->idColumn => $session_id));
         if (isset($session) && $session->num_rows() > 0){
             $row = $session->row();
             return $row->{$this->dataColumn};
         }
        return null;
    }

    /**
     * (non-PHPdoc)
     * @see SessionHandlerInterface::write()
     */
    public function write ( $session_id , $session_data ){
        $this->_log('Write called');
        if ( !$this->checkInstall() ) :
          return false;
        endif;
        $session = $this->db->get_where($this->dbTable, array($this->idColumn => $session_id));
        if ($this->read($session_id) !== null){
             $this->db->where($this->idColumn, $session_id);
             $this->db->update($this->dbTable, array($this->dataColumn => $session_data));
        } else{
            $this->db->insert($this->dbTable, array($this->idColumn => $session_id, $this->dataColumn => $session_data));
        }
        return true;
    }

    /**
     * Write session on end of request
     */
    public function __destruct(){
        session_write_close();
    }

    /**
     * Helper method to write message to log using CI mechanisms
     * @param unknown $message
     * @param string $level
     */
    private function _log($message, $level = 'debug'){
        @log_message($level, '[CIDatabaseSessionHandler] '.$message);
    }
}
