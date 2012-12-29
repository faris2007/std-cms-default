<?php

/**
 * this class for add,edit and remove from users table
 * 
 * @author Faris Al-Otaibi
 */
class users extends CI_Model{
    
    private $_table = "users";
    
    public function __construct() {
        parent::__construct();
    }
    
    private function encrypt_password($pass){
        return sha1($pass);
    }


    public function addNewUser($data){
        if(!is_array($data))
            return false;
        $data['password'] = $this->encrypt_password($data['password']);
        
        $this->db->trans_start();
        $this->db->insert($this->_table,$data);
        $this->db->trans_complete();
        return ($this->db->trans_status() === FALSE)? FALSE : True;
    }
    
    public function updateUser($id,$data){
        if(empty($id) || !is_array($data) || !is_numeric($id))
            return FALSE;
        
        $this->db->trans_start();
        $this->db->where("id",$id);
        $this->db->update($this->_table,$data);
        $this->db->trans_complete();
        return ($this->db->trans_status() === FALSE)? FALSE : True;
    }
    
    public function deleteUser($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete($this->_table);
        $this->db->trans_complete();
        return ($this->db->trans_status() === FALSE)? FALSE : True;
    }
    
    public function getUser($id){
        if(empty($id) || !is_numeric($id))
            return false;
        
        $this->db->trans_start();
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
            return false;
        else{
            return ($query->num_rows() > 0)? $query->row() : false;
        }
            
    }
    
    public function getUsers(){
        
        $this->db->trans_start();
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
            return false;
        else{
            return ($query->num_rows() > 0)? $query->result() : false;
        }
            
    }
    
    public function login($username,$password){
        if(empty($username) || empty($password))
            return false;
        
        $this->db->trans_start();
        $this->db->where('username', $username);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
            return false;
        else{
            if($query->num_rows() > 0)
            { 
                $result = $query->row() ;
                if(empty($result->new_password)){
                    if($result->password == $this->encrypt_password($password)){
                        return true;
                    }else
                        return false;
                }else{
                    if($result->password == $this->encrypt_password($password)){
                        $data['new_password'] = Null;
                        $this->updateUser($result->id, $data);
                        return true;
                    }elseif($result->new_password == $this->encrypt_password($password)){
                        $data['password'] = $result->new_password;
                        $this->updateUser($result->id, $data);
                        return true;
                    }else
                        return false;
                }
            }
        }
    }
    
    
    
}

?>
