<?php

Class courses extends CI_Model{
    
    private $_table = "course";

    function __construct() {
        parent::__construct();
    }
        
    
    function addNewCourse($data) {
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
  
    function getCourse($id="all"){
        
        if (empty($id)) return FALSE;  
        
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
         
         function deleteCourse($id="all") {
             
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
         
           function updateCourse($id,$data) {
             
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
        
        public function getNameOfCourse($id){
            if(empty($id) || !is_numeric($id))
                return false;
            
            $query = $this->getCourse($id);
            if(is_bool($query))
                return false;
            else 
                return $query[0]->course_name;
        }
        
        public function getAvailableCourse($userId){
            if(!empty($userId) && !is_numeric($userId))
                $checkUser = "AND course.id = (SELECT course_id FROM `order` where `order`.users_id != '".$userId."')";
            else
                $checkUser = "";
            
            $where = "course.`course_capacity` > (SELECT count(*) FROM `order` where `order`.course_id = course.id) ".$checkUser;
            $this->db->where($where);
            $anotherWhere = array(
                'isHidden'              => 0,
                'isDelete'              => 0,
                'course_register_end >'   => time()
            );
            $this->db->where($anotherWhere);
            $result = $this->getCourse('all');
            
            return $result;
        }
        
}// end class
?>
