<?php

/**
 * this class for add,edit and remove from ajax table
 * 
 * @author Faris Al-Otaibi
 */
class ajax extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model("datatables");
    }
    
    function index(){
        show_404();
    }
    
    private function __dataeditUsers($data,$key = "mobile"){


        for($i = 0 ; $i<count($data);$i++){
            $id = $data[$i]->id;
            $delimgdetail = array(
                'src'       => 'style/default/icon/del.png',
                'alt'       => 'حذف',
                'title'       => 'حذف',
                'onClick'   => "action('".  base_url()."group/action/deletep/".$id."','deletep','permission".$id."','".$id."')"
            );
            $delimg = img($delimgdetail);
            $data[$i]->$key = $delimg; 
        }
        return $data;
    }
    
    private function __changeWord(&$data){
        
        $services = $this->core->getServicesName('all');
        $functions = $this->core->getFunctionsName('all');
        for($i = 0 ; $i<count($data);$i++){
            $data[$i]->value = 'الجميع';
            $data[$i]->function_name = $functions[$data[$i]->service_name][$data[$i]->function_name];
            $data[$i]->service_name = $services[$data[$i]->service_name];
        }
        
        
    }


    function permission(){
        $this->load->model('permissions');
        $columns = array(
            "`permissions`.`service_name`",
            "`permissions`.`function_name`",
            "`permissions`.`value`",
            "`permissions`.`id`"
            );
        $segments = $this->uri->segment_array();
        $groupId = (isset($segments[3]))? $segments[3] : 'all';
        $this->datatables->beforeQuery($columns);
        $query = $this->permissions->getPermissions($groupId);
        $totalAfterfiltering = $this->datatables->getNumberOfRowForFilterData();
        $data['iTotalDisplayRecords'] = $totalAfterfiltering;
        $data['iTotalRecords'] = "".$this->permissions->getTotalPermissions($groupId)."";
        if($totalAfterfiltering >0 && $data['iTotalRecords'] >0){
            $this->__changeWord(&$query);
            $query = $this->__dataeditUsers($query,'id');
        }else{
            $query = array();
        }
        echo $this->datatables->afterQuery($data,$query);
    }
    
    function accepted(){
        
        $columns = array(
            "`Employee`.`contract_id`",
            "`users`.`idn`",
            "`users`.`en_name`",
            "`jobs`.`name`",
            "`users`.`mobile`",
            "`users`.`id`"
            );
        $this->datatables->beforeQuery($columns);
        $query = $this->users->getAllInfoUser(NULL,NULL,true);
        $totalAfterfiltering = $this->datatables->getNumberOfRowForFilterData();
        $data['iTotalDisplayRecords'] = $totalAfterfiltering;
        $data['iTotalRecords'] = "".$this->users->get_total_info_users()."";
        echo $this->datatables->afterQuery($data,$this->__dataeditUsers($query,'id'));
    }
}

?>
