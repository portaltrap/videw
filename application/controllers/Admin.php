<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	private $page_data;
    private $admin_user;

	public function __construct() {
        parent::__construct();
        $this->load->model('Authentication/AdminModel');
		$this->page_data = $this->WebsiteModel->pageData();
        $this->admin_user = $this->AdminModel->adminDetails();
        
        if(!$this->admin_user)
            redirect(base_url(AUTH_CONTROLLER));
    }
    
    public function index() {
        redirect(base_url(GENERAL_CONTROLLER));
    }
}
