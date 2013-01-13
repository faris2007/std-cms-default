<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Permissions extends CI_Model {
      
    private $_table = "permissions";
    
    function __construct() {
        
        parent::__construct();
        
    }
            /*
             *
             */
    function addNewPermission($data) {
        if( !is_array($data))
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
    
    function updatePermission($id , $data) {
        
        if(empty($id) || !is_array($data))
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

    
    public function deletePermission($id = "all") {
        
        if(empty($id) || !is_numeric($id))
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
    
    function getPermissions($groupid) {
   
         if(empty($groupid) ) return false;
        
         //$this->db->trans_start();
         if(is_numeric($groupid))
         {
            $this->db->where('group_id',$groupid);
         }
         //$this->db->order_by("id"); 
         $query = $this->db->get($this->_table);
         //$this->db->trans_complete();
         /*if($this->db->trans_status() === FALSE)
         {
            $this->error();
            return false;
         }else*/
            return ($query->num_rows() > 0)? $query->result() : false;
    
    }
    
    function getTotalPermissions($groupid) {
   
         if(empty($groupid) ) return false;
        
         $this->db->trans_start();
         if(is_numeric($groupid))
         {
            $this->db->where('group_id',$groupid);
         }
         $this->db->order_by("id"); 
         $query = $this->db->get($this->_table);
         $this->db->trans_complete();
         if($this->db->trans_status() === FALSE)
         {
            $this->error();
            return false;
         }else
            return $query->num_rows() ;
    
    }
    
    function getPermission($id) {
        if(empty($id) || !is_numeric($id)) return false;
        
        $this->db->trans_start();
        $this->db->where('id' , $id);
        
        $query=  $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return( $query->num_rows() > 0) ?$query->row() :FALSE;
        
    }
    
    private function error(){
        $query = $this->db->last_query();
        $typeOfError =  $this->db->_error_message();
        log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
    }
    
}

?>
