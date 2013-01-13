<?php

class Menus extends CI_Model {

        private $_table = "menu";

    function __construct() {
        parent::__construct();
    }

    function addNewMenu($data) {
        if(!is_array($data))
            return FALSE;
        
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

    function updateMenu($id , $data ) {
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

    function deleteMenu($id) {
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
    
    function getMenus($parentId = NULL) {
        
        $this->db->trans_start();
        if(!is_null($parentId) && is_numeric($parentId))
        {
            $this->db->where('parent_id',$parentId);
        }elseif(is_null($parentId)){
            $this->db->where('parent_id IS NULL');
        }
        $this->db->order_by("sort_id"); 
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return ($query->num_rows() > 0)? $query->result() : false;
    }
    
    function getMenu($id) {
        
        if(empty($id) || !is_numeric($id)) return false;
        
        $this->db->trans_start();
        $this->db->where('id',$id);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $this->error();
            return false;
        }else
            return ($query->num_rows() > 0)? $query->row() : false; 
    }
    
    public function getMenuWithChild($parentID,$where = NULL){
        if((is_null($parentID) || is_numeric($parentID) )){
            $data = array();
            if(is_array($where))
                $this->db->where($where);
            
            $result = $this->getMenus($parentID);
            if(!is_bool($result)){
                foreach ($result as $key => $val){
                    $data[$key]['content'] = $val;
                    $data[$key]['child'] = serialize($this->getMenuWithChild($val->id,$where));
                }
            }else
                return false ;
            return $data;
        }
    }
    
    public function getParentThisMenu($menuId){
        if(empty($menuId))
            return false;
        
        if(is_numeric($menuId) && $menuId > 0){
            $result = $this->getMenu($menuId);
            if($result->parent_id != NULL)
                return $this->getParentThisMenu($result->parent_id).','.serialize($result);
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
