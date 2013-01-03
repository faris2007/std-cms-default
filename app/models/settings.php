<?php

class settings extends CI_Model {

    private $_table = "settings";
    
    function __construct() {
        
        parent::__construct();
    }

    function addNewSetting($data) {
        if(!is_array($data))
            return FALSE;
        
      return $this->db->insert($this->_table, $data); 
    }

    function updateSetting($id , $data) {
        if(empty($id) || !is_array($data))
            return FALSE;
                return $this->db->insert($this->_table, $data); 

            
    }
    
    function deleteSetting($id , $data) {
        if (empty($id) || !is_array($data))
            return FALSE;
    
        $this->db->where('id',$id);
        return $this->db->delete($this->_table); 
    }

    function getSettings($userid) {
        if(empty($userid)) return false;
        
        if(is_numeric($userid))
        {
            $this->db->where('users_id',$userid);
        }
        $this->db->order_by("id"); 
        $query = $this->db->get($this->_table);
        return ($query->num_rows() > 0)? $query->result() : false;
    }
    
    function getSetting($id) {
           if(empty($id) || !is_numeric($id)) return false;
        
        $this->db->where('id',$id);
        $query = $this->db->get($this->_table);
        return ($query->num_rows() > 0)? $query->row() : false; 
    }
}

?>
