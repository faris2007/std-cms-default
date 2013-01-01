<?php

class Menu extends CI_Model {

        private $_table = "menu";

    function __construct() {
        parent::__construct();
    }

    function addNewMenu($data) {
        if(!is_array($data))
            return FALSE;
        
        return $this->db->insert($this->_table,$data);        
    }

    function updateMenu($id , $data ) {
        if(empty($id) || !is_array($data))
            return FALSE;
        
        $this->db->where('id' , $id);
        return $this->db->order_by('data' , $data);
        
    }

    function deleteMenu($id) {
        if(empty($id) || !is_numeric($id))
            return FALSE;
        
        $this->db->where('id' , $id);
        
        return $this->db->delete($this->_table);
        
    }
    
    function getMenus($id = "all") {
        if(!is_numeric($id))
            return FALSE;
        if(is_numeric($id))
        {
            $this->db->where('id',$id);
        }
        $this->db->order_by("id"); 
        $query = $this->db->get($this->_table);
        return ($query->num_rows() > 0)? $query->result() : false;
    }
    function getMenu($id) {
        
        if(empty($id) || !is_numeric($id)) return false;
        
        $this->db->where('id',$id);
        $query = $this->db->get($this->_table);
        return ($query->num_rows() > 0)? $query->row() : false; 
    }
    
}


?>
