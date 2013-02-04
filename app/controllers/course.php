<?php

/**
 * this class for add,edit and remove from course table
 * 
 * @author Faris Al-Otaibi
 */
class course extends CI_Controller {
    
    
    public function __construct() {
        parent::__construct();
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
                    $this->db->where('isHidden',0);
                    break;
                
                case 'disable':
                    $this->db->where('isHidden',1);
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
            $data['COURSES'] = $this->courses->getCourse("all");
            $data['CONTENT'] = "course";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'course'   => "إدارة الدورات",
            );
            $data['TITLE'] = "-- إدارة الدورات";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function available(){
        
        $userId = ($this->users->isLogin())? $this->users->getInfoUser('id') : '';
        $data['COURSES'] = $this->courses->getAvailableCourse($userId);
        $data['CONTENT'] = "course";
        $data['STEP'] = 'available';
        $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'course/available'   => "الدورات المتاحة",
            );
        $data['TITLE'] = "-- الدورات المتاحة";
        $this->core->load_template($data);
    }


    public function add(){
        if($this->core->checkPermissions('course','add','all')){
            if($_POST){
                $start_date = $this->input->post('start_date',true);
                $register_end = $this->input->post('register_end',true);
                $store = array(
                    'course_name'           => $this->input->post('name',true),
                    'price'                 => $this->input->post('price',true),
                    'course_start'          => strtotime($start_date),
                    'course_length'         => $this->input->post('length',true),
                    'course_location'       => $this->input->post('location',true),
                    'course_register_end'   => strtotime($register_end),
                    'course_capacity'       => $this->input->post('capacity',true),
                    'isHidden'              => 1,
                    'isDelete'              => 0
                );
                if($this->courses->addNewCourse($store)){
                    $data['CONTENT'] = 'msg';
                    $data['TITLE'] = "-- إدارة الدورات";
                    $url = base_url().'course';
                    $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة الدورات أضغط هنا");
                }else{
                    $data['CONTENT'] = "course";                                     
                    $data['STEP'] = 'add';
                    $data['ERROR'] = true;
                    $data['ERR_MSG'] = 'لا تستطيع أضافة دورة جديد لمشكلة في البيانات';
                }
                    
            }else{
                $data['CONTENT'] = "course";
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'course'   => "إدارة الدورات",
            );
            $data['TITLE'] = "-- إدارة الدورات -- أضافة دورة جديد";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $courseId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('course','edit',$courseId)){
            $courseInfo = $this->courses->getCourse($courseId);
            if(is_bool($courseInfo))
                redirect(STD_CMS_ERROR_PAGE);
            if($_POST){
                $start_date = $this->input->post('start_date',true);
                $register_end = $this->input->post('register_end',true);
                $store = array(
                    'course_name'           => $this->input->post('name',true),
                    'price'                 => $this->input->post('price',true),
                    'course_start'          => strtotime($start_date),
                    'course_length'         => $this->input->post('length',true),
                    'course_location'       => $this->input->post('location',true),
                    'course_register_end'   => strtotime($register_end),
                    'course_capacity'       => $this->input->post('capacity',true),
                    'isHidden'              => 1,
                    'isDelete'              => 0
                );
                if($this->courses->updateCourse($courseId,$store)){
                    $data['CONTENT'] = 'msg';
                    $data['TITLE'] = "-- إدارة الدورات";
                    $url = base_url().'course';
                    $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة الدورات أضغط هنا");
                }else{
                    $data['COURSE_NAME'] = $courseInfo[0]->course_name;
                    $data['COURSE_PRICE'] = $courseInfo[0]->price;
                    $data['COURSE_START_DATE'] = date('Y-m-d',$courseInfo[0]->course_start);
                    $data['COURSE_LENGTH'] = $courseInfo[0]->course_length;
                    $data['COURSE_LOCATION'] = $courseInfo[0]->course_location;
                    $data['COURSE_REGISTER_DATE'] = date('Y-m-d',$courseInfo[0]->course_register_end);
                    $data['COURSE_CAPACITY'] = $courseInfo[0]->course_capacity;
                    $data['CONTENT'] = "course";                                     
                    $data['STEP'] = 'edit';
                    $data['ERROR'] = true;
                    $data['ERR_MSG'] = 'لا تستطيع تعديل العنصر لمشكلة في البيانات';
                }           
            }else{
                $data['COURSE_NAME'] = $courseInfo[0]->course_name;
                $data['COURSE_PRICE'] = $courseInfo[0]->price;
                $data['COURSE_START_DATE'] = date('Y-m-d',$courseInfo[0]->course_start);
                $data['COURSE_LENGTH'] = $courseInfo[0]->course_length;
                $data['COURSE_LOCATION'] = $courseInfo[0]->course_location;
                $data['COURSE_REGISTER_DATE'] = date('Y-m-d',$courseInfo[0]->course_register_end);
                $data['COURSE_CAPACITY'] = $courseInfo[0]->course_capacity;
                $data['CONTENT'] = "course";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'course'   => "إدارة الدورات",
            );
            $data['TITLE'] = "-- إدارة الدورات -- تعديل الدورة ";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $courseId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'delete'    => 'الحذف',
                'restore'   => 'الأستعادة'
            );
            if(is_null($type) || $courseId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $course = $this->courses->getCourse($courseId);
            if(is_bool($course))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('course','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die('ليس لديك صلاحية الحذف');
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('course','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die('ليس لديك صلاحية التفعيل');
            else
                die('خطأ - خطأ في الرابط');
            if($this->courses->updateCourse($courseId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
    
    
    
    
}

?>
