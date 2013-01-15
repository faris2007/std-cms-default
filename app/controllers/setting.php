<?php

/**
 * this class for add,edit and remove from setting table
 * 
 * @author Faris Al-Otaibi
 */
class setting extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        if($this->core->checkPermissions('setting','show','all')){
            if($_POST){
                 if($this->core->checkPermissions('setting','edit')){
                     $store = array(
                            0 => array(
                                    'name'      => "site_name",
                                    'value'     => $this->input->post('sitename',true)
                                ),
                            1 => array(
                                    'name'      => "site_url",
                                    'value'     => $this->input->post('siteurl',true)
                                ),
                            2 => array(
                                    'name'      => "site_email",
                                    'value'     => $this->input->post('siteemail',true)
                                ),
                            3 => array(
                                    'name'      => "style",
                                    'value'     => $this->input->post('style',true)
                                ),
                            4 => array(
                                'name'      => "site_enable",
                                'value'     => $this->input->post('siteenable',true)
                                ),
                            5 => array(
                                'name'      => "disable_msg",
                                'value'     => $this->input->post('disable_msg',true)
                                ),
                            6 => array(
                                'name'      => "disable_except_group",
                                'value'     => $this->input->post('group_disable',true)
                                ),
                            7 => array(
                                'name'      => "cms_register_enable",
                                'value'     => $this->input->post('registerenable',true)
                                ),
                            8 => array(
                                'name'      => "cms_register_group",
                                'value'     => $this->input->post('register_group',true)
                                ),
                            9 => array(
                                'name'      => "cms_register_active",
                                'value'     => $this->input->post('registeractive',true)
                                )
                            );
                    foreach ($store as $value)
                        $this->settings->updateSetting($value['name'],array('value'=> $value['value']));
                    $data['CONTENT'] = 'msg';
                    $data['TITLE'] = "-- أعدادات الموقع";
                    $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor(base_url().'setting', "للعودة للأعدادات أضغط هنا");
                }else
                     redirect ('login/permission');
            }else{
                $folder = get_dir_file_info('./app/views/', $top_level_only = TRUE);
                $data['STYLE'] = array_keys($folder);
                $this->load->model('settings');
                $this->load->model('groups');
                $settings = $this->settings->getSettings();
                if($settings){
                    $setting = array(
                        'site_name'             => 'SITENAME',
                        'site_url'              => 'SITEURL',
                        'site_email'            => 'SITEEMAIL',
                        'style'                 => 'STYLEVALUE',
                        'site_enable'           => 'SITEENABLE',
                        'disable_msg'           => 'DISABLE_MSG',
                        'disable_except_group'  => 'GROUPDISABLE',
                        'cms_register_enable'   => 'REGISTERENABLE',
                        'cms_register_group'    => 'GROUPREGSITER',
                        'cms_register_active'   => 'REGISTERACTIVE'
                    );
                    foreach ($settings as $value)
                        if(isset($setting[$value->name]))
                            $data[$setting[$value->name]] = $value->value;
                }
                $data['GROUP'] = $this->groups->getGroups('all');
                $data['CONTENT'] = 'setting';
            }
        }else
            redirect ('login/permission');
        $this->core->load_template($data);
    }
}

?>
