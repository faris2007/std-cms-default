<?php

/**
 * this class for add,edit and remove from menu table
 * 
 * @author Faris Al-Otaibi
 */
class cat extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('cats');
    }
    
    public function index(){
        if($this->core->checkPermissions('cat','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $filter = isset($segments[3])? $segments[3]:'all';
        if($this->core->checkPermissions('cat','show','all')){
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
            $data['CATS'] = $this->cats->getCat('all');
            $data['CONTENT'] = "cat";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'cat'   => "إدارة اقسام التواصل",
            );
            $data['TITLE'] = "-- إدارة الأقسام";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        if($this->core->checkPermissions('cat','add','all')){
            if($_POST){
                    $store = array(
                        'name'      => $this->input->post('name',true),
                        'desc'      => $this->input->post('desc',true),
                        'isHidden'  => 1,
                        'isDelete'  => 0
                    );
                    if($this->cats->addNewCat($store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة الأقسام";
                        $url = base_url().'cat/show';
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة الأقسام أضغط هنا");
                    }else{
                        $data['CONTENT'] = "cat";
                        $data['STEP'] = 'add';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع أضافة قسم جديد لمشكلة في البيانات';
                    }
            }else{
                $data['CONTENT'] = "cat";
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'cat'   => "إدارة الأقسام",
            );
            $data['TITLE'] = "-- إدارة الأقسام -- أضافة قسم جديد";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $catId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('cat','edit',$catId)){
            $catInfo = $this->cats->getCat($catId);
            if(is_bool($catInfo))
                redirect(STD_CMS_ERROR_PAGE);
            if($_POST){
                    $store = array(
                        'name'      => $this->input->post('name',true),
                        'desc'      => $this->input->post('desc',true)
                    );
                    if($this->cats->updateCat($catId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة الأقسام";
                        $url = base_url().'cat/show/';
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة الأقسام أضغط هنا");
                    }else{
                        $data['CATNAME'] = $catInfo->name;
                        $data['CATDESC'] = $catInfo->desc;
                        $data['CONTENT'] = "cat";
                        $data['STEP'] = 'edit';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع تحديث القسم لمشكلة في البيانات';
                    }
            }else{
                $data['CATNAME'] = $catInfo->name;
                $data['CATDESC'] = $catInfo->desc;
                $data['CONTENT'] = "cat";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'cat'   => "إدارة الأقسام",
            );
            $data['TITLE'] = "-- إدارة الأقسام -- تعديل القسم ";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $catId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'delete'    => 'الحذف',
                'restore'   => 'الأستعادة'
            );
            if(is_null($type) || $catId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $cat = $this->cats->getCat($catId);
            if(is_bool($cat))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('cat','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die('ليس لديك صلاحية الحذف');
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('cat','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die('ليس لديك صلاحية التفعيل');
            else
                die('خطأ - خطأ في الرابط');
            if($this->cats->updateCat($catId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
}

?>
