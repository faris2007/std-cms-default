<?php

/**
 * this class for add,edit and remove from login table
 * 
 * @author Faris Al-Otaibi
 */
class login extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        if(!$this->users->isLogin())
        {
            if($_POST){
                $username = $this->input->post('username',true);
                $password = $this->input->post('password',true);
                if($this->users->login($username,$password)){
                    $data['CONTENT'] = 'msg';
                    $data['TITLE'] = "-- تسجيل الدخول";
                    $data['MSG'] = 'تم تسجيل الدخول بنجاح, سوف يتم تحويلك تلقائيا<br />'.  anchor(base_url(), "أذا لم يتم تحويلك بشكل تلقائي يمكن الضغط على هذا الرابط للأنتقال للصفحة الرئيسية");
                    $data['HEAD'] =  meta(array('name' => 'refresh', 'content' => '3;url='.  base_url(), 'type' => 'equiv'));
                }else{
                    $data['CONTENT'] = "login";
                    $data['TITLE'] = "-- تسجيل الدخول";
                    $data['STEP'] = 'login';
                    $data['ERROR'] = true;
                }
            }else{
                $data['CONTENT'] = "login";
                $data['TITLE'] = "-- تسجيل الدخول";
                $data['STEP'] = 'login';
                $data['ERROR'] = FALSE;
            }
            $this->core->load_template($data);
        }else
            redirect ();
    }
    
    public function logout(){
        if($this->users->isLogin()){
            $this->users->logout();
        }
        $data['CONTENT'] = "login";
        $data['TITLE'] = "-- تسجيل الدخول";
        $data['STEP'] = 'logout';
        $data['ERROR'] = FALSE;
        $this->core->load_template($data);
    }
    
    public function permission(){
        if($this->users->isLogin()){
            $data['CONTENT'] = "login";
            $data['TITLE'] = "-- تسجيل الدخول";
            $data['STEP'] = 'permission';
            $data['ERROR'] = FALSE;
            $this->core->load_template($data);
        }else
            redirect ('login');
    }
}

?>
