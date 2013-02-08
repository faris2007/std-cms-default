<?php

/**
 * this class for add,edit and remove from register table
 * 
 * @author Faris Al-Otaibi
 */
class register extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    private function __check($type,$value)
    {
        if(empty($type) || empty($value))
            return false;
        
        if($type == 'username')
            $this->db->where('username',$value);
        elseif($type == 'email')
            $this->db->where('email',$value);
        else
            return false;
        $check = $this->users->getUsers();
        if(is_bool($check))
            return true;
        else
            return false;
    }


    public function index(){
        if($this->users->isLogin())
            redirect ();
        
        $this->load->helper('captcha');
        $enable_register = $this->core->getSettingByName("cms_register_enable");
        $group_register = $this->core->getSettingByName("cms_register_group");
        if($enable_register == 0){
            $data['CONTENT'] = "msg";
            $data['MSG'] = 'عفواً التسجيل مغلق حالياً نرجوا منك زيارتنا لاحقا';

        }else{
            if($_POST){
                $captcha = $this->input->post('captcha',true);
                if($captcha == $this->session->userdata('captcha')){
                    $password = $this->input->post('password',true);
                    $repassword = $this->input->post('repassword',true);
                    if($password == $repassword){
                        if($this->__check('username', $this->input->post('username',true)) && $this->__check('email', $this->input->post('email',true))){
                            
                            $active_register = $this->core->getSettingByName("cms_register_active");
                            $store = array(
                                'username'      => $this->input->post('username',true),
                                'full_name'     => $this->input->post('fullName',true),
                                'email'         => $this->input->post('email',true),
                                'password'      => $password,
                                'mobile'        => $this->input->post('mobile',true),
                                'isBanned'      => 0,
                                'isDelete'      => 0,
                                'isActive'      => $active_register,
                                'group_id'      => ($group_register)? $group_register : 2
                            );
                            $this->users->addNewUser($store);
                            $this->load->library('email');
                            $site_name = $this->core->getSettingByName("site_name");
                            $site_email = $this->core->getSettingByName("site_email");

                            $this->email->from($site_email, ' ('.$site_name.'):');
                            $this->email->to($this->input->post('email',true));

                            $this->email->subject('('.$site_name.'): تسجيل جديد');
                            $message = '
                                <p>شكراً لأختيارك موقعنا نتمنى لك التوفيق</p>
                                <p>هذه الرسالة تأتي لتأكيد التسجيل لدينا </p>
                                <p>اسم المستخدم  :'.$this->input->post('username',true).'</p>
                                <p>'.  anchor(base_url(), $site_name).'</p>
                            ';
                            $this->email->message($message);

                            $this->email->send();
                            $data['CONTENT'] = "msg";
                            if($active_register->value == 0)
                                $data['MSG'] = ' شكراً لتسجيلك في موقعنا سيصلك ايميل عند التفعيل <br/> تنبيه: قد يصل الأيميل للبريد المهمل الرجاء التأكد منه';
                            else
                                $data['MSG'] = ' شكراً لتسجيلك في موقعنا و تم تفعيل حسابك تلقائياً <br/> تنبيه: قد يصل الأيميل للبريد المهمل الرجاء التأكد منه';
                        }else{
                            $vals = array(
                                    'img_path' => './captcha/',
                                    'img_url' => base_url().'captcha/'
                                );
                            $cap = create_captcha($vals);

                            $data['CAPTCHA'] = $cap['image'];
                            $data['ERROR'] = True;
                            $data['ERR_MSG'] = 'خطأ انت مسجل لدينا سابق يمكنك استخدام هذا <a href="<?=base_url()?>login/resetpassword">النموذج</a> لأستعادة كلمة المرور';

                            $array = array(
                                'captcha'   => $cap['word']
                            );
                            $this->session->set_userdata($array);
                            $data['CONTENT'] = "register";
                        }
                    }else{
                        $vals = array(
                                'img_path' => './captcha/',
                                'img_url' => base_url().'captcha/'
                            );
                        $cap = create_captcha($vals);

                        $data['CAPTCHA'] = $cap['image'];
                        $data['ERROR'] = True;
                        $data['ERR_MSG'] = 'خطأ في أحدى كلمات المرور يبدو انها غير متطابقة';

                        $array = array(
                            'captcha'   => $cap['word']
                        );
                        $this->session->set_userdata($array);
                        $data['CONTENT'] = "register";
                    }
                }else{
                    $vals = array(
                            'img_path' => './captcha/',
                            'img_url' => base_url().'captcha/'
                        );
                    $cap = create_captcha($vals);

                    $data['CAPTCHA'] = $cap['image'];
                    $data['ERROR'] = True;
                    $data['ERR_MSG'] = 'خطأ في السؤال الأمني';

                    $array = array(
                        'captcha'   => $cap['word']
                    );
                    $this->session->set_userdata($array);
                    $data['CONTENT'] = "register";
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
                $data['CONTENT'] = "register";
            }
        }
        $data['TITLE'] = "-- التسجيل";
        $this->core->load_template($data);
    }
    
    public function check(){
        if($this->input->is_ajax_request()){
            if(!$_POST){
                $type = $this->uri->segment(3, 0);
                $username = $this->uri->segment(4, 0);
            }else{
                $type = $this->input->post('type',true);
                $username = $_POST['value'];
            }
            if (strlen($username) != strlen(utf8_decode($username)))
                die('1');
            if($type == 'username')
                $this->db->where('username',$username);
            elseif($type == 'email')
                $this->db->where('email',$username);
            else
                die('2');
            $check = $this->users->getUsers();
            if(is_bool($check))
                die('0');
            else
                die('3');
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
}

?>
