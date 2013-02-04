<?php

/**
 * this class for add,edit and remove from log table
 * 
 * @author Faris Al-Otaibi
 */
class log extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('logs');
    }
    
    public function index(){
        if($this->core->checkPermissions('log','show','all')){
            if($_POST){
                $time = $this->input->post('time',true);
                $userId = $this->input->post('users',true);
                if($userId != 'all' && is_numeric($userId))
                    $this->db->where('users_id',$userId);
                
                switch ($time){
                    
                    case 'day':
                        $date = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
                        $this->db->where('date >',$date);
                        break;
                    
                    case 'month':
                        $date = mktime(0,0,0,date("m")-1,date("d"),date("Y"));
                        $this->db->where('date >',$date);
                        break;
                    
                    case 'year':
                        $date = mktime(0,0,0,date("m"),date("d"),date("Y")-1);
                        $this->db->where('date >',$date);
                        break;
                    
                    case 'all':
                    default :
                        break;
                }
                $data['LOGS'] = $this->logs->getLogs('all');
                $data['CONTENT'] = "log";
                $data['STEP'] = 'view';
            }else{
                $data['CONTENT'] = "log";
                $data['STEP'] = 'init';
                $data['USERS'] = $this->users->getUsers();
            }
            $data['NAV'] = array(
                base_url()          => "الصفحة الرئيسية",
                base_url().'admin'  => "لوحة التحكم",
                base_url().'log'   => "إدارة السجلات",
            );
            $data['TITLE'] = "-- السجلات";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
}

?>
