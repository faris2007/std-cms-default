<?php

/**
 * this class for add,edit and remove from course table
 * 
 * @author Faris Al-Otaibi
 */
class order extends CI_Controller {
    
    
    public function __construct() {
        parent::__construct();
        $this->load->model('orders');
        $this->load->model('courses');
    }
    
    public function index(){
        if($this->core->checkPermissions('course','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $filter = isset($segments[3])? $segments[3]:'all';
        if($this->core->checkPermissions('course','show','all')){
            switch ($filter){
                case 'enable':
                    $this->db->where('isAccept',1);
                    break;
                
                case 'disable':
                    $this->db->where('isAccept',0);
                    break;
                
                case 'all':
                default :
                    break;
            }
            $data['FILTER'] = $filter;
            $data['ORDERS'] = $this->orders->getOrder("all");
            $data['CONTENT'] = "order";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'order'   => "إدارة الدورات",
            );
            $data['TITLE'] = "-- إدارة الدورات";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function course(){
        $segments = $this->uri->segment_array();
        $courseId = isset($segments[3])? $segments[3]:0;
        $filter = isset($segments[4])? $segments[4]:'all';
        if($this->core->checkPermissions('order','show','all')){
            $courseInfo = $this->courses->getCourse($courseId);
            if(is_bool($courseInfo))
                redirect(STD_CMS_ERROR_PAGE);
            
            switch ($filter){
                case 'enable':
                    $this->db->where('isAccept',1);
                    break;
                
                case 'disable':
                    $this->db->where('isAccept',0);
                    break;
                
                case 'all':
                default :
                    break;
            }
            $this->db->where('course_id',$courseId);
            $data['FILTER'] = $filter;
            $data['ORDERS'] = $this->orders->getOrder("all");
            if($_POST && $this->core->checkPermissions('order','show','all')){
                $this->load->library('email');
                $site_name = $this->core->getSettingByName("site_name");
                $site_email = $this->core->getSettingByName("site_email");
                $list = $data['ORDERS'];
                $this->email->set_mailtype("html");
                foreach ($list as  $address)
                {
                    $this->email->clear();

                    $this->email->to($this->users->getEmail($address->users_id));
                    $this->email->from($site_email, '('.$site_name.'):');
                    $this->email->subject('('.$site_name.'):',$this->input->post('title',true));
                    $this->email->message($this->input->post('content'));
                    $this->email->send();
                }
                $data['SENDMSG'] = true;
            }else
                $data['SENDMSG'] = false;
            $data['CONTENT'] = "order";
            $data['STEP'] = 'course';
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'order'   => "إدارة الدورات",
                base_url().'order/course/'.$courseId   => $courseInfo[0]->course_name,
            );
            $data['TITLE'] = "-- إدارة الدورات";
            $data['COURSE_NAME'] = $courseInfo[0]->course_name;
            $data['COURSE_PRICE'] = $courseInfo[0]->price;
            $data['COURSE_START_DATE'] = date('Y-m-d',$courseInfo[0]->course_start);
            $data['COURSE_LENGTH'] = $courseInfo[0]->course_length;
            $data['COURSE_LOCATION'] = $courseInfo[0]->course_location;
            $data['COURSE_REGISTER_DATE'] = date('Y-m-d',$courseInfo[0]->course_register_end);
            $data['COURSE_CAPACITY'] = $courseInfo[0]->course_capacity;
            $data['COURSE_ORDERS'] = (!is_bool($data['ORDERS'])) ? count($data['ORDERS']) : 0;
            $data['COURSE_ID'] = $courseId;
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function user(){
        $segments = $this->uri->segment_array();
        $userId = isset($segments[3])? $segments[3]:0;
        $filter = isset($segments[4])? $segments[4]:'all';
        if($this->core->checkPermissions('order','show','all') || ($this->users->isLogin() && $this->users->isActive())){
            if(!$this->core->checkPermissions('order','show','all') || $userId == 0)
                $userId = $this->users->getInfoUser('id');
            $userInfo = $this->users->getUser($userId);
            if(is_bool($userInfo))
                redirect(STD_CMS_ERROR_PAGE);
            
            if($_POST && $this->core->checkPermissions('order','show','all')){
                $this->load->library('email');
                $site_name = $this->core->getSettingByName("site_name");
                $site_email = $this->core->getSettingByName("site_email");
                $this->email->set_mailtype("html");
                $this->email->to($userInfo->email);
                $this->email->from($site_email, '('.$site_name.'):');
                $this->email->subject('('.$site_name.'):',$this->input->post('title',true));
                $this->email->message($this->input->post('content'));
                $this->email->send();
                $data['SENDMSG'] = true;
            }else
                $data['SENDMSG'] = false;
            switch ($filter){
                case 'enable':
                    $this->db->where('isAccept',1);
                    break;
                
                case 'disable':
                    $this->db->where('isAccept',0);
                    break;
                
                case 'all':
                default :
                    break;
            }
            $this->db->where('users_id',$userId);
            $data['FILTER'] = $filter;
            $data['ORDERS'] = $this->orders->getOrder("all");
            $data['CONTENT'] = "order";
            $data['STEP'] = 'user';
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'order'   => "إدارة الدورات",
                base_url().'order/user/'.$userId   => $userInfo->full_name,
            );
            $data['TITLE'] = "-- إدارة الدورات";
            $data['USER_NAME'] = $userInfo->full_name;
            $data['USER_EMAIL'] = $userInfo->email;
            $data['USER_MOBILE'] = $userInfo->mobile;
            $data['USER_ID'] = $userId;
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $orderId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'order'     => 'الطلب',
                'cancel'    => 'الغاء الطلب'
            );
            if(is_null($type) || $orderId == 0)
                die('خطأ - مشكلة في الرابط');
            if($type != 'order')
                $order = $this->orders->getOrder($orderId);
            
            if(is_bool($order))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'order' || $type == 'cancel'){
                if($type == 'order'){
                    $store = array(
                        'users_id'      => $this->users->getInfoUser('id'),
                        'course_id'     => $orderId,
                        'isAccept'      => 0
                    );
                    if($this->orders->addNewOrder($store))
                        die('1 - نجحت عملية '.$names[$type]);
                    else
                        die('خطأ - لم تنجح عملية '.$names[$type]);
                }else{
                    if($this->orders->deleteOrder($orderId))
                        die('1 - نجحت عملية '.$names[$type]);
                    else
                        die('خطأ - لم تنجح عملية '.$names[$type]);
                }
            }else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('order','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die('ليس لديك صلاحية التفعيل');
            else
                die('خطأ - خطأ في الرابط');
            if($this->orders->updateOrder($orderId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
    
    
    
    
}

?>
