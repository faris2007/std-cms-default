<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
        public function __construct() {
            parent::__construct();
            
        }
        
        public function index()
	{
            $this->load->model('settings');
            $homepage = $this->settings->getSettingByName('cms_home_page');
            if(!$homepage || $homepage->value == 'home'){
                $data['CONTENT'] = "home";
                $data['TITLE'] = "";
            }else{
                $this->load->model('pages');
                $pageId = $homepage->value;
                $pageInfo = $this->pages->getPage($pageId);
                if(is_bool($pageInfo))
                {
                    $data['CONTENT'] = "home";
                    $data['TITLE'] = "";
                }else{
                    $data['CONTENTPAGE'] = $pageInfo->content;
                    $data['CONTENT'] = "page";
                    $data['STEP'] = 'view';
                    $data['TITLE'] = "-- " .$pageInfo->title;
                }
            }
            $this->core->load_template($data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */