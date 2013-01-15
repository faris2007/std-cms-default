<?php

/**
 * this class for add,edit and remove from admin table
 * 
 * @author Faris Al-Otaibi
 */
class admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        if($this->users->isLogin() && $this->users->isAdmin()){
            $data['CONTENT'] = "admin";
            $data['TITLE'] = "-- لوحة التحكم";
            $this->core->load_template($data);
        }else
            redirect ('login/permission');
    }
}

?>
