<?php

class settings extends CI_Model {

    private $_table = "settings";
    
    function __construct() {
        
        parent::__construct();
    }

    function addNewSetting($data) {
        if(!is_array($data))
            return FALSE;
        
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

    function updateSetting($name , $data) {
        if(empty($name) || !is_array($data))
            return FALSE;
        
        $this->db->trans_start();
        $this->db->where("name",$name);
        $this->db->update($this->_table,$data);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return True;

            
    }
    
    function deleteSetting($id) {
        if (empty($id) || !is_numeric($id))
            return FALSE;
    
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

    function getSettings() {
        
        $this->db->trans_start();
        $this->db->order_by("id"); 
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return ($query->num_rows() > 0)? $query->result() : false;
    }
    
    function getSetting($id) {
           if(empty($id) || !is_numeric($id)) return false;
        
        $this->db->trans_start();
        $this->db->where('id',$id);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return ($query->num_rows() > 0)? $query->row() : false; 
    }
    
    function getSettingByName($name){
        if(empty($name) || !is_string($name))
            return FALSE;
        
        $this->db->trans_start();
        $this->db->where('name',$name);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return ($query->num_rows() > 0)? $query->row() : false;
    }
    
    private function error(){
        $query = $this->db->last_query();
        $typeOfError =  $this->db->_error_message();
        log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
    }
}

?>
