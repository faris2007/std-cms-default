<?php

/**
 * this class for add,edit and remove from error_log table
 * 
 * @author Faris Al-Otaibi
 */
class error_logs extends CI_Model {
    
    private $_table = "error_log";
    
    public function __construct() {
        parent::__construct();
    }
    
    public function addNewLog($data){
        if(!is_array($data))
            return false;
        
        $this->db->trans_start();
        $this->db->insert($this->_table,$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return True;
    }
    
    public function updateLog($id,$data){
        if(empty($id) || !is_array($data) || !is_numeric($id))
            return FALSE;
        
        $this->db->trans_start();
        $this->db->where("id",$id);
        $this->db->update($this->_table,$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return True;
    }
    
    public function deleteLog($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return True;
    }
    
    public function getLog($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            return ($query->num_rows() > 0)? $query->row() : false;
        }
            
    }
    
    public function getLogs(){
        
        $this->db->trans_start();
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            return ($query->num_rows() > 0)? $query->result() : false;
        }
            
    }
    
    
    private function error(){
        $query = $this->db->last_query();
        $typeOfError =  $this->db->_error_message();
        log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
    }
}

?>
