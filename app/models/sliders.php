<?php

Class sliders extends CI_Model{
    
    private $_table = "slider";

    function __construct() {
        parent::__construct();
    }
        
    
    function addNewSlider($data) {
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
  
    function getSlider ($id="all"){
        
        if (empty($id)) return FALSE;  
        
        $this->db->trans_start();
        if(is_numeric($id)){ 
        $this->db->where('id',$id);
        
        }
         $this->db->order_by("sort_id");
         $query = $this->db->get($this->_table);
         $this->db->trans_complete();
         if($this->db->trans_status() === FALSE)
         {
            $this->error();
            return false;
         }else
            return ($query->num_rows() >0)? $query->result() : false;
         }
         
         function deleteSlider($id="all") {
             
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
         
           function updateSlider($id,$data) {
             
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
}// end class
?>
