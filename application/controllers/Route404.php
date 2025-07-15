<?php

defined('BASEPATH') || exit('Access Denied.');

class Route404 extends CI_Controller {
    public function __construct() {
        parent::__construct();
		$this->page_data = $this->WebsiteModel->pageData();
        
        $this->page_data['user'] = $this->WebsiteModel->user_session();
    }
    
    public function index() {
        $this->lang->load(array('global'), $this->WebsiteModel->language());
        $this->load->helper('language');

        $theme_view = $this->page_data['theme_view'];
        $theme_view('error_404', $this->page_data);
    }
}