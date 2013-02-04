<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of slider
 *
 * @author faris2007
 */
class slider extends CI_Controller {
    
    
    public function __construct() {
        parent::__construct();
        $this->load->model("sliders");
    }
    
    public function index(){
        if($this->core->checkPermissions('slider','all','all')){
            $this->show();
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function show(){
        $segments = $this->uri->segment_array();
        $filter = isset($segments[3])? $segments[3]:'all';
        if($this->core->checkPermissions('slider','show','all')){
            if($_POST){
                foreach ($_POST as $key => $value)
                {
                    if(@ereg('slider_', $key)){
                        $keyArr = explode('_', $key);
                        $store = array(
                            'sort_id'   => $this->input->post($key,true)
                        );
                        $this->sliders->updateSlider($keyArr[1],$store);
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
            $data['SLIDERS'] = $this->sliders->getSlider("all");
            $data['CONTENT'] = "slider";
            $data['STEP'] = 'show';
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'slider'   => "إدارة واجهة العرض",
            );
            $data['TITLE'] = "-- إدارة واجهة العرض";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
        
    }
    
    public function add(){
        if($this->core->checkPermissions('slider','add','all')){
            if($_POST){
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = md5(rand(0, 995499));
                    
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload())
                    {
                        $data = $this->upload->data();
                        $picUrl = base_url()."uploads/".$data['file_name'];
                        $store = array(
                            'slider_name'   => $this->input->post('name',true),
                            'url'           => $this->input->post('url',true),
                            'sort_id'       => $this->input->post('sort',true),
                            'picture'       => $picUrl,
                            'desc'          => $this->input->post('desc',true),
                            'isHidden'      => 1,
                            'isDelete'      => 0
                        );
                        if($this->sliders->addNewSlider($store)){
                            $data['CONTENT'] = 'msg';
                            $data['TITLE'] = "-- إدارة واجهة العرض";
                            $url = base_url().'slider';
                            $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة واجهة العرض أضغط هنا");
                        }else{
                            $data['CONTENT'] = "slider";                                     
                            $data['STEP'] = 'add';
                            $data['ERROR'] = true;
                            $data['ERR_MSG'] = 'لا تستطيع أضافة عنصر جديد لمشكلة في البيانات';
                        }
                    }else{
                        $data['CONTENT'] = "slider";
                        $data['STEP'] = 'add';
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'حدثت مشكلة في رفع الملف';
                    }
            }else{
                $data['CONTENT'] = "slider";
                $data['STEP'] = 'add';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'slider'   => "إدارة واجهة العرض",
            );
            $data['TITLE'] = "-- إدارة واجهة العرض -- أضافة عنصر جديد";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function edit(){
        $segments = $this->uri->segment_array();
        $sliderId = isset($segments[3])? $segments[3]:NULL;
        if($this->core->checkPermissions('slider','edit',$sliderId)){
            $sliderInfo = $this->sliders->getSlider($sliderId);
            if(is_bool($sliderInfo))
                redirect(STD_CMS_ERROR_PAGE);
            if($_POST){
                if($this->input->post("update",true) == 1){
                    
                    $config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = md5(rand(0, 995499));
                    
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload())
                    {
                        $data = $this->upload->data();
                        $picUrl = base_url()."uploads/".$data['file_name'];
                    }
                    $store['picture'] = $picUrl;
                }
                $store = array(
                    'slider_name'   => $this->input->post('name',true),
                    'url'           => $this->input->post('url',true),
                    'sort_id'       => $this->input->post('sort',true),
                    'desc'          => $this->input->post('desc',true)
                );
                if($this->sliders->updateSlider($sliderId,$store)){
                    $data['CONTENT'] = 'msg';
                    $data['TITLE'] = "-- إدارة واجهة العرض";
                    $url = base_url().'slider';
                    $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للإدارة واجهة العرض أضغط هنا");
                }else{
                    $data['SLIDER_NAME'] = $sliderInfo[0]->slider_name;
                    $data['SLIDER_URL'] = $sliderInfo[0]->url;
                    $data['SLIDER_SORT'] = $sliderInfo[0]->sort_id;
                    $data['SLIDER_PICTURE'] = $sliderInfo[0]->picture;
                    $data['SLIDER_DESC'] = $sliderInfo[0]->desc;
                    $data['CONTENT'] = "slider";                                     
                    $data['STEP'] = 'edit';
                    $data['ERROR'] = true;
                    $data['ERR_MSG'] = 'لا تستطيع تعديل العنصر لمشكلة في البيانات';
                }           
            }else{
                $data['SLIDER_NAME'] = $sliderInfo[0]->slider_name;
                $data['SLIDER_URL'] = $sliderInfo[0]->url;
                $data['SLIDER_SORT'] = $sliderInfo[0]->sort_id;
                $data['SLIDER_PICTURE'] = $sliderInfo[0]->picture;
                $data['SLIDER_DESC'] = $sliderInfo[0]->desc;
                $data['CONTENT'] = "slider";
                $data['STEP'] = 'edit';
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'slider'   => "إدارة واجهة العرض",
            );
            $data['TITLE'] = "-- إدارة القوائم -- تعديل القائمة ";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $sliderId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'enable'    => 'التفعيل',
                'disable'   => 'التعطيل',
                'delete'    => 'الحذف',
                'restore'   => 'الأستعادة'
            );
            if(is_null($type) || $sliderId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $slider = $this->sliders->getSlider($sliderId);
            if(is_bool($slider))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('slider','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die('ليس لديك صلاحية الحذف');
            else if($type == 'enable' || $type == 'disable')
                if($this->core->checkPermissions('slider','active','all')){
                    $store = array(
                        'isHidden' => ($type == 'enable')? 0 : 1
                    );
                }else
                    die('ليس لديك صلاحية التفعيل');
            else
                die('خطأ - خطأ في الرابط');
            if($this->sliders->updateSlider($sliderId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
}

?>
