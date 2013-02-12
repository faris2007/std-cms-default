<?php

/**
 * this class for add,edit and remove from menu table
 * 
 * @author Faris Al-Otaibi
 */
class menu extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('menus');
        $this->load->model('pages');
    }
    
    public function index(){
        if($this->core->checkPermissions('menu','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $filter = isset($segments[4])? $segments[4]:'all';
        $parent_id = ($parent_id == 'all')? null:$parent_id;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('menu','show',$value)){
            if($_POST){
                foreach ($_POST as $key => $value)
                {
                    if(@ereg('menu_', $key)){
                        $keyArr = explode('_', $key);
                        $store = array(
                            'sort_id'   => $this->input->post($key,true)
                        );
                        $this->menus->updateMenu($keyArr[1],$store);
                    }
                }
            }
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
            $data['MENUS'] = $this->menus->getMenus($parent_id);
            $data['TYPEMENU'] = ($parent_id == NULL) ? 'الرئيسية': 'الفرعية';
            $data['PARENTMENU'] = $parent_id;
            $data['CONTENT'] = "menu";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'menu'   => "إدارة القوائم",
            );
            if(!is_null($parent_id))
            {
                $menuArray = $this->menus->getParentThisMenu($parent_id);
                $menus = explode(',', $menuArray);
                foreach ($menus as $val){
                    $menuOne = unserialize($val);
                    $data['NAV'][base_url().'menu/show/'.$menuOne->id] = $menuOne->title;
                }
            }
            $data['TITLE'] = "-- إدارة القوائم";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('menu','add',$value)){
            if($_POST){
                    $store = array(
                        'title'     => $this->input->post('title',true),
                        'url'       => ($this->input->post('type_url',true) == 'page')? base_url().'page/view/'.$this->input->post('page_num',true) :$this->input->post('url',true),
                        'sort_id'   => $this->input->post('sort',true),
                        'parent_id' => $parent_id,
                        'isHidden'  => 1,
                        'isDelete'  => 0
                    );
                    if($this->menus->addNewMenu($store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة القوائم";
                        $url = base_url().'menu';
                        $url .= (!is_null($parent_id))?'/show/'.$parent_id:'';
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة القوائم أضغط هنا");
                    }else{
                        $data['CONTENT'] = "menu";
                        $where = array(
                            'isDelete'  => 0,
                            'isHidden'  => 0
                        );
                        $this->db->where($where);
                        $data['PAGES'] = $this->pages->getPages('all');
                        $data['STEP'] = 'add';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع أضافة قائمة جديده لمشكلة في البيانات';
                    }
            }else{
                $data['CONTENT'] = "menu";
                $where = array(
                    'isDelete'  => 0,
                    'isHidden'  => 0
                );
                $this->db->where($where);
                $data['PAGES'] = $this->pages->getPages('all');
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'menu'   => "إدارة القوائم",
            );
            if(!is_null($parent_id))
            {
                $menuArray = $this->menus->getParentThisMenu($parent_id);
                $menus = explode(',', $menuArray);
                foreach ($menus as $val){
                    $menuOne = unserialize($val);
                    $data['NAV'][base_url().'menu/show/'.$menuOne->id] = $menuOne->title;
                }
            }
            
            $data['TITLE'] = "-- إدارة القوائم -- أضافة قائمة جديده";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $menuId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('menu','edit',$menuId)){
            $menuInfo = $this->menus->getMenu($menuId);
            if(is_bool($menuInfo))
                redirect(STD_CMS_ERROR_PAGE);
            if($_POST){
                    $store = array(
                        'title'     => $this->input->post('title',true),
                        'url'       => ($this->input->post('type_url',true) == 'page')? base_url().'page/view/'.$this->input->post('page_num',true) :$this->input->post('url',true),
                        'sort_id'   => $this->input->post('sort',true)
                    );
                    if($this->menus->updateMenu($menuId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة القوائم";
                        $url = base_url().'menu';
                        $url .= (!is_null($menuInfo->parent_id))?'/show/'.$menuInfo->parent_id:'';
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة القوائم أضغط هنا");
                    }else{
                        $data['MENUTITLE'] = $menuInfo->title;
                        $data['MENUURL'] = $menuInfo->url;
                        $data['MENUSORT'] = $menuInfo->sort_id;
                        $data['CONTENT'] = "menu";
                        $where = array(
                            'isDelete'  => 0,
                            'isHidden'  => 0
                        );
                        $this->db->where($where);
                        $data['PAGES'] = $this->pages->getPages('all');
                        $data['STEP'] = 'edit';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع تحديث القائمة لمشكلة في البيانات';
                    }
            }else{
                $data['MENUTITLE'] = $menuInfo->title;
                $data['MENUURL'] = $menuInfo->url;
                $data['MENUSORT'] = $menuInfo->sort_id;
                $data['CONTENT'] = "menu";
                $where = array(
                    'isDelete'  => 0,
                    'isHidden'  => 0
                );
                $this->db->where($where);
                $data['PAGES'] = $this->pages->getPages('all');
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'menu'   => "إدارة القوائم",
            );
            $menuArray = $this->menus->getParentThisMenu($menuId);
            if($menuArray){
                $menus = explode(',', $menuArray);
                foreach ($menus as $val){
                    $menuOne = unserialize($val);
                    $data['NAV'][base_url().'menu/show/'.$menuOne->id] = $menuOne->title;
                }
            }
            $data['TITLE'] = "-- إدارة القوائم -- تعديل القائمة ";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $menuId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'delete'    => 'الحذف',
                'restore'   => 'الأستعادة'
            );
            if(is_null($type) || $menuId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $menu = $this->menus->getMenu($menuId);
            if(is_bool($menu))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('menu','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die('ليس لديك صلاحية الحذف');
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('menu','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die('ليس لديك صلاحية التفعيل');
            else
                die('خطأ - خطأ في الرابط');
            if($this->menus->updateMenu($menuId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
}

?>
