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
        if(empty($data) || !is_array($data)) return false;
        
        return $this->db->insert($this->_table, $data); 
    }

/*
 *  function to get all group or specific group
 */
    function getGroups ($id="all"){
        
        if (empty($id)) return FALSE;  //Cheack it's not empty value
        
        // Get the group by id
        if(is_numeric($id) || !is_numeric($id) ){ 
        $this->db->where('id',$id);
        
        }
         $this->db->order_by("id");
         $query = $this->db->get($this->_table);
         return ($query->num_row >0)? $query->result() : false;

         }
    
         
         function deleteGroup($id="all") {
             
             if(empty($id) || !is_numeric($id)) 
                 return FALSE;
             $this->db->where('id' , $id);
             return $this->db->delete($this->_table);
         }
         
         function updateGroup($id,$data) {
             
             if(empty($id) || !is_numeric($data))
                 return FALSE;
             
             $this->db->where('id' , $id);
             
             return $this->db->update($this->_table,$data);
         
             
         }
//EOF
    
    
    } 
?>
