<?php

/**
 * this class for add,edit and remove from communication table
 * 
 * @author Faris Al-Otaibi
 */
class communication extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('communications');
        $this->load->model('cats');
    }
    
    public function index(){
        $this->show();
    }
    
    public function show(){
        if(!$this->core->checkPermissions('communication','show','all')){
            $userId = $this->users->getInfoUser('id');
            $this->db->where(array(
                'users_id'      => $userId,
                'isDelete'      => 0
            ));
        }
        $data['query'] = $this->communications->getCommunications('all');
        $data['CONTENT'] = "communication";
        $data['STEP'] = 'show';
        $data['NAV'] = array(
            base_url()                   => "الصفحة الرئيسية",
            base_url().'communication'   => (!$this->core->checkPermissions('communication','show','all'))? "التواصل مع المستخدمين":"التواصل مع الإدارة"
        );
        $data['TITLE'] = "-- ".(!$this->core->checkPermissions('communication','show','all'))? "التواصل مع المستخدمين":"التواصل مع الإدارة";
        $this->core->load_template($data);
        
    }
    
    public function view(){
        $segments = $this->uri->segment_array();
        $commId = (isset($segments[3]))? $segments[3] : null;
        if(!$this->users->isLogin())
            redirect (STD_CMS_PERMISSION_PAGE);
        
        if(is_null($commId))
            redirect(STD_CMS_ERROR_PAGE);
        
        $comm = $this->communications->getCommunication($commId);
        if(!$comm)
            redirect (STD_CMS_ERROR_PAGE);
        
        $userId = $this->users->getInfoUser('id');
        if(($comm->users_id == $userId)|| $this->core->checkPermissions('communication','show','all')){
            if($_POST){
                $store = array(
                        'title'         => $this->input->post('title',true),
                        'content'       => $this->input->post('content',true),
                        'isDelete'      => 0,
                        'users_id'      => $this->users->getInfoUser('id'),
                        'cat_id'        => $comm->cat_id,
                        'parent_id'     => $commId,
                        'date'          => time()
                    );
                    if($this->communications->addNewCommunication($store)){
                        if($this->core->checkPermissions('communication','show','all')){
                            $this->load->library('email');
                            $site_name = $this->core->getSettingByName("site_name");
                            $site_email = $this->core->getSettingByName("site_email");

                            $this->email->from($site_email, '('.$site_name.'):');
                            $this->email->to($this->users->getEmail($comm->users_id));

                            $this->email->subject('('.$site_name.'): تم الرد من قبل'.$this->users->getUsername($this->users->getInfoUser('id')));
                            $message = '
                                <p>تم الرد من الأدارة </p>
                                <p>اسم المستخدم  :'.$this->input->post('username',true).'</p>
                                <p>العنوان: '.$this->input->post('title',true).'</p>
                                <p>'.  anchor(base_url(), $site_name).'</p>
                            ';
                            $this->email->message($message);

                            $this->email->send();
                        }
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- التواصل مع الأدارة";
                        $url = base_url().'communication/view/'.$commId;
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة أضغط هنا");
                    }else{
                        $data['CONTENT'] = "communication";
                        $data['STEP'] = 'view';
                        $data['TITLEM'] = $comm->title;
                        $data['FROM'] = $this->users->getUsername($comm->users_id);
                        $data['CONTENT_MSG'] = $comm->content;
                        $data['DATE'] = date('Y-m-d H:i:s',$comm->date);
                        $data['REPLAY'] = $this->communications->getCommunications($commId);
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع الرد لمشكلة في البيانات';
                    }
            }else{
                $data['CONTENT'] = "communication";
                $data['STEP'] = 'view';
                $data['TITLEM'] = $comm->title;
                $data['FROM'] = $this->users->getUsername($comm->users_id);
                $data['CONTENT_MSG'] = $comm->content;
                $data['DATE'] = date('Y-m-d H:i:s',$comm->date);
                $data['REPLAY'] = $this->communications->getCommunications($commId);
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()                   => "الصفحة الرئيسية",
                base_url().'communication'   => (!$this->core->checkPermissions('communication','show','all'))? "التواصل مع المستخدمين":"التواصل مع الإدارة"
            );
            $data['TITLE'] = "-- التواصل مع الأدارة";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }


    public function add(){
        if($this->users->isLogin()){
            if($_POST){
                    $store = array(
                        'title'         => $this->input->post('title',true),
                        'content'       => $this->input->post('content',true),
                        'isDelete'      => 0,
                        'users_id'      => $this->users->getInfoUser('id'),
                        'cat_id'        => $this->input->post('cat',true),
                        'date'          => time()
                    );
                    if($this->communications->addNewCommunication($store)){
                        $this->load->library('email');
                        $site_name = $this->core->getSettingByName("site_name");
                        $site_email = $this->core->getSettingByName("site_email");

                        $this->email->from($site_email, '('.$site_name.'):');
                        $this->email->to($site_email);

                        $this->email->subject('('.$site_name.'): تم التواصل من قبل'.$this->users->getUsername($this->users->getInfoUser('id')));
                        $message = '
                            <p>تم التواصل مع الأدارة </p>
                            <p>القسم : '.$this->cats->getNameOfCat($this->input->post('cat',true)).'</p>
                            <p>اسم المستخدم  :'.$this->input->post('username',true).'</p>
                            <p>العنوان: '.$this->input->post('title',true).'</p>
                            <p>'.  anchor(base_url(), $site_name).'</p>
                        ';
                        $this->email->message($message);

                        $this->email->send();
                        $data['CONTENT'] = 'msg';
                        $data['TITLE'] = "-- التواصل مع الأدارة";
                        $url = base_url().'communication/show';
                        $data['MSG'] = 'تم حفظ البيانات بشكل صحيح <br />'.  anchor($url, "للعودة للتواصل مع الأدارة أضغط هنا");
                    }else{
                        $data['CONTENT'] = "communication";
                        $data['STEP'] = 'add';
                        $data['CATS'] = $this->cats->getCat('all');
                        $data['ERROR'] = true;
                        $data['ERR_MSG'] = 'لا تستطيع الأضافة لمشكلة في البيانات';
                    }
            }else{
                $data['CONTENT'] = "communication";
                $data['STEP'] = 'add';
                $data['CATS'] = $this->cats->getCat('all');
                $data['ERROR'] = false;
                $data['ERR_MSG'] = '';
            }
            $data['NAV'] = array(
                base_url()                   => "الصفحة الرئيسية",
                base_url().'communication'   => (!$this->core->checkPermissions('communication','show','all'))? "التواصل مع المستخدمين":"التواصل مع الإدارة"
            );
            $data['TITLE'] = "-- التواصل مع الأدارة";
            $this->core->load_template($data);
        }else
            redirect (STD_CMS_PERMISSION_PAGE);
    }
    
    public function action(){
        if($this->input->is_ajax_request()){
            $segments = $this->uri->segment_array();
            $type = isset($segments[3])? $segments[3]:NULL;
            $commId = isset($segments[4])? $segments[4]:0;
            $names = array(
                'delete'    => 'الحذف',
                'restore'   => 'الأستعادة'
            );
            if(is_null($type) || $commId == 0)
                die('خطأ - مشكلة في الرابط');
            
            $comm = $this->communications->getCommunication($commId);
            if(is_bool($comm))
                die('خطأ - عفواً هذا القائمة غير موجود');
            
            if($type == 'delete' || $type == 'restore')
                if($this->core->checkPermissions('communication','delete','all')){
                    $store = array(
                        'isDelete' => ($type == 'delete')? 1:0
                    );
                }else
                    die('ليس لديك صلاحية الحذف');
            else
                die('خطأ - خطأ في الرابط');
            if($this->communications->updateCommunication($commId,$store))
                die('1 - نجحت عملية '.$names[$type]);
            else
                die('خطأ - لم تنجح عملية '.$names[$type]);
            
        }else
            redirect(STD_CMS_ERROR_PAGE);
    }
    
}

?>
