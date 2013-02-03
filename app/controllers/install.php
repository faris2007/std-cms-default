<?
class install extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }
    
    function index(){
        $this->step();
    }

    function step($step=0)
    {
        if($step==1)
        {
            $data['ERROR'] = (!$this->load->database())? FALSE : TRUE;
            $data['TITLE']  = "سكربت أدارة المحتوى -- صفحة التركيب";
            $data['CONTENT'] = "install";
            $data['STEP'] = "checkdb";
        }
        else if ($step == 2)
        {
            $this->dbforge->drop_database('settings');
            $this->db->query("
            CREATE  TABLE IF NOT EXISTS `settings` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(45) NULL ,
                `value` TEXT NULL ,
                `default` TEXT NULL ,
                PRIMARY KEY (`id`) )
            ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");
            $tables[] = "settings";

            $this->dbforge->drop_database('group');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `group` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(45) NULL ,
                `isDelete` VARCHAR(1) NOT NULL DEFAULT 0 ,
                `isAdmin` VARCHAR(1) NOT NULL DEFAULT 0 ,
                PRIMARY KEY (`id`) )
                ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");
            $tables[] = "group";

            $this->dbforge->drop_database('users');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `users` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `username` VARCHAR(45) NULL ,
                `full_name` VARCHAR(45) NULL ,
                `email` VARCHAR(45) NULL ,
                `password` VARCHAR(45) NULL ,
                `new_password` VARCHAR(45) NULL DEFAULT NULL ,
                `mobile` VARCHAR(45) NULL ,
                `isBanned` VARCHAR(1) NOT NULL DEFAULT 0 ,
                `isDelete` VARCHAR(1) NOT NULL DEFAULT 0 ,
                `isActive` VARCHAR(1) NOT NULL DEFAULT 0 ,
                `group_id` INT NOT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_users_group` (`group_id` ASC) ,
                CONSTRAINT `fk_users_group`
                    FOREIGN KEY (`group_id` )
                    REFERENCES `group` (`id` )
                    ON DELETE SET NULL
                    ON UPDATE CASCADE)
                ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");
            $tables[] = "users";


            $this->dbforge->drop_database('password_log');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `password_log` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `date` VARCHAR(45) NULL ,
                `password` VARCHAR(45) NULL ,
                `users_id` INT NOT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_password_log_users1` (`users_id` ASC) ,
                CONSTRAINT `fk_password_log_users1`
                    FOREIGN KEY (`users_id` )
                    REFERENCES `users` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)
                    ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");
            $tables[] = "password_log";

            $this->dbforge->drop_database('logs');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `logs` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `date` VARCHAR(45) NULL ,
                `activity` VARCHAR(45) NULL ,
                `ip` VARCHAR(15) NULL ,
                `users_id` INT NOT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_logs_users1` (`users_id` ASC) ,
                CONSTRAINT `fk_logs_users1`
                    FOREIGN KEY (`users_id` )
                    REFERENCES `users` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)
                    ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");
            $tables[] = "logs";

            $this->dbforge->drop_database('pages');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `pages` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(45) NULL ,
                `content` TEXT NULL ,
                `isDelete` VARCHAR(1) NOT NULL DEFAULT 0 ,
                `isHidden` VARCHAR(1) NOT NULL DEFAULT 0 ,
                `refresh` INT NULL ,
                `keyword` VARCHAR(45) NULL ,
                `desc` VARCHAR(45) NULL ,
                `publish_start` VARCHAR(45) NULL ,
                `publish_end` VARCHAR(45) NULL ,
                `parent_id` INT NULL DEFAULT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_pages_pages1` (`parent_id` ASC) ,
                CONSTRAINT `fk_pages_pages1`
                    FOREIGN KEY (`parent_id` )
                    REFERENCES `pages` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)
                    ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");
            $tables[] = "pages";


            $this->dbforge->drop_database('menu');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `menu` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(45) NULL ,
                `url` TEXT NULL ,
                `isDelete` VARCHAR(1) NULL ,
                `isHidden` VARCHAR(1) NULL ,
                `sort_id` INT NULL ,
                `parent_id` INT NULL DEFAULT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_menu_menu1` (`parent_id` ASC) ,
                CONSTRAINT `fk_menu_menu1`
                    FOREIGN KEY (`parent_id` )
                    REFERENCES `menu` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)
                    ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
            $tables[] = "menu";

            $this->dbforge->drop_database('permissions');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `permissions` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `service_name` VARCHAR(45) NULL ,
                `function_name` VARCHAR(45) NULL ,
                `value` VARCHAR(45) NULL ,
                `group_id` INT NOT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_permissions_group1` (`group_id` ASC) ,
                CONSTRAINT `fk_permissions_group1`
                    FOREIGN KEY (`group_id` )
                    REFERENCES `group` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)
                    ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");
            $tables[] = "permissions";

            $this->dbforge->drop_database('error_log');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `error_log` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `date` VARCHAR(45) NULL ,
                `ip` VARCHAR(45) NULL ,
                `url` VARCHAR(45) NULL ,
                `note` VARCHAR(45) NULL ,
                `error_number` VARCHAR(45) NULL ,
                `error_code` LONGTEXT NULL ,
                PRIMARY KEY (`id`) )
                ENGINE=MYISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
            $tables[] = "error_log";
            
            $this->dbforge->drop_database('slider');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `slider` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                `slider_name` VARCHAR(45) NULL ,
                `url` TEXT NULL ,
                `picture` TEXT NULL ,
                `desc` TEXT NULL ,
                `isDelete` VARCHAR(1) NULL ,
                `isHidden` VARCHAR(1) NULL ,
                `sort_id` INT NULL ,
                PRIMARY KEY (`id`) )
                ENGINE = MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
            $tables[] = "slider";
            
            $this->dbforge->drop_database('course');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `course` (
                `id` INT UNSIGNED  NOT NULL AUTO_INCREMENT ,
                `course_name` TEXT NULL ,
                `price` INT UNSIGNED NULL ,
                `course_start` VARCHAR(45) NULL ,
                `course_length` INT NULL ,
                `course_location` TEXT NULL ,
                `course_register_end` VARCHAR(45) NULL ,
                `course_capacity` INT UNSIGNED NULL ,
                `isDelete` VARCHAR(1) NULL ,
                `isHidden` VARCHAR(1) NULL ,
                PRIMARY KEY (`id`) )
                ENGINE = MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
            $tables[] = "course";
            
            $this->dbforge->drop_database('order');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `order` (
                `id` INT UNSIGNED  NOT NULL AUTO_INCREMENT ,
                `isAccept` VARCHAR(1) NULL DEFAULT 0 ,
                `users_id` INT UNSIGNED  NOT NULL ,
                `course_id` INT UNSIGNED  NOT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_order_users1` (`users_id` ASC) ,
                INDEX `fk_order_course1` (`course_id` ASC) ,
                CONSTRAINT `fk_order_users1`
                    FOREIGN KEY (`users_id` )
                    REFERENCES `users` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                CONSTRAINT `fk_order_course1`
                    FOREIGN KEY (`course_id` )
                    REFERENCES `course` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)
                ENGINE = MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
            $tables[] = "order";
            
            
            $this->dbforge->drop_database('cat');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `cat` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                `name` VARCHAR(45) NULL ,
                `desc` TEXT NULL ,
                `isHidden` VARCHAR(1) NULL ,
                `isDelete` VARCHAR(1) NULL ,
                PRIMARY KEY (`id`) )
                ENGINE = MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
            $tables[] = "cat";
            
            $this->dbforge->drop_database('communication');
            $this->db->query("CREATE  TABLE IF NOT EXISTS `communication` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                `title` VARCHAR(100) NULL ,
                `content` TEXT NULL ,
                `date` VARCHAR(45) NULL ,
                `isDelete` VARCHAR(1) NULL ,
                `cat_id` INT UNSIGNED NOT NULL ,
                `parent_id` INT UNSIGNED NULL ,
                `users_id` INT NOT NULL ,
                PRIMARY KEY (`id`) ,
                INDEX `fk_communication_cat1` (`cat_id` ASC) ,
                INDEX `fk_communication_communication1` (`parent_id` ASC) ,
                INDEX `fk_communication_users1` (`users_id` ASC) ,
                CONSTRAINT `fk_communication_cat1`
                    FOREIGN KEY (`cat_id` )
                    REFERENCES `cat` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                CONSTRAINT `fk_communication_communication1`
                    FOREIGN KEY (`parent_id` )
                    REFERENCES `communication` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                CONSTRAINT `fk_communication_users1`
                    FOREIGN KEY (`users_id` )
                    REFERENCES `users` (`id` )
                    ON DELETE CASCADE
                    ON UPDATE CASCADE)
                ENGINE = MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
            $tables[] = "communication";
            
            $data['tables'] = $tables;
            $data['TITLE']  = "سكربت أدارة المحتوى -- صفحة التركيب";
            $data['CONTENT'] = "install";
            $data['STEP'] = "createtable";
        }
        else if($step==3)
        {
            $data['TITLE']  = "سكربت أدارة المحتوى -- صفحة التركيب";
            $data['CONTENT'] = "install";
            $data['STEP'] = "addinfo";

        }else if($step==4)
        {
            $this->load->model("settings");
            $this->load->model("users");
            $this->load->model("permissions");
            $this->load->model("groups");
            $store = array(
                0 => array(
                        'name'      => "site_name",
                        'value'     => $this->input->post('nameurl',true),
                        'default'   => $this->input->post('nameurl',true)
                    ),
                1 => array(
                        'name'      => "site_url",
                        'value'     => $this->input->post('url',true),
                        'default'   => base_url()
                    ),
                2 => array(
                        'name'      => "site_email",
                        'value'     => $this->input->post('email',true),
                        'default'   => ''
                    ),
                3 => array(
                        'name'      => "style",
                        'value'     => 'default',
                        'default'   => 'default'
                    ),
                4 => array(
                    'name'      => "site_enable",
                    'value'     => '1',
                    'default'   => '1'
                    ),
                5 => array(
                    'name'      => "disable_msg",
                    'value'     => 'الموقع مغلق للصيانة
                        سوف يتم افتتاحه قريباً',
                    'default'   => 'الموقع مغلق للصيانة
                        سوف يتم افتتاحه قريباً'
                    ),
                6 => array(
                    'name'      => "disable_except_group",
                    'value'     => '1',
                    'default'   => '1'
                    ),
                7 => array(
                    'name'      => "email_server",
                    'value'     => 'php mail',
                    'default'   => 'php mail'
                    ),
                8 => array(
                    'name'      => "email_username",
                    'value'     => '',
                    'default'   => ''
                    ),
                9 => array(
                    'name'      => "email_password",
                    'value'     => '',
                    'default'   => ''
                    ),
                10 => array(
                    'name'      => "email_port",
                    'value'     => '',
                    'default'   => ''
                    ),
                11 => array(
                    'name'      => "email_protocol",
                    'value'     => '',
                    'default'   => ''
                    ),
                12 => array(
                    'name'      => "cms_version",
                    'value'     => STD_CMS_VER,
                    'default'   => STD_CMS_VER
                    ),
                13 => array(
                    'name'      => "cms_register_enable",
                    'value'     => 0,
                    'default'   => 0
                    ),
                14 => array(
                    'name'      => "cms_register_group",
                    'value'     => 2,
                    'default'   => 2
                    ),
                15 => array(
                    'name'      => "cms_register_active",
                    'value'     => 0,
                    'default'   => 0
                    ),
                16 => array(
                    'name'      => "cms_home_page",
                    'value'     => 'home',
                    'default'   => 'home'
                    )
                );
            foreach ($store as $value)
                $this->settings->addNewSetting($value);
            
            $this->groups->addNewGroup(array(
                'name'      => 'الإدارة',
                'isDelete'  => 0,
                'isAdmin'   => 1
            ));
            $groupId = 1;
            
            $this->groups->addNewGroup(array(
                'name'      => 'المستخدمين',
                'isDelete'  => 0,
                'isAdmin'   => 0
            ));
            
            $permission = $this->core->getServicesName();
            foreach ($permission as $key => $val)
                $this->permissions->addNewPermission(array(
                    'group_id'      => $groupId,
                    'service_name'  => $key,
                    'function_name' => 'all',
                    'value'         => 'all'
                ));
            
            $admin_user = array(
                    'username'  => $this->input->post('useradmin',true),
                    'password'  => $this->input->post('pass1',true),
                    'email'     => $this->input->post('email',true),
                    'group_id'  => $groupId,
                    'isActive'  => 1
            );
            $this->users->addNewUser($admin_user);
            $data['TITLE']  = "سكربت أدارة المحتوى -- صفحة التركيب";
            $data['CONTENT'] = "install";
            $data['STEP'] = "insertinfo";
            $data['isInstall'] = 'install';
            
            
        }else if($step == 0 )
        {
            $data['TITLE']  = "سكربت أدارة المحتوى -- صفحة التركيب";
            $data['CONTENT'] = "install";
            $data['STEP'] = "init";
            
        }
        $data['MENU'] = '';
        $data['isInstall'] = 'install';
        $this->core->load_template($data);
    }
}
?>
