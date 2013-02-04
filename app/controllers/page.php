<?php

/**
 * this class for add,edit and remove from page table
 * 
 * @author Faris Al-Otaibi
 */
class page extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('pages');
    }
    
    public function index(){
        if($this->core->checkPermissions('page','show','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function view(){
        $segments = $this->uri->segment_array();
        $pageId = isset($segments[3])? $segments[3]:NULL;
        if(is_null($pageId))
            redirect(STD_CMS_ERROR_PAGE);
        
        if(!$this->core->checkPermissions('page','show','all')){
            $this->db->where('isHidden',0);
            $this->db->where('isDelete',0);
        }
        $pageInfo = $this->pages->getPage($pageId);
        if(is_bool($pageInfo))
            redirect(STD_CMS_ERROR_PAGE);
        
        $data['NAV'] = $this->core->getPath($pageId);
        $data['CONTENTPAGE'] = $pageInfo->content;
        $data['CONTENT'] = "page";
        $data['STEP'] = 'view';
        $data['TITLE'] = "-- " .$pageInfo->title;
        $this->core->load_template($data);
        
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $filter = isset($segments[4])? $segments[4]:'all';
        $parent_id = ($parent_id == 'all')? null:$parent_id;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('page','show',$value)){
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
            if(!is_null($parent_id))
                $data['NAV'] = $this->core->getPath($pageId,true);
            else
                $data['NAV'] = array(
                    base_url()          => "الصفحة الرئيسية",
                    base_url().'admin'  => "لوحة التحكم",
                    base_url().'page'   => "إدارة الصفحات",
                );    
            $parentId = (is_null($parent_id)) ? 'all' : $parent_id;
            $data['PAGES'] = $this->pages->getPages($parentId);
            $data['PARENTPAGE'] = $parent_id;
            $data['CONTENT'] = "page";
            $data['STEP'] = 'show';
            $data['TITLE'] = "-- إدارة الصفحات";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('page','add',$value)){
            if($_POST){
                    $store = array(
                        'title'         => $this->input->post('title',true),
                        'content'       => $this->input->post('content'),
                        'keyword'       => $this->input->post('keyword',true),
                        'desc'          => $this->input->post('desc',true),
                        'parent_id'     => $parent_id,
                        'publish_start' => date('Y-m-d H:i'),
                        'isHidden'      => 1,
                        'isDelete'      => 0
                    );
                    if($this->pages->addNewPage($store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة الصفحات";
                        $url = base_url().'page/show';
                        $url .= (!is_null($parent_id))?'/'.$parent_id:'';
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة الصفحات أضغط هنا");
                    }else{
                        $data['CONTENT'] = "page";
                        $data['STEP'] = 'add';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع أضافة صفحة جديده لمشكلة في البيانات';
                    }
            }else{
                $data['CONTENT'] = "page";
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'page'   => "إدارة الصفحات",
            );
            $data['TITLE'] = "-- إدارة الصفحات -- أضافة صفحة جديده";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $pageId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('page','edit',$pageId)){
            $pageInfo = $this->pages->getPage($pageId);
            if(is_bool($pageInfo))
                redirect(STD_CMS_ERROR_PAGE);
            
            if($_POST){
                    $store = array(
                        'title'     => $this->input->post('title',true),
                        'content'   => $this->input->post('content'),
                        'keyword'   => $this->input->post('keyword',true),
                        'desc'      => $this->input->post('desc',true)
                    );
                    if($this->pages->updatePage($pageId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة الصفحات";
                        $url = base_url().'page/show';
                        $url .= (!is_null($pageInfo->parent_id))?'/'.$pageInfo->parent_id:'';
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة الصفحات أضغط هنا");
                    }else{
                        $data['PAGETITLE'] = $pageInfo->title;
                        $data['PAGECONTENT'] = $pageInfo->content;
                        $data['PAGEKEY'] = $pageInfo->keyword;
                        $data['PAGEDESC'] = $pageInfo->desc;
                        $data['CONTENT'] = "page";
                        $data['STEP'] = 'edit';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع تحديث الصفحة  لمشكلة في البيانات';
                    }
            }else{
                $data['PAGETITLE'] = $pageInfo->title;
                $data['PAGECONTENT'] = $pageInfo->content;
                $data['PAGEKEY'] = $pageInfo->keyword;
                $data['PAGEDESC'] = $pageInfo->desc;
                $data['CONTENT'] = "page";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'page'   => "إدارة الصفحات",
            );
            $data['TITLE'] = "-- إدارة الصفحات -- تعديل الصفحة ";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    
    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $pageId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'delete'    => 'الحذف',
                'restore'   => 'الأستعادة'
            );
            if(is_null($type) || $pageId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $page = $this->pages->getPage($pageId);
            if(is_bool($page))
                die('خطأ - عفواً هذا الصفحة غير موجود');
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('page','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die('ليس لديك صلاحية الحذف');
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('page','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die('ليس لديك صلاحية التفعيل');
            else
                die('خطأ - خطأ في الرابط');
            if($this->pages->updatePage($pageId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
    
    public function error_page(){
        
        $data['CONTENT'] = "page";
        $data['STEP'] = 'error';
        $data['TITLE'] = "-- خطأ صفحة غير موجودة";
        $this->core->load_template($data);
    }
}

?>
