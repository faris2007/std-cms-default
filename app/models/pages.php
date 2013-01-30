<?php

/**
 * this class for add,edit and remove from pages table
 * 
 * @author Faris Al-Otaibi
 */
class pages extends CI_Model{
    
    private $_table = "pages";
    
    public function __construct() {
        parent::__construct();
    }
    
    public function addNewPage($data){
        if(!is_array($data))
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
    
    public function updatePage($id,$data){
        if(empty($id) || !is_array($data) || !is_numeric($id))
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
    
    public function deletePage($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
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
    
    public function getPage($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            return ($query->num_rows() > 0)? $query->row() : false;
        }
            
    }
    
    public function getPages($parentId = 'all'){
        if (empty($parentId))            
            return FALSE;
        
        $this->db->trans_start();
        if(is_numeric($parentId))
            $this->db->where('parent_id', $parentId);
        
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            return ($query->num_rows() > 0)? $query->result() : false;
        }
            
    }
    
    public function getParentThisPage($pageId){
        if(empty($pageId))
            return false;
        
        if(is_numeric($pageId) && $pageId > 0){
            $result = $this->getPage($pageId);
            if($result->parent_id != NULL)
                return $this->getParentThisPage($result->parent_id).','.serialize($result);
            else
                return serialize($result);
        }
            
    }


    private function error(){
        $query = $this->db->last_query();
        $typeOfError =  $this->db->_error_message();
        log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
    }
}

?>
