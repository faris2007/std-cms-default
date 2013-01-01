<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core {
	
    private $CI;
    private $Token,$Old_Token,$New_Token,$Security_Key,$User_Agent;
    public  $site_language = 'english';

    public function __construct()
    {
            // Load Class CI
            $this->CI =& get_instance();
            $this->load_setting();
    }

    public function load_setting()
    {
            $this->generate_token();
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
            $data['HEAD']['TITLE'] = (isset($temp_data['TITLE'])) ? $temp_data['TITLE'] : '';

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

            // Main Menu
            $data['MENU'] = (isset($temp_data['MENU'])) ? $temp_data['MENU'] : $this->CI->load->view('default/menu',NULL,TRUE);
			
            // Content
            $data['CONTENT'] = (isset($temp_data['CONTENT'])) ? $this->CI->load->view($temp_data['CONTENT'],$temp_data,TRUE) : '' ;

            // Main Template
            $load_only = (is_bool($load_only)) ? $load_only : FALSE;

            if ($load_only)
            {
                    return $this->CI->load->view('default/template',$data,TRUE);
            }else{
                    $this->CI->load->view('default/template',$data);
            }
    }
    
    public function checkPermissions($service_name = "admin",$function_name = "all",$value = "all",$otherValue = "all")
    {
        if(empty($service_name) || empty($function_name) || empty($value) || empty($otherValue))
            return false;
        $this->CI->load->model("users");
        if(!$this->CI->users->isLogin())
            return false;
        
        if($this->CI->users->checkifUser())
            return FALSE;
        
        if ($service_name == "admin")
        {
            if(!$this->CI->users->checkifUser())
                return true;
            else 
                return false;
        }else
        {
            return $this->CI->users->checkIfHavePremission($service_name,$function_name,$value,$otherValue);
        }
        
    }
    
    public function getServicesName($service_name = Null){
        $data = array(
            "page"      => "ادارة الصفحات" ,
            "menu"      => "إدارة القوائم",
            "users"     => "إدارة الأعضاء",
            "group"     => "إدارة المجموعات",
            "setting"   => "الإعدادات",
            "log"       => "السجل"
            );
        return ($service_name == Null ) ? $data : $data[$service_name];
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
    
    public function add_log($_service = '',$_function = '',$_action = '',$_parem = '')
    {
        /**
         * SERVICE => Controller Name
         * FUNCTION => Function Name
         * ACTION => Num Action ( Get Str from lang )
         * PAREM => Exp ( 1,ADD,LOGOUT )
         */        
        $data = array('USER_ID' => $this->CI->users->get_info_user("id"),
                      'SERVICE' => $_service,
                      'FUNCTION' => $_function,
                      'ACTION' => $_action,
                      'PAREM' => $_parem,
                      'TIMESTAMP' => time());
        $this->db->insert('logs', $data); 
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
            $result = array(
                0 => array(
                    'url'   => base_url(),
                    'name'  => "الصفحة الرئيسية"
                )
            );
            foreach ($path as $value){
                $item = unserialize($value);
                $result[] = array(
                    'url'   => base_url().'page/'.$item->id,
                    'name'  => $item->title
                );
            }
                
        }
        return $result;
    }
}

?>
