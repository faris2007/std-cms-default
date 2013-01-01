<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Logs extends CI_Model {
      
    private $_table = "logs";
    
    function __construct() {
        
        parent::__construct();
        
    }
            /*
             *
             */
    function addNewLog($data) {
        if(empty($data) || !is_array($data))
            return false;
        return $this->db->insert($this->_table,$data);
    }
    
    function updateLog($id , $data) {
        
        if(empty($id) || is_array($data))
            return FALSE;
        
        $this->db->where('id' , $id);
        
        return $this->db->update($this->_table,$data);
    }

    
    private function deleteLog($id = "all") {
        
        if(empty($id) || !is_numeric($id))
            return FALSE;
        
        $this->db->where('id',$id);
        
        return $this->db->delete($this->_table);
            
    }
    
    function getLogs($userid) {
   
         if(empty($userid)) return false;
        
        if(is_numeric($userid))
        {
            $this->db->where('users_id',$userid);
        }
        $this->db->order_by("id"); 
        $query = $this->db->get($this->_table);
        return ($query->num_rows() > 0)? $query->result() : false;
    
    }
}

?>
