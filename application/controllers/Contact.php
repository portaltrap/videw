<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {
	private $page_data;

	public function __construct() {
		parent::__construct();
 
		$this->page_data = $this->WebsiteModel->pageData();
        $this->page_data['user'] = $this->WebsiteModel->user_session();
		$this->page_data['email'] = $this->WebsiteModel->smtp_settings();
	}

	public function index() {
		$theme_view = $this->page_data['theme_view'];

		$this->lang->load(array('contact', 'global'), $this->page_data['idiom']);
		$this->load->helper('language');

		if($this->input->post('submit')) {
			$rules = array(
				array(
					'field' => 'email', 
					'label' => lang('email'), 
					'rules' => 'required|valid_email', 
					'errors' => array(
						'required' => lang('email_required'),
						'valid_email' => lang('email_valid')
					)
				),
				array(
					'field' => 'name', 
					'label' => lang('name'), 
					'rules' => 'required', 
					'errors' => array(
						'required' => lang('name_required'),
					)
				),
				array(
					'field' => 'message', 
					'label' => lang('message'), 
					'rules' => 'required', 
					'errors' => array(
						'required' => lang('message_required'),
					)
				)
			);

			$this->form_validation->set_rules($rules);
			$validation = $this->form_validation->run();

			if($validation) {
				$captcha = true;
				if($this->page_data['recaptcha']['status']) {
					$this->load->library('xl_recaptcha', $this->page_data['recaptcha']);
					$captcha = $this->xl_recaptcha->verify_captcha($this->input->post('g-response-response'), $this->input->ip_address());
				}

				if($captcha) {
					$options = $this->page_data['email'];
					$this->load->library('xl_mailer', $options);
	
					$mail = array(
						'to' 		=> $options['email'],
						'from'  	=> array($options['email'], 'Contact Message'),
						'reply_to' 	=> array($this->input->post('email'), $this->input->post('name')),
						'subject'  	=> 'New message from ' . esc($this->input->post('name')),
						'message'  	=> compile_template(array(
							'logo' => base_url('application/uploads/img/' . $this->page_data['general']['logo']),
							'web_url' => base_url(),
							'sender_name' => $this->input->post('name'),
							'sender_email' => $this->input->post('email'),
							'content'	=> nl2br($this->input->post('message')),
							'year'		=> date('Y'),
							'name'		=> $this->page_data['general']['title'],
						), file_get_contents(APPPATH . 'views/themes/' . $this->page_data['theme'] . '/email_templates/contact_message.html')),
					);
	

					$res = $this->xl_mailer->send_mail($mail);
					if(!$res) 
						$this->page_data['alert'] = array(
							'type'  => 'alert alert-danger',
							'msg'	=> lang('unable_to_send')
						);
					else 
						$this->page_data['alert'] = array(
							'type'	=> 'alert alert-success',
							'msg'	=> lang('contact_sent')
						);
				} else {
					$this->page_data['alert'] = array(
						'type' => 'alert alert-danger',
						'msg'  => lang('captcha_failed')
					);
				}
			}
		}

		$theme_view('contact', $this->page_data);
	}
}
