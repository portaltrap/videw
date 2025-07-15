<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Install extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata()) {
			redirect(installPath().'install/', 'location');
		} else {
			$userData = $this->session->userdata();
			if((empty($userData['license'])) && ($this->router->method!=="license") && ($this->router->method!=="index")) {
				redirect(installPath().'install', 'location');
			}
		}

		$this->load->driver('cache', array('adapter' => 'file'));
		$this->load->model("InstallModel");
	}
	
	public function index() {
		$data = array();
		$userData = array();
		if($this->session->userdata()) {
			$this->session->sess_destroy();
		}
		$data['title'] = "Welcome To " . PRODUCT_NAME . " Installer";
		$data['current_page'] = "welcome";
		$data['next_page'] = "license";
		$data['base_url'] = installPath();
		$data['error'] = false;
		$version_latest = json_decode(file_get_contents(APPPATH."views/install/includes/version.json"),true);
		$data['build']['version'] = isset($version_latest['version']) ? $version_latest['version'] : "0";
		$userData['build']['version'] = $data['build']['version'];
		$this->session->set_userdata($userData);
		$this->load->view("install/index",$data);
	}

	public function error() {
		$data = array();
		$data['title'] = PRODUCT_NAME . " - Oooops !";
		$data['base_url'] = installPath();
		$data['error'] = false;
		$version_latest = json_decode(getRemoteContents(API_URL."latest_version?product_unique_id=".PRODUCT_ID),true);
		$data['build']['version'] = isset($version_latest['version']) ? $version_latest['version'] : "0";
		$this->load->view("install/error",$data);
	}
	
	public function getProductInfo() {
		return json_decode(getRemoteContents("https://downloads.xlscripts.com/" . PRODUCT_ID . "/" . PRODUCT_ID . ".json"),true);
	}
	
	public function license() {
		$data = array();
		$userData = array();
		$data['title'] = PRODUCT_NAME . " - License Verification";
		$data['current_page'] = "license";
		$data['next_page'] = "requirements";
		$data['base_url'] = installPath();
		$data['error'] = false;
		if($this->input->post("submit")) {
			$license_key = $this->input->post("license_key");
			$this->form_validation->set_rules('license_key', 'License Key', 'required');
			$token = $this->input->post("token");
			$this->form_validation->set_rules('token', 'Envato Token', 'required');
			if ($this->form_validation->run() == TRUE) {
				$productInfo = $this->getProductInfo();
				//clear
				$userData = $this->session->userdata();
				$userData['license']['license_key'] = $license_key;
				$userData['license']['token'] = $token;
				$this->session->set_userdata($userData);
				redirect(installPath().'install/'.$data['next_page'], 'location');
				exit();
		}
	}
	$this->load->view("install/license",$data);
}
	
	public function requirements() {
	$data = array();
	$data['title'] = PRODUCT_NAME . " - Requirements";
	$data['current_page'] = "requirements";
	$data['next_page'] = "database";
	$data['base_url'] = installPath();
	$data['error'] = false;
	$data['web_server'] = $_SERVER["SERVER_SOFTWARE"];
	$data['php_version'] = PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION.PHP_RELEASE_VERSION;
	$data['curl_installed'] = function_exists('curl_version');
	$data['zip_loaded'] = extension_loaded ("zip");
	$data['mysql_support']	= extension_loaded('mysqlnd');
	if($data['php_version']<=5.5 || !$data['zip_loaded'] || !$data['curl_installed'] || !$data['mysql_support'] || (substr_count(strtolower($data['web_server']),"apache")>0 && substr_count(strtolower($data['web_server']),"litespeed")>0)) {
	$data['error'] = true;
	} else {
	$data['error'] = false;
	}
	$this->load->view("install/requirements",$data);
	}
	
	public function database() {
	$data = array();
	$data['title'] = PRODUCT_NAME . " - Database Details";
	$data['current_page'] = "database";
	$data['next_page'] = "settings";
	$data['base_url'] = installPath();
	$data['error'] = false;
	$db_status = false;
	if($this->input->post("submit")) {
		$data['host'] = $this->input->post("host");
		$this->form_validation->set_rules('host', 'Database Host', 'required');
		$data['database'] = $this->input->post("database");
		$this->form_validation->set_rules('database', 'Database Name', 'required');
		$data['username'] = $this->input->post("username");
		$this->form_validation->set_rules('username', 'Database Username', 'required');
		$data['password'] = $this->input->post("password");
		if ($this->form_validation->run() == TRUE) {
			$db_status = $this->InstallModel->verify_db_details($data['host'],$data['username'],$data['password'],$data['database']);
			if($db_status && $this->session->userdata()) {
				$userData = $this->session->userdata();
				$userData['database']['host'] = $data['host'];
				$userData['database']['database'] = $data['database'];
				$userData['database']['username'] = $data['username'];
				$userData['database']['password'] = $data['password'];
				$this->session->set_userdata($userData);
				$data['error'] = false;
				$this->InstallModel->paste_db_details($data['host'],$data['username'],$data['password'],$data['database']);
				redirect(installPath().'install/'.$data['next_page'], 'location');
				exit();
			} else {
				$data['error'] = true;
				$data['msg'] = "Invalid Database Details, Enter Correct Details & Try Again";
			}
		}
	}
	$this->load->view("install/database",$data);
	}
	
	function _alpha_dash_space($str_in = '') {
		if (! preg_match("/^([-a-z0-9_ ])+$/i", $str_in)) {
			$this->form_validation->set_message('_alpha_dash_space', 'The %s field may only contain alpha-numeric characters, spaces, underscores, and dashes.');
			return FALSE;
		}
		else {
			return TRUE;
		}
	}

	public function settings() {
	$data = array();
	$data['title'] = PRODUCT_NAME . " - Website Settings";
	$data['current_page'] = "settings";
	$data['next_page'] = "finish";
	$data['base_url'] = installPath();
	$data['install_path'] = installPath();
	$data['error'] = false;
	if($this->input->post("submit")) {
		$data['install_path'] = $this->input->post("install_path");
		if(filter_var($data['install_path'], FILTER_VALIDATE_URL) === FALSE) {
			$data['error'] = true;
		}
		$data['website_title'] = strip_tags($this->input->post("website_title"));
		$this->form_validation->set_rules('website_title', 'Website Title', 'required|callback__alpha_dash_space|max_length[70]');
		$data['email'] = $this->input->post("email");
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$data['username'] = $this->input->post("username");
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[3]');
		$data['password'] = $this->input->post("password");
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
		if ($this->form_validation->run() == TRUE && $this->session->userdata()) {
			$userData = $this->session->userdata();
			$userData['settings']['base_url'] = $data['install_path'];
			$userData['settings']['website_title'] = $data['website_title'];
			$userData['settings']['email'] = $data['email'];
			$userData['settings']['username'] = $data['username'];
			$userData['settings']['password'] = $data['password'];
			$this->session->set_userdata($userData);
			$this->InstallModel->paste_config_details($data['install_path'],$data['website_title'],$data['email'],$data['username'],$data['password']);
			redirect(installPath().'install/'.$data['next_page'], 'location');
			exit();
		} else {
			$data['error'] = true;
			$data['errorMsg'] = "Errors were found. Please check all fields and try again";
		}
	}
	$this->load->view("install/settings",$data);
	}
	
	public function finish() {
		if($this->session->userdata()) {
				$this->InstallModel->finishInstall($this->session->userdata());
				$this->session->sess_destroy();
		}
		$data = array();
		$data['title'] = PRODUCT_NAME . " - Installation Success";
		$data['current_page'] = "finish";
		$data['next_page'] = "finish";
		$data['base_url'] = installPath();
		$data['error'] = false;
		$this->cache->clean();
		$this->load->view("install/finish",$data);
	}
	
	public function validate_license($license_key,$token) {
		//return array('license_response'=>['item'=>['id'=>'28698607','author_username'=>'XLScripts'], 'code'=>'license-key']);
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => "https://api.envato.com/v3/market/buyer/purchase?code=" . $license_key,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 20,

			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $token
			)
		));
		$response = curl_exec($ch);
		curl_close($ch);
		$dataApi = json_decode($response,true);
		return $dataApi;
	}
}