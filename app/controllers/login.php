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
    
    public function resetpassword(){
        $this->load->helper('captcha');
        
        if($_POST){
            $captcha = $this->input->post('captcha',true);
            if($captcha == $this->session->userdata('captcha')){
                $email = $this->input->post('email',true);
                $newPass = md5(rand(1, 999999));
                $newPass = substr($newPass,0,10);
                if($this->users->resetPassword($email,$newPass)){
                    $this->load->library('email');
                    $this->load->model('settings');
                    
                    $site_name = $this->settings->getSettingByName("site_name");
                    $site_email = $this->settings->getSettingByName("site_email");
                    
                    $this->email->from($site_email->value, 'CMS ('.$site_name->value.'):');
                    $this->email->to($email);

                    $this->email->subject('CMS ('.$site_name->value.'): أستعادة الباسورد');
                    $message = '
                        <p>البيانات الخاص بك</p>
                        <p>الباسورد الجديد :'.$newPass.'</p>
                        <p style="color:red">تنبيه هذا أذا سجلت الدخول بعد هذه الرسالة بالباسورد القديم سوف يعتبر هذا الباسورد ملغي وسوف يعتمد القديم</p>
                    ';
                    $this->email->message($message);

                    $this->email->send();
                    $data['CONTENT'] = "msg";
                    $data['MSG'] = 'تم أرسال البيانات لأيميلك الرجاء مراجعة الأيميل <br/> تنبيه: قد يصل الأيميل للبريد المهمل الرجاء التأكد منه';
                }else{
                    $vals = array(
                        'img_path' => './captcha/',
                        'img_url' => base_url().'captcha/'
                    );
                    $cap = create_captcha($vals);

                    $data['CAPTCHA'] = $cap['image'];
                    $data['ERROR'] = TRUE;
                    $data['ERR_MSG'] = 'خطاً عفوا هذا الايميل غير مسجل لدينا';

                    $array = array(
                        'captcha'   => $cap['word']
                    );
                    $this->session->set_userdata($array);
                    $data['CONTENT'] = "login";
                    $data['STEP'] = 'resetpass';
                }
            }else{
                $vals = array(
                    'img_path' => './captcha/',
                    'img_url' => base_url().'captcha/'
                );
                $cap = create_captcha($vals);

                $data['CAPTCHA'] = $cap['image'];
                $data['ERROR'] = TRUE;
                $data['ERR_MSG'] = 'خطأ في الجواب على السؤال الأمني';

                $array = array(
                    'captcha'   => $cap['word']
                );
                $this->session->set_userdata($array);
                $data['CONTENT'] = "login";
                $data['STEP'] = 'resetpass';
            }
        }else{
            $vals = array(
                    'img_path' => './captcha/',
                    'img_url' => base_url().'captcha/'
                );
            $cap = create_captcha($vals);
            
            $data['CAPTCHA'] = $cap['image'];
            $data['ERROR'] = FALSE;
            $data['ERR_MSG'] = '';
            
            $array = array(
                'captcha'   => $cap['word']
            );
            $this->session->set_userdata($array);
            $data['CONTENT'] = "login";
            $data['STEP'] = 'resetpass';
        }
        $data['TITLE'] = "-- أستعادة الباسورد";
        $this->core->load_template($data);
    }
}

?>
