<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	private $page_data;

	public function __construct() {
		parent::__construct();
        $this->page_data = $this->WebsiteModel->pageData();
        $this->page_data['social_keys'] = $this->WebsiteModel->social_keys();
        $this->page_data['user'] = $this->WebsiteModel->user_session();
        
        if(!$this->page_data['general']['enable_registration'])
            redirect(base_url('404'));
    }

    public function delete($slug_id = null, $delete = false) {
        if(!$this->page_data['user'])
            redirect(base_url(USER_CONTROLLER . '/login'));

        $this->load->model('Uploads/UserUploadModel');
        $this->page_data['upload_cfg'] = $this->WebsiteModel->upload_settings();

        if($this->page_data['upload_cfg']['can_user_delete'] && $slug_id && $upload = $this->UserUploadModel->get_upload($slug_id)) {
            if($this->page_data['user']['id'] == $upload['user']['id']) {
                $this->page_data['upload'] = $upload;

                $this->lang->load(array('user/delete', 'global'), $this->page_data['idiom']);
                $this->load->helper('language');
    
                if(!$delete) {
                    $theme_view = $this->page_data['theme_view'];
                    $theme_view('user/delete', $this->page_data);
                } else {
                    $this->UserUploadModel->delete_upload($upload['id']);
                    $this->session->set_flashdata('alert', array(
                        'type' => 'alert alert-success',
                        'msg'  => lang('image_deleted')
                    ));
                    redirect(base_url(USER_CONTROLLER . '/profile'));
                }
            } else
                redirect(base_url(USER_CONTROLLER . '/profile'));
        } else
            redirect(base_url(USER_CONTROLLER . '/profile'));
    }

    public function profile($page = 1) {
        if(!$this->page_data['user'])
            redirect(base_url(USER_CONTROLLER . '/login'));


        $this->lang->load(array('global'), $this->page_data['idiom']);
        $this->load->helper('language');

        $this->load->model('Uploads/UserUploadModel');

        $count = $this->UserUploadModel->total_uploads($this->page_data['user']['id']);
        $pages = number_format((($count - 1) / 25) + 1);

        $this->page_data['upload_cfg'] = $this->WebsiteModel->upload_settings();
        $this->page_data['user_uploads'] = $this->UserUploadModel->get_uploads($page, $this->page_data['user']['id']); 
        $this->page_data['title'] = 'User Profile';
        $this->page_data['load_scripts'] = array(
            base_url('public/js/parallax.min.js'),
            base_url('public/js/includes/profile.js')
        );
        $this->page_data['page']  = $page;
        $this->page_data['count'] = $count;
        $this->page_data['pagination_pages'] = $pages;

        $theme_view = $this->page_data['theme_view'];
        $theme_view('user/profile', $this->page_data);
    }
    
    public function settings() {
        if(!$this->page_data['user'])
            redirect(base_url(USER_CONTROLLER . '/login'));

        $theme_view = $this->page_data['theme_view'];

        $this->lang->load(array('user/settings', 'global'), $this->page_data['idiom']);
        $this->load->helper('language');

        if($this->input->post('submit-pass-new')) {
            $this->page_data['tab'] = 'password';
            
            $password = $this->input->post('password');

            $this->form_validation->set_rules('password', lang('new_password'), 'required|min_length[8]', array('required' => lang('new_password_required'), 'min_length' => lang('new_password_length')));
            $validation = $this->form_validation->run();

            if($validation) {
                $this->load->model('Authentication/UserModel');
                if(!$this->page_data['user']['password_set']) {
                    $this->UserModel->set_new_password($password);
                    $this->page_data['user'] = $this->UserModel->refresh_info();
                    $this->page_data['alert'] = array(
                        'type' => 'alert alert-success',
                        'msg' => lang('password_changed')
                    );
                } else
                    $this->page_data['alert'] = array(
                        'type' => 'alert alert-success',
                        'msg' => lang('not_allowed')
                    );
            }
        } else if($this->input->post('submit-pass')) {
            $this->page_data['tab'] = 'password';

            $rules = array(
                array(
                    'field' => 'password',
                    'label' => lang('old_password'),
                    'rules' => 'required',
                    'errors' => array(
                        'required' => lang('old_password_required')
                    )
                ),
                array(
                    'field' => 'newpassword',
                    'label' => lang('new_password'),
                    'rules' => 'required|min_length[8]',
                    'errors' => array(
                        'required' => lang('new_password_required'),
                        'min_length' => lang('new_password_length')
                    )
                )
            );

            $this->form_validation->set_rules($rules);
            $validation = $this->form_validation->run();

            if($validation) {
                $this->load->model('Authentication/UserModel');

                if($this->UserModel->verify_password($this->input->post('password'))) {
                    $this->UserModel->set_new_password($this->input->post('newpassword'));
                    $this->page_data['alert'] = array(
                        'type' => 'alert alert-success',
                        'msg' => lang('password_changed')
                    );
                } else {
                    $this->page_data['alert'] = array(
                        'type' => 'alert alert-danger',
                        'msg' => lang('old_password_invalid')
                    );
                }
            }
        } else if($this->input->post('submit-acc')) {
            $username = $this->input->post('username');
            $privacy  = $this->input->post('privacy-check');

            $this->load->model('Authentication/UserModel');

            if($privacy) {
                $this->UserModel->set_privacy_true();
                $this->page_data['user'] = $this->UserModel->refresh_info();
            } else {
                $this->UserModel->set_privacy_false();
                $this->page_data['user'] = $this->UserModel->refresh_info();
            }

            $this->page_data['alert'] = array(
                'type' => 'alert alert-success',
                'msg' => lang('updated_successfully')
            );

            if(is_uploaded('avatar')) {
                if(is_image('avatar')) {
                    $this->load->library('upload', array(
                        'upload_path' => APPPATH.'uploads/user/',
                        'allowed_types' => '*',
                        'overwrite' => false,
                        'file_name'  => md5($this->page_data['user']['id']),
                        'max_width' => 400,
                        'max_height' => 400,
                    ));

                    $success = $this->upload->do_upload('avatar');
                    if($success) {
                        $data = $this->upload->data();
                        $this->UserModel->set_new_avatar($data['file_name']);
                        $this->page_data['user'] = $this->UserModel->refresh_info();
                    } else 
                        $this->page_data['alert'] = array(
                            'type' => 'alert alert-danger',
                            'msg' => lang('over_size')
                        );
                } else
                    $this->page_data['alert'] = array(
                        'type' => 'alert alert-danger',
                        'msg' => lang('is_not_image')
                    );
            }

            if($username) {
                $username = strtolower($username);
                if($username != $this->page_data['user']['username']) {
                    $this->load->database();
                    $this->form_validation->set_rules('username', lang('username'), 'required|is_unique[end-users.username]', array('required' => lang('username_required'), 'is_unique' => lang('username_unique')));
                    $validation = $this->form_validation->run();

                    if($validation) {
                        $this->UserModel->set_new_username($username);
                        $this->page_data['user'] = $this->UserModel->refresh_info();
                    } else {
                        $this->page_data['alert'] = array(
                            'type' => 'alert alert-danger',
                            'msg' => lang('update_error')
                        );
                    }
                }
            }
        }

        $this->page_data['title'] = lang('user_settings');

        $theme_view('user/settings', $this->page_data);
    }

	public function login() {
        if($this->page_data['user'])
            redirect(base_url(USER_CONTROLLER . '/profile'));

        $theme_view = $this->page_data['theme_view'];
        
        $this->lang->load(array('user/login', 'global'), $this->page_data['idiom']);
        $this->load->helper('language');

        if($this->input->post('submit')) {
            $identifier = $this->input->post('identifier');
            $password   = $this->input->post('password');

            $rules = array(
                array(
					'field' => 'identifier', 
					'label' => lang('identifier'), 
					'rules' => 'required', 
					'errors' => array(
						'required' => lang('identifier_required'),
					)
				),
				array(
					'field' => 'password', 
					'label' => lang('password'), 
					'rules' => 'required', 
					'errors' => array(
						'required' => lang('password_required'),
					)
				),
            );

            $this->form_validation->set_rules($rules);
            $validation = $this->form_validation->run();

            if($validation) {
                $this->load->model('Authentication/UserModel');
                $res = $this->UserModel->do_login($identifier, $password);
                if(!$res)
					$this->page_data['alert'] = array(
						'type'  => 'alert alert-danger',
						'msg'	=> lang('invalid_credentials')
					);
                else if($res == 'unverified')
                    $this->page_data['alert'] = array(
                        'type'  => 'alert alert-danger',
                        'msg'	=> lang('not_verified')
                    );
                else 
                    redirect(base_url(USER_CONTROLLER . '/profile'));
            }
        }

        $this->page_data['title'] = lang('login');

        $theme_view('user/login', $this->page_data);
    }

	public function register() {
        if($this->page_data['user'])
            redirect(base_url(USER_CONTROLLER . '/profile'));

        $theme_view = $this->page_data['theme_view'];
        
        $this->lang->load(array('user/register', 'global'), $this->page_data['idiom']);
        $this->load->helper('language');

        if($this->input->post('submit')) {
            $username   = $this->input->post('username');
            $email      = $this->input->post('email');
            $password   = $this->input->post('password');

            $rules = array(
                array(
					'field' => 'username', 
					'label' => lang('username'), 
					'rules' => 'required|min_length[3]|max_length[32]|alpha_dash|is_unique[end-users.username]', 
					'errors' => array(
                        'required' => lang('username_required'),
                        'min_length' => lang('username_length_min'),
                        'max_length' => lang('username_length_max'),
                        'alpha_dash' => lang('username_alpha_numeric_dash'),
                        'is_unique'  => lang('username_unique')
					)
                ),
                array(
					'field' => 'email', 
					'label' => lang('email'), 
					'rules' => 'required|valid_email|is_unique[end-users.email]', 
					'errors' => array(
						'required' => lang('email_required'),
						'valid_email' => lang('email_invalid'),
                        'is_unique'  => lang('email_unique')
					)
				),
				array(
					'field' => 'password', 
					'label' => lang('password'), 
					'rules' => 'required|min_length[8]', 
					'errors' => array(
                        'required' => lang('password_required'),
                        'min_length' => lang('password_length')
					)
				),
            );

            $this->load->database(); // For Form Validation 'is_unique' Rule.
            $this->form_validation->set_rules($rules);
            $validation = $this->form_validation->run();

            if($validation) {
                $this->load->model('Authentication/UserModel');
                
                $res = $this->UserModel->do_register($username, $email, $password);
                if(!$res)
                    $this->page_data['alert'] = array(
                        'type' => 'alert alert-danger',
                        'msg'  => lang('server_error'),
                    );
                else
                    $this->page_data['alert'] = array(
                        'type' => 'alert alert-success',
                        'msg'  => lang('register_success'),
                    );
            }
        }

        $this->page_data['title'] = lang('register');

        $theme_view('user/register', $this->page_data);
    }

    public function activate($code = null) {
        if($this->page_data['user'])
            redirect(base_url(USER_CONTROLLER . '/profile'));

        $this->load->model('Authentication/UserModel');

        $this->lang->load('user/activate', $this->page_data['idiom']);
        $this->load->helper('language');

        if($code && $this->UserModel->do_activate($code))
            $this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg' => lang('activated')));
        else 
            $this->session->set_flashdata('alert', array('type' => 'alert alert-danger', 'msg' => lang('error')));

        redirect(base_url(USER_CONTROLLER . '/login'));
    }

    public function reset($code = null) {
        if($this->page_data['user'])
            redirect(base_url(USER_CONTROLLER . '/profile'));

        $theme_view = $this->page_data['theme_view'];
        
        $this->lang->load(array('user/reset', 'global'), $this->page_data['idiom']);
        $this->load->helper('language');

        $this->load->model('Authentication/UserModel');
                    
        $this->page_data['title'] = lang('reset_password');
        if($code && $id = $this->UserModel->get_id_by_reset_code($code)) {
            if($this->input->post('submit')) {
                $new        = $this->input->post('password');
                $new_conf   = $this->input->post('password_conf');

                $rules = array(
                    array(
                        'field' => 'password',
                        'label' => lang('new_password'),
                        'rules' => 'required|min_length[8]',
                        'errors' => array(
                            'required' => lang('new_password_required'),
                            'min_length' => lang('new_password_length')
                        )
                    ),
                    array(
                        'field' => 'passwordconf',
                        'label' => lang('new_password_confirm'),
                        'rules' => 'required|matches[password]',
                        'errors' => array(
                            'required' => lang('new_password_confirm_required'),
                            'matches' => lang('new_password_confirm_match')
                        )
                    )
                );

                $this->form_validation->set_rules($rules);
                $validation = $this->form_validation->run();

                if($validation) {
                    $res = $this->UserModel->reset_via_code($id, $new);
                    if($res) {
                        $this->session->set_flashdata('alert', array(
                            'type' => 'alert alert-success',
                            'msg'  => lang('reset_success'),
                        ));

                        redirect(base_url(USER_CONTROLLER . '/login'));
                    } else {
                        $this->page_data['alert'] = array(
                            'type' => 'alert alert-danger',
                            'msg'  => lang('server_error'),
                        );
                    }
                }
            }

            $theme_view('user/reset/step2', $this->page_data);
        } else {
            if($this->input->post('submit')) {
                $email = $this->input->post('email');

                $this->form_validation->set_rules('email', lang('email'), 'required|valid_email', array('required' => lang('email_required'), 'valid_email' => 'email_invalid'));
                $validation = $this->form_validation->run();

                if($validation) {
                    $captcha = true;
                    if($this->page_data['recaptcha']['status']) {
                        $this->load->library('xl_recaptcha', $this->page_data['recaptcha']);
                        $captcha = $this->xl_recaptcha->verify_captcha($this->input->post('g-recaptcha-response'), $this->input->ip_address());
                    }

                    if($captcha) {
                        if($id = $this->UserModel->get_id_by_email($email)) {
                            $res = $this->UserModel->send_reset_code($id);
                            if(!$res) {
                                $this->page_data['alert'] = array(
                                    'type'  => 'alert alert-danger',
                                    'msg'	=> lang('unable_to_send')
                                );
                            } else {
                                $this->page_data['alert'] = array(
                                    'type'	=> 'alert alert-success',
                                    'msg'	=> lang('code_sent')
                                );
                            }
                        } else {
                            $this->page_data['alert'] = array(
                                'type'  => 'alert alert-danger',
                                'msg'	=> lang('user_not_found')
                            );
                        }
                    } else {
                        $this->page_data['alert'] = array(
                            'type'  => 'alert alert-danger',
                            'msg'	=> lang('captcha_required')
                        );
                    }
                }
            }

            $theme_view('user/reset/step1', $this->page_data);
        }
    }

    public function logout() {
        $this->load->model('Authentication/UserModel');
        $this->UserModel->do_logout();

        redirect(base_url());
    }
}
