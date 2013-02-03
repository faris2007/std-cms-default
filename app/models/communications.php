<?php

/**
 * this class for add,edit and remove from cats table
 * 
 * @author Faris Al-Otaibi
 */
class communications extends CI_Model {
    
    private $_table = "communication";

    function __construct() {
        parent::__construct();
    }
        
    
    function addNewCommunication($data) {
        if(!is_array($data)) return FALSE; 
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
  
    function getCommunication($id){

        if (empty($id) || !is_numeric($id)) return FALSE;  

        $this->db->trans_start();
        if(is_numeric($id)){ 
            $this->db->where('id',$id);
        }
        $this->db->order_by("id");
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return ($query->num_rows() >0)? $query->row() : false;
    }
    
    function getCommunications($parent_id = 'all'){

        if (empty($parent_id) ) return FALSE;  

        $this->db->trans_start();
        if($parent_id == 'all'){
            $this->db->where('parent_id IS NULL');
        }else{
            $this->db->where('parent_id',$parent_id);
        }
        $this->db->order_by("id");
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return ($query->num_rows() >0)? $query->result() : false;
    }
         
    function deleteCommunication($id="all") {

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

    function updateCommunication($id,$data) {

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

    private function error(){
        $query = $this->db->last_query();
        $typeOfError =  $this->db->_error_message();
        log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
    }

}

?>
