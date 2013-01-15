<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core {
	
    private $CI;
    private $Token,$Old_Token,$New_Token,$Security_Key,$User_Agent;
    public  $site_language = 'english';
    private $site_name,$site_style;

    public function __construct()
    {
            // Load Class CI
            $this->CI =& get_instance();
            if($this->CI->uri->segment(1, 0) !== 'install')
                $this->load_setting();
    }

    public function load_setting()
    {
            $this->generate_token();
            $this->CI->load->model('settings');            
            $title = $this->CI->settings->getSettingByName("site_name");
            $this->site_name = (!is_bool($title))? $title->value : '' ;
            $style = $this->CI->settings->getSettingByName("style");
            $this->site_style = (!is_bool($style)) ? $style->value : 'default';
            $enable = $this->CI->settings->getSettingByName("site_enable");
            if(!is_bool($enable)){
                if($enable->value == 0) {
                    $disable = TRUE;
                    $this->CI->load->model('users');
                    if($this->CI->users->isLogin()){
                            $enableForGroup = $this->CI->settings->getSettingByName("disable_except_group");
                            if($this->CI->users->getInfoUser('group') == $enableForGroup->value)
                                $disable = FALSE;
                        }
                    if($this->CI->uri->segment(1, 0) !== 'login' && $disable){
                        $disable_msg = $this->CI->settings->getSettingByName("disable_msg");
                        exit($this->load_template(array(
                            'CONTENT'   => 'msg',
                            'MSG'       => nl2br($disable_msg->value),
                            'DISABLE'   => TRUE
                        ),true));
                    }
                }
            }
            
    }

    public function generate_token()
    {
            // Token
            $this->Security_Key = sha1(rand(1000,9999) * rand(1000,9999));
            $this->Old_Token = $this->CI->encrypt->decode($this->CI->session->userdata('Token'));
            $this->Token = $this->CI->encrypt->decode($this->CI->session->userdata('New_Token'));
            $this->User_Agent = $this->CI->agent->agent_string();
            $this->New_Token = $this->CI->encrypt->encode($this->User_Agent . '|' . $this->Security_Key  . '|' . time());
            $this->Token = ($this->Token != '') ? $this->Token : $this->New_Token;
            
    }

    public function token($generate = FALSE)
    {
            $generate = (is_bool($generate)) ? $generate : FALSE;

            if ($generate)
            {
                    return $this->New_Token;
            }else{
                    return $this->Token;
            }
    }

    public function check_token($redirect = TRUE,$token = '')
    {
            $redirect = (is_bool($redirect)) ? $redirect : FALSE;
            $token = ($token == '') ? $this->CI->input->post('token', TRUE) : $token;
            if ($this->CI->input->post('token', TRUE) == $this->Token)
            {
                return TRUE;
            }else{
                if ($redirect)
                {
                    redirect();
                }else{
                    return FALSE;
                }
            }
    }
 
    public function load_template($temp_data = array(),$load_only = FALSE)
    {

            // Page Title
            $data['HEAD']['TITLE'] = (isset($temp_data['TITLE'])) ? $this->site_name.' '.$temp_data['TITLE'] :$this->site_name;

            // Meta	
            $data['HEAD']['META']['ROBOTS'] = (isset($data['HEAD']['META']['ROBOTS'])) ? 'none' : 'all';
            $data['HEAD']['META']['DESC'] = (isset($data['HEAD']['META']['DESC'])) ? $data['HEAD']['META']['DESC'] : '';
            $data['HEAD']['META']['KW'] = (isset($data['HEAD']['META']['KW'])) ? $data['HEAD']['META']['KW'] : '';
            $data['HEAD']['META']['META'] = array(
                    array('name' => 'robots', 'content' => $data['HEAD']['META']['ROBOTS']),
                    array('name' => 'description', 'content' => $data['HEAD']['META']['DESC']),
                    array('name' => 'keywords', 'content' => $data['HEAD']['META']['KW'])
            );

            // Other Code ( HTML -> HEAD )
            $data['HEAD']['OTHER'] = (isset($temp_data['HEAD'])) ? $temp_data['HEAD'] : '';
            
            // Main Menu Data
            $menu['MAINMENU'] = $this->getMenuPyParentID(NULL);
            
            // Main Menu
            $data['MENU'] = (isset($temp_data['MENU'])) ? $temp_data['MENU'] : $this->CI->load->view($this->site_style.'/menu',$menu,TRUE);
            // Change style if install
            $this->site_style = (isset($temp_data['isInstall']))? 'install' : $this->site_style;
            
            // Url for folder of style
            $data['STYLE_FOLDER'] = base_url().'style/'.  $this->site_style.'/';
            
            // Url for folder of style for get from content file
            $temp_data['STYLE_FOLDER'] = base_url().'style/'.  $this->site_style.'/';
            // Check If the file is exist
            $contentFile = (file_exists('./app/views/'.$this->site_style.'/controller/'.$temp_data['CONTENT'].'.php') && $this->site_style != 'default') ? $this->site_style.'/controller/'.$temp_data['CONTENT'] : 'default/controller/'.$temp_data['CONTENT'];
            // Load Model Of users
            $this->CI->load->model('users');
            
            // Load User Profile
            $userInfo = ($this->CI->users->isLogin())? $this->CI->users->getUser($this->CI->users->getInfoUser('id')):FALSE;
            
            // Remove Improtant attrabute
            if($userInfo){
                
                // Remove Password
                unset($userInfo->password);
                
                // Remove  new Password
                unset($userInfo->new_password);
                
                // Remove isBanned
                unset($userInfo->isBanned);
                
                // Remove isDelete
                unset($userInfo->isDelete);
                
                // Remove isActive
                unset($userInfo->isActive);
                
                // Remove group_id
                unset($userInfo->group_id);
            
                // Check if the User is Admin
                $userInfo->isAdmin = $this->CI->users->isAdmin();
            }
            // Store Information about user in $temp_data
            $temp_data['userInfo'] = $userInfo;
            
            // Content
            $data['CONTENT'] = (isset($temp_data['CONTENT'])) ? $this->CI->load->view($contentFile,$temp_data,TRUE) : '' ;
            
            // Navbar for website
            $data['NAV'] = (isset($temp_data['NAV'])) ? $temp_data['NAV']: false;
            
            // Copy Right
            $data['DEVELOPMENT'] = 'تصميم وتطوير '.  anchor('https://std-hosting.com/', 'الشركة السعودية للتصاميم التقنية') .'.';
            
            // Disable Website
            $data['DISABLE'] = (isset($temp_data['DISABLE'])) ? $temp_data['DISABLE'] : false;
            
            // Main Template
            $load_only = (is_bool($load_only)) ? $load_only : FALSE;

            if ($load_only)
            {
                    return $this->CI->load->view($this->site_style.'/template',$data,TRUE);
            }else{
                    $this->CI->load->view($this->site_style.'/template',$data);
            }
    }
    
    public function checkPermissions($service_name = "admin",$function_name = "all",$value = "all")
    {
        if(empty($service_name) || empty($function_name) || empty($value) )
            return false;
        $this->CI->load->model("users");
        if(!$this->CI->users->isLogin())
            return false;
        
        if(!$this->CI->users->isAdmin())
            return FALSE;
        
        $functions = $this->getFunctionsName();
        if ($service_name == "admin")
        {
            if($this->CI->users->isAdmin()){
                $action = $functions[$service_name][$function_name];
                $this->add_log($action);
                return true;
            }else 
                return false;
        }else
        {
            
            if($this->CI->users->checkIfHavePremission($service_name,$function_name,$value)){
                $action = $functions[$service_name][$function_name];
                $this->add_log($action);
                return true;
            }else{
                if($service_name == 'menu' && $value != 'all'){
                    $this->CI->load->model('menus');
                    $parentData = $this->CI->menus->getParentThisMenu($value);
                    $data = explode(',', $parentData);
                    foreach ($data as $row){
                        if($this->CI->users->checkIfHavePremission($service_name,$function_name,$row->id))
                        {        
                            $action = $functions[$service_name][$function_name];
                            $this->add_log($action);
                            return true;
                        }
                    }
                    return false;
                }else if($service_name == 'page' && $value != 'all'){
                    $this->CI->load->model('pages');
                    $parentData = $this->CI->pages->getParentThisPage($value);
                    $data = explode(',', $parentData);
                    foreach ($data as $row){
                        if($this->CI->users->checkIfHavePremission($service_name,$function_name,$row->id))
                        {
                            $action = $functions[$service_name][$function_name];
                            $this->add_log($action);
                            return true;
                        }
                    }
                    return false;
                }else
                    return false;
            }
        }
        
    }
    
    public function getServicesName($service_name = 'all'){
        $data = array(
            "page"      => "ادارة الصفحات" ,
            "menu"      => "إدارة القوائم",
            "users"     => "إدارة الأعضاء",
            "group"     => "إدارة المجموعات",
            "setting"   => "الإعدادات",
            "log"       => "السجل"
            );
        if($service_name == 'all' )
            return $data; 
        elseif(isset($data[$service_name]))
            return $data[$service_name];
        else
            return 'غير معروف';
    }
    
    public function getFunctionsName($service_name ="all"){
        $data = array(
            "page"      => array(
                    "all"       => "جميع الصلاحيات",
                    "active"    => "تنشيط",
                    "show"      => "استعراض البيانات",
                    "add"       => "أضافة",
                    "edit"      => "تعديل",
                    "delete"    => "حذف"
                    ),
            "menu"      => array(
                    "all"       => "جميع الصلاحيات",
                    "active"    => "تنشيط",
                    "show"      => "استعراض البيانات",
                    "add"       => "أضافة",
                    "edit"      => "تعديل",
                    "delete"    => "حذف"
                    ),
            "users"     => array(
                    "all"       => "جميع الصلاحيات",
                    "active"    => "تنشيط",
                    "show"      => "استعراض البيانات",
                    "add"       => "أضافة",
                    "edit"      => "تعديل",
                    "delete"    => "حذف"
                    ),
            "group"     => array(
                    "all"       => "جميع الصلاحيات",
                    "active"    => "تنشيط",
                    "show"      => "استعراض البيانات",
                    "add"       => "أضافة",
                    "edit"      => "تعديل",
                    "delete"    => "حذف"
                    ),
            "setting"   => array(
                    "all"       => "جميع الصلاحيات",
                    "show"      => "استعراض البيانات",
                    "add"       => "أضافة",
                    "edit"      => "تعديل"
                    ),
            "log"       => array(
                    "all"       => "جميع الصلاحيات",
                    "show"      => "استعراض البيانات",
                    "delete"    => "حذف"
                    ),
            );
        return (isset($data[$service_name]))? $data[$service_name] : $data  ;
    }
    
    public function getMenuPyParentID($parentID = NULL){
        
        $this->CI->load->model('menus');
        $where = array(
            'isDelete'      => 0,
            'isHidden'      => 0
        );
        $result = $this->CI->menus->getMenuWithChild($parentID,$where);
        $data = array();
        if(!is_bool($result)){
            foreach ($result as $val){
                $child = $this->extractSubMenu($val['child']);
                $data[] = (!is_bool($child))? anchor('#',$val['content']->title).' '.$this->getSubMenu($val['child']): anchor($val['content']->url,$val['content']->title);
            }
        }
        
        return $data;
    }
    
    private function extractSubMenu($content){
        return unserialize($content); 
    }
    
    private function getSubMenu($content){
        
        $content = $this->extractSubMenu($content);
        $data = array();
        if(!is_bool($content)){
            foreach ($content as $val){
                $subMenu  = $this->getSubMenu($val['child']);
                $data[] = (!is_bool($subMenu))? anchor('#',$val['content']->title).' '.$subMenu : anchor($val['content']->url,$val['content']->title);
            }
        }else
            return false;
        
        return ul($data, array());
    }
    

    public function perpage($url = '',$total = 0,$cur_page = 0,$per_page = 30)
    {
        $this->CI->load->library('pagination');
        $config['base_url'] = base_url() . $url;
        $config['total_rows'] = $total;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 6;
        $config['num_links'] = 3;
        $config['cur_page'] = $cur_page;
        $this->CI->pagination->initialize($config);
        return $this->CI->pagination->create_links();        
    }
    
    public function message($_message = '',$_url = '',$_title = '',$_time = 200)
    {
        $data['MSG']['TITLE'] = $_title;
        $data['MSG']['MESSAGE'] = $_message;
        $data['MSG']['URL'] = $_url;
        $data['MSG']['TIME'] = $_time;
        $data['HEAD'] =  meta(array('name' => 'refresh', 'content' => $_time.';url='.$_url, 'type' => 'equiv'));
        $data['TITLE'] = 'MSG';
        $data['CONTENT'] = 'message';
        die($this->load_template($data,TRUE));
    }

    public function getTimeAsArray($time){
        $timeH = explode(":", $time);
        $data['hour'] = $timeH[0];
        $timeM = explode(" ", $timeH[1]);
        $data['min'] = $timeM[0];
        $data['am'] = $timeM[1];
        return $data;
    }
    
    public function add_log($action = Null)
    {
        $this->CI->load->model('users');
        $this->CI->load->model('logs');
        $location = $this->getServicesName($this->CI->uri->segment(1, 0));
        $data = array(
            'date'      => time(),
            'activity'  => (!is_null($action)) ? $action . ' '.$location : $location,
            'ip'        => $this->CI->input->ip_address(),
            'users_id'  => $this->CI->users->getInfoUser('id') 
        );
        $this->CI->logs->addNewLog($data);
    }
    
    function changeIfWindows($string){
        $this->CI->load->library('user_agent');
        if(empty($string))
            return "";
        if(@ereg('windows', strtolower($this->CI->agent->platform())))
            $newString = @iconv('UTF-8', 'Windows-1256', $string);
        else
            $newString = $string;
        return $newString;
    }
    
    public function getPath($pageId){
        if(empty($pageId))
            return FALSE;
        $this->CI->load->model("pages");
        
        $parentPage = $this->CI->pages->getParentThisPage($pageId);
        $result = FALSE;
        if(!is_bool($parentPage)){
            $path = explode(',', $parentPage);
            $result["".base_url().""] = "الصفحة الرئيسية";
            foreach ($path as $value){
                $item = unserialize($value);
                $result[base_url().'page/view/'.$item->id] = $item->title;
            }
                
        }
        return $result;
    }
}

?>
