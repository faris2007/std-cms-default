<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Permissions extends CI_Model {
      
    private $_table = "logs";
    
    function __construct() {
        
        parent::__construct();
        
    }
            /*
             *
             */
    function addNewPermission($data) {
        if( !is_array($data))
            return false;
        return $this->db->insert($this->_table,$data);
    }
    
    function updatePermission($id , $data) {
        
        if(empty($id) || !is_array($data))
            return FALSE;
        
        $this->db->where('id' , $id);
        
        return $this->db->update($this->_table,$data);
    }

    
    private function deletePermission($id = "all") {
        
        if(empty($id) || !is_numeric($id))
            return FALSE;
        
        $this->db->where('id',$id);
        
        return $this->db->delete($this->_table);
            
    }
    
    function getPermissions($groupid) {
   
         if(empty($groupid) ) return false;
        
        if(is_numeric($groupid))
        {
            $this->db->where('group_id',$groupid);
        }
        $this->db->order_by("id"); 
        $query = $this->db->get($this->_table);
        return ($query->num_rows() > 0)? $query->result() : false;
    
    }
    
    function getPermission($id) {
        if(empty($id) || !is_numeric($id)) return false;
        
        $this->db->where('id' , $id);
        
        $query=  $this->db->get($this->_table);
        
        return( $query->num_rows() > 0) ?$query->row() :FALSE;
        
    }
    
}

?>
