<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	private $page_data;

	public function __construct() {
		parent::__construct();
 
		$this->page_data = $this->WebsiteModel->pageData();
        
        $this->page_data['user']   = $this->WebsiteModel->user_session();
		$this->page_data['upload'] = $this->WebsiteModel->upload_settings();
		$array = explode (",", rtrim($this->page_data['upload']['mime_types'], ','));
		array_walk($array, function(&$value, $key ) {  $value = 'video/'.$value; } ); 
        $this->page_data['upload']['mime_typ'] = implode(',', $array);
	}

	public function index() {
		$theme_view = $this->page_data['theme_view'];

		$this->lang->load(array('main', 'global'), $this->page_data['idiom']);
		$this->load->helper('language');

		$this->page_data['load_scripts'] = array(
			base_url('public/js/includes/uploads.js')
		);

		$theme_view('main', $this->page_data);
	}

	
	public function set_language($idiom = null) {
		$this->config->load('languages');

		$languages = $this->config->item('idioms');

		if($idiom && array_key_exists(strtolower($idiom), $languages))
			set_language($idiom);

		if($_SERVER['HTTP_REFERRER'] && $_SERVER['HTTP_REFERRER'] != '')
			redirect($_SERVER['HTTP_REFERRER']);

		redirect(base_url());
	}
}
