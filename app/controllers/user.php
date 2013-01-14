<?php

/**
 * this class for add,edit and remove from user table
 * 
 * @author Faris Al-Otaibi
 */
class user extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        if($this->core->checkPermissions('users','show','all')){
            $this->show();
        }else 
            redirect ('login/permission');
    }
    
    public function show(){
        if($this->core->checkPermissions('users','show','all')){
            $segments = $this->uri->segment_array();
            $filter = isset($segments[3])? $segments[3]:'all';
            switch ($filter){
                
                case 'enable':
                    $this->db->where('isActive',1);
                    break;
                
                case 'disable':
                    $this->db->where('isActive',0);
                    break;
                
                case 'delete':
                    $this->db->where('isDelete',1);
                    break;
                
                case 'undelete':
                    $this->db->where('isDelete',0);
                    break;
                
                case 'all':
                default :
                    break;
            }
            $data['FILTER'] = $filter;
            $data['USERS'] = $this->users->getUsers();
            $data['CONTENT'] = "user";
            $data['STEP'] = 'show';
            $data['TITLE'] = "-- إدارة الإعضاء";
        }else
            redirect ('login/permission');
        $this->core->load_template($data);
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
    
    public function add(){
        if($this->core->checkPermissions('users','add','all')){
            $this->load->model('groups');
            if($_POST){
                $password = $this->input->post('password',true);
                $repassword = $this->input->post('repassword',true);
                if($password == $repassword){
                    if($this->__check('username', $this->input->post('username',true)) && $this->__check('email', $this->input->post('email',true))){
                        $store = array(
                            'username'      => $this->input->post('username',true),
                            'full_name'     => $this->input->post('fullName',true),
                            'email'         => $this->input->post('email',true),
                            'password'      => $password,
                            'mobile'        => $this->input->post('mobile',true),
                            'isBanned'      => 0,
                            'isDelete'      => 0,
                            'isActive'      => 1,
                            'group_id'      => $this->input->post('group_id',true)
                        );
                        $this->users->addNewUser($store);
                        $this->load->library('email');
                        $site_name = $this->settings->getSettingByName("site_name");
                        $site_email = $this->settings->getSettingByName("site_email");

                        $this->email->from($site_email->value, 'CMS ('.$site_name->value.'):');
                        $this->email->to($this->input->post('email',true));

                        $this->email->subject('CMS ('.$site_name->value.'): تسجيل جديد');
                        $message = '
                            <p>شكراً لأختيارك موقعنا نتمنى لك التوفيق</p>
                            <p>هذه الرسالة تأتي لتأكيد التسجيل لدينا </p>
                            <p>اسم المستخدم  :'.$this->input->post('username',true).'</p>
                        ';
                        $this->email->message($message);

                        $this->email->send();
                        $data['CONTENT'] = "msg";
                        $data['MSG'] = 'شكراً لتسجيلك في موقعنا <br/> تنبيه: قد يصل الأيميل للبريد المهمل الرجاء التأكد منه';

                    }else{
                        $data['ERROR'] = True;
                        $data['ERR_MSG'] = 'خطأ انت مسجل لدينا سابق يمكنك استخدام هذا <a href="<?=base_url()?>login/resetpassword">النموذج</a> لأستعادة كلمة المرور';
                        $data['CONTENT'] = "user";
                        $data['GROUPS'] = $this->groups->getGroups('all');
                        $data['STEP'] = 'add';
                    }
                }else{
                    $data['ERROR'] = True;
                    $data['ERR_MSG'] = 'خطأ في أحدى كلمات المرور يبدو انها غير متطابقة';
                    $data['GROUPS'] = $this->groups->getGroups('all');
                    $data['CONTENT'] = "user";
                    $data['STEP'] = 'add';
                }
            }else{
                $data['ERROR'] = FALSE;
                $data['ERR_MSG'] = '';
                $data['GROUPS'] = $this->groups->getGroups('all');
                $data['CONTENT'] = "user";
                $data['STEP'] = 'add';
            }
        }else 
            redirect ('login/permission');
        $this->core->load_template($data);
    }
    
    public function edit(){
        $this->load->model('groups');
        if($this->users->isLogin()){
            $segments = $this->uri->segment_array();
            if($this->core->checkPermissions('users','edit','all')){
                $userId = isset($segments[3])? $segments[3]:$this->users->getInfoUser('id');
                $data['ADMIN'] = true;
            }else{ 
                $userId = $this->users->getInfoUser('id');
                $data['ADMIN'] = false;
            }
            $userInfo = $this->users->getUser($userId);
            if(is_bool($userInfo))
                show_404 ();
            
            if($_POST){
                $password = $this->input->post('password',true);
                $repassword = $this->input->post('repassword',true);
                if((!empty($password) && ($password == $repassword)) || empty($password)){
                    $store = array(
                            'username'      => $this->input->post('username',true),
                            'full_name'     => $this->input->post('fullName',true),
                            'email'         => $this->input->post('email',true),
                            'mobile'        => $this->input->post('mobile',true),
                            'group_id'      => $this->input->post('group_id',true)
                    );
                    if(!empty($password))
                        $store['password'] = $password;
                    
                    if($this->users->updateUser($userId,$store)){
                        $data['CONTENT'] = 'msg';
                        if($data['ADMIN'] == true)
                            $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor(base_url().'user', "للعودة للإدارة الإعضاء");
                        else
                            $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor(base_url().'', "للعودة للصفحة الرئيسية");
                    }else{
                        $data['username'] = $userInfo->username;
                        $data['fullname'] = $userInfo->full_name;
                        $data['email'] = $userInfo->email;
                        $data['mobile'] = $userInfo->mobile;
                        $data['group_id'] = $userInfo->group_id;
                        $data['ERROR'] = True;
                        $data['GROUPS'] = $this->groups->getGroups('all');
                        $data['ERR_MSG'] = 'خطأ لم يستطيع البرنامج تحديث التعديلات الجديده';
                        $data['CONTENT'] = "user";
                        $data['STEP'] = 'edit';
                    }
                }else{
                    $data['username'] = $userInfo->username;
                    $data['fullname'] = $userInfo->full_name;
                    $data['email'] = $userInfo->email;
                    $data['mobile'] = $userInfo->mobile;
                    $data['group_id'] = $userInfo->group_id;
                    $data['ERROR'] = True;
                    $data['GROUPS'] = $this->groups->getGroups('all');
                    $data['ERR_MSG'] = 'خطأ في أحدى كلمات المرور يبدو انها غير متطابقة';
                    $data['CONTENT'] = "user";
                    $data['STEP'] = 'edit';
                }
            }else{
                $data['username'] = $userInfo->username;
                $data['fullname'] = $userInfo->full_name;
                $data['email'] = $userInfo->email;
                $data['mobile'] = $userInfo->mobile;
                $data['group_id'] = $userInfo->group_id;
                $data['ERROR'] = False;
                $data['GROUPS'] = $this->groups->getGroups('all');
                $data['ERR_MSG'] = '';
                $data['CONTENT'] = "user";
                $data['STEP'] = 'edit';
            }
        }else 
            redirect ('login/permission');
        $this->core->load_template($data);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $userId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'delete'    => 'الحذف',
                'restore'   => 'الأستعادة'
            );
            if(is_null($type) || $userId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $user = $this->users->getUser($userId);
            if(is_bool($user))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'delete' || $type == 'restore')
                $store = array(
                    'isDelete' => ($type == 'delete')? 1:0
                );
            else if($type == 'enable' || $type == 'disable')
                $store = array(
                    'isActive' => ($type == 'enable')? 1 : 0
                );
            else
                die('خطأ - خطأ في الرابط');
            
            if($this->users->updateUser($userId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            show_404 ();
    }
}

?>
