<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerName extends CI_Controller {
	private $page_data;

	public function __construct() {
		parent::__construct();
		$this->page_data = $this->WebsiteModel->pageData();
	}

	public function index() {
        $theme_view = $this->page_data['theme_view'];
        
        $this->lang->load(array('LANGUAGE_SET', 'global'), $this->page_data['idiom']);
        $this->load->helper('language');

        $theme_view('VIEW_NAME', $this->page_data);
    }
}
