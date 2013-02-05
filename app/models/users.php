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
    
    private function setSession($userid,$groupId){
        $data = $this->getPermissions($groupId);
        $data['userid'] = $userid;
        $data['group'] = $groupId;
        $CI =& get_instance();
        $CI->load->model('groups');
        $groupInfo = $CI->groups->getGroups($groupId);
        $data['group_type'] = ($groupInfo[0]->isAdmin == 1)? 'admin' : 'user';
        $this->session->set_userdata($data);
    }
    
    private function unsetSession(){
        
        $data['premissions'] = '';
        $data['userid'] = '';
        $data['group'] = '';
        $data['group_type'] = '';
        $this->session->unset_userdata($data);
    }
    
    private function getPermissions($groupId)
    {
        $CI =& get_instance();
        $CI->load->model('permissions');
        $permissions = $CI->permissions->getPermissions($groupId);
        if(!is_bool($permissions))
        {
            $data = array();
            foreach ($permissions as $row)
            {
                $data['permissions'][] = array(
                    'service_name'  => $row->service_name,
                    'function_name' => $row->function_name,
                    'value'         => $row->value
                );
                /*if($row->function_name == "all")
                    $data['permissions'][$row->service_name] =  true;
                else
                {
                    if($row->value == "all")
                        $data['permissions'][$row->service_name][$row->function_name] = true;
                    else
                    {
                        $data['permissions'][$row->service_name][$row->function_name][$row->value] = true;
                    }
                }*/
            }
            return $data;
        }else
            return array(
                'permissions' => false
                );
    }
    
    public function checkIfHavePremission($service_name = "admin",$function_name = "all",$value = "all")
    {
        if(empty($service_name) || empty($function_name) || empty($value) )
            return FALSE;
        
        $premission = $this->session->userdata('permissions');
        if($service_name == 'admin'){
            return $this->isAdmin();
        }
        foreach ($premission as $row){
            if($row['service_name'] == $service_name){
                if($row['function_name'] == $function_name || $row['function_name'] == 'all'){
                    if($row['value'] == $value || $row['value'] == 'all'){
                        $this->setSession($this->session->userdata('userid'),$this->session->userdata('group'));
                        return true;
                    }
                }elseif($function_name == 'all' && $value == 'all'){
                    $this->setSession($this->session->userdata('userid'),$this->session->userdata('group'));
                    return true;
                }elseif($function_name == 'show'){
                    switch ($row['function_name']){
                        
                        case 'add':
                        case 'edit':
                        case 'delete':
                        case 'active':
                            $this->setSession($this->session->userdata('userid'),$this->session->userdata('group'));
                            return true;
                            break;
                        
                        default :
                            break;
                    }
                }
                    
            }
            
        }
        return false;
        /*$accessGrade = (isset($premission[$service_name]))?1:0;
        $accessAdmin = (isset($premission[$service_name]))?0:3;
        
        if($function_name != "all"){
            $accessGrade++;
            if($value != "all") { 
                $accessGrade++;
            }
        } 
        if($accessAdmin == 1){
            if(!is_bool($premission[$service_name])){
                $accessAdmin++;
                if(!is_bool($premission[$service_name][$function_name])) { 
                    $accessAdmin++;
                    if(!is_bool($premission[$service_name][$function_name][$value])) 
                        $accessAdmin++;
                }
            }
        }*/
        //return ($accessGrade >= $accessAdmin)? true:false;
    }


    public function addNewUser($data){
        if(!is_array($data))
            return false;
        $data['password'] = $this->encrypt_password($data['password']);
        
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
    
    public function updateUser($id,$data){
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
    
    public function deleteUser($id){
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
    
    public function getUser($id){
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
    
    public function getUsers(){
        
        $this->db->trans_start();
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            return ($query->num_rows() > 0)? $query->result() : false;
        }
            
    }
    
    public function login($username,$password){
        if(empty($username) || empty($password))
            return false;
        
        $this->db->trans_start();
        $this->db->where('isBanned', 0);
        $this->db->where('isDelete', 0);
        $this->db->where('isActive', 1);
        $this->db->where('username', $username);
        $query = $this->db->get($this->_table);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            $this->error();
            return false;
        }else{
            if($query->num_rows() > 0)
            { 
                $result = $query->row() ;
                if(empty($result->new_password)){
                    if($result->password == $this->encrypt_password($password)){
                        $this->setSession($result->id, $result->group_id);
                        return true;
                    }else
                        return false;
                }else{
                    if($result->password == $this->encrypt_password($password)){
                        $data['new_password'] = Null;
                        $this->updateUser($result->id, $data);
                        $this->setSession($result->id, $result->group_id);
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
    
    public function logout(){
        $this->unsetSession();
    }
    
    public function isLogin($redirect = FALSE)
    {
        if ($this->session->userdata('userid'))
        {
            return TRUE;
        }else{
            if ($redirect)
            {
                redirect();
            }else{
                return FALSE;
            }
        }
    }
    
    public function isAdmin($redirect = FALSE)
    {
        if ($this->session->userdata('group_type') == 'admin')
        {
            return TRUE;
        }else{
            if ($redirect)
            {
                redirect();
            }else{
                return FALSE;
            }
        }
    }
    
    public function isActive(){
        $id = $this->session->userdata('userid');
        $userInfo = $this->getUser($id);
        if(!is_bool($userInfo)){
            if($userInfo->isActive == 1)
                return true;
            else
                return FALSE;
        }else
            return false;
    }


    public function change_password($username,$oldPass,$newPass,$admin = false){
        if(empty($username)||(empty($oldPass)&& !$admin)||empty($newPass))
            return false;
        
        $CI =& get_instance();
        $CI->load->model('password_logs');
        if(!$admin){
            $this->db->where("password",  $this->encrypt_password($oldPass));
        }
        $this->db->where("username",$username);
        $query = $this->db->get($this->_table);
        if($query->num_rows() == 0)
            return false;
        $result = $query->row();
        $store = array(
            'date'      => date("d-m-Y H:i:s"),
            'password'  => $result->password,
            'users_id'  => $result->id
        );
        $CI->password_logs->addNewLog($store);
        $data['password'] = $this->encrypt_password($newPass);
        if($this->updateUser($result->id, $data)){
            return true;
        }else
            return false;
            
    } 
    
    public function resetPassword($email,$newPassword){
        if(empty($email) || empty($newPassword))
            return false;
        $CI =& get_instance();
        $CI->load->model('password_logs');
        $this->db->where('isBanned', 0);
        $this->db->where('isDelete', 0);
        $this->db->where('isActive', 1);
        $this->db->where("email",  $email);
        $query = $this->db->get($this->_table);
        if($query->num_rows() == 0)
            return false;
        $result = $query->row();
        $store = array(
            'date'      => date("d-m-Y H:i:s"),
            'password'  => $result->password,
            'users_id'  => $result->id
        );
        $CI->password_logs->addNewLog($store);
        $data['new_password'] = $this->encrypt_password($newPassword);
        if($this->updateUser($result->id, $data)){
            return true;
        }else
            return false;
            
    }
    
    private function error(){
        $query = $this->db->last_query();
        $typeOfError =  $this->db->_error_message();
        log_message("error", "In This Query -- " . $query . " -- " . $typeOfError);
    }
    
    public function getInfoUser($type ='id'){
        if(empty($type))
            return false;
        
        
        switch ($type){
            
            case 'id':
                return $this->session->userdata('userid');
                break;
            
            case 'group':
                return $this->session->userdata('group');
                break;
            
            default :
                return false;
                break;
        }
        
    }
    
    public function getUsername($id){
        if(empty($id))
            return false;
        
        $query = $this->getUser($id);
        
        
        return (!is_bool($query))? $query->username : false;
    }
    
    public function getEmail($id){
        if(empty($id))
            return false;
        
        $query = $this->getUser($id);
        
        
        return (!is_bool($query))? $query->email : false;
    }
    
    
}

?>
