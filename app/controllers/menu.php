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
    }
    
    public function index(){
        if($this->core->checkPermissions('menu','show','all')){
            $this->show();
        }else
            redirect ('login/permission');
    }
    
    public function show(){
        if($this->core->checkPermissions('menu','show','all')){
            $segments = $this->uri->segment_array();
            $parent_id = isset($segments[3])? $segments[3]:NULL;
            $data['MENUS'] = $this->menus->getMenus($parent_id);
            $data['TYPEMENU'] = ($parent_id == NULL) ? 'الرئيسية': 'الفرعية';
            $data['PARENTMENU'] = $parent_id;
            $data['CONTENT'] = "menu";
            $data['STEP'] = 'show';
            $data['TITLE'] = "-- إدارة القوائم";
            $this->core->load_template($data);
        }else
            redirect ('login/permission');
        
    }
    
    public function add(){
        $segments = $this->uri->segment_array();
        $parent_id = isset($segments[3])? $segments[3]:NULL;
        $value = (is_null($parent_id)) ? 'all' : $parent_id;
        if($this->core->checkPermissions('menu','add',$value)){
            if($_POST){
                    $store = array(
                        'title'     => $this->input->post('title',true),
                        'url'       => $this->input->post('url',true),
                        'sort_id'   => $this->input->post('sort',true),
                        'parent_id' => $parent_id,
                        'isHidden'  => 1,
                        'isDelete'  => 0
                    );
                    if($this->menus->addNewMenu($store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة القوائم";
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor(base_url().'menu'.(!is_null($parent_id))?'/show/'.$parent_id:'', "للعودة للإدارة القوائم أضغط هنا");
                    }else{
                        $data['CONTENT'] = "menu";
                        $data['STEP'] = 'add';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع أضافة قائمة جديده لمشكلة في البيانات';
                    }
            }else{
                $data['CONTENT'] = "menu";
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['TITLE'] = "-- إدارة القوائم -- أضافة قائمة جديده";
            $this->core->load_template($data);
        }else
            redirect ('login/permission');
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $menuId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('menu','edit',$menuId)){
            $menuInfo = $this->menus->getMenu($menuId);
            if(is_bool($menuInfo))
                show_404 ();
            if($_POST){
                    $store = array(
                        'title'     => $this->input->post('title',true),
                        'url'       => $this->input->post('url',true),
                        'sort_id'   => $this->input->post('sort',true),
                        'isHidden'  => 1,
                        'isDelete'  => 0
                    );
                    if($this->menus->updateMenu($menuId,$store)){
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- إدارة القوائم";
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor(base_url().'menu/show/'.$menuId, "للعودة للإدارة القوائم أضغط هنا");
                    }else{
                        $data['MENUTITLE'] = $menuInfo->title;
                        $data['MENUURL'] = $menuInfo->url;
                        $data['MENUSORT'] = $menuInfo->sort_id;
                        $data['CONTENT'] = "menu";
                        $data['STEP'] = 'edit';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع أضافة قائمة جديده لمشكلة في البيانات';
                    }
            }else{
                $data['MENUTITLE'] = $menuInfo->title;
                $data['MENUURL'] = $menuInfo->url;
                $data['MENUSORT'] = $menuInfo->sort_id;
                $data['CONTENT'] = "menu";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['TITLE'] = "-- إدارة القوائم -- تعديل القائمة ";
            $this->core->load_template($data);
        }else
            redirect ('login/permission');
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $menuId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'delete'    => 'الحذف'
            );
            if(is_null($type) || $menuId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $menu = $this->menus->getMenu($menuId);
            if(is_bool($menu))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'delete')
                $store = array(
                    'isDelete' => 1
                );
            else if($type == 'enable' || $type == 'disable')
                $store = array(
                    'isHidden' => ($type == 'enable')? 0 : 1
                );
            else
                die('خطأ - خطأ في الرابط');
            if($this->menus->updateMenu($menuId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            show_404 ();
    }
}

?>
