<?php
/*
 *  This Class for mainpulation in the Group
 * add , remove and edit
 * 
 * Saeed
 * 
 * 
 */



class groups extends CI_Model {
   
    private $_table = "group";
    
    function __construct() {
        parent::__construct();
    }
    
    
    function addNewGroup($data)
    {
        if(!is_array($data)) return false;
        
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

/*
 *  function to get all group or specific group
 */
    function getGroups ($id="all"){
        
        if (empty($id)) return FALSE;  //Cheack it's not empty value
        
        // Get the group by id
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
            return ($query->num_rows() >0)? $query->result() : false;

    }
    
         
         function deleteGroup($id="all") {
             
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
         
         function updateGroup($id,$data) {
             
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
//EOF
         private function error(){
            $query = $this->db->last_query();
            $typeOfError =  $this->db->_error_message();
            log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
        }
    
    } 
?>
