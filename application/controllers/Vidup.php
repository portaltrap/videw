<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vidup extends CI_Controller {
	private $page_data;
    private $admin_user;

    /* This is a Controller only accessible to Administrator Accounts */
	public function __construct() {
        parent::__construct();

        // Load Admin model
        $this->load->model('Authentication/AdminModel');

        $this->page_data = $this->WebsiteModel->pageData();     // Retrieve Page Data, WebsiteModel is Auto-loaded
        $this->admin_user = $this->AdminModel->adminDetails();  // Retrive Admin Details, if logged in.
        
        $this->page_data['update'] = $this->WebsiteModel->updates_settings();
        
        // Check if Admin is logged in. Redirect to login otherwise.
        if(!$this->admin_user) {
            redirect(base_url(AUTH_CONTROLLER . '/login?redirect='.urlencode(current_url())));
        }
	}

	public function index() {
        redirect(base_url(VIDUP_CONTROLLER . '/uploads'));
    }
    
    public function user_uploads($id = null, $page = 1) {
        $this->load->model('Uploads/UserUploadModel');
        $this->load->model('Authentication/UserModel');

        if($user = $this->UserModel->user_by_id($id)) {
            $count = $this->UserUploadModel->total_uploads($user['id']);
            $pages = number_format((($count - 1) / 25) + 1);

            if($page > $pages) redirect(VIDUP_CONTROLLER . '/user_uploads/' . $user['id'] . '/1');

            $data = array(
                'page_data'    => $this->page_data,
                'page_title'   => 'Uploads by ' . $user['username'],
                'user'         => $this->admin_user,
                'user_uploads' => $this->UserUploadModel->get_uploads($page, $user['id']),
                'end_user'     => $user
            );

            $data['page'] = $page;
            $data['pages'] = $pages;

            $this->load->view('admin/vidup/user_uploads', $data);
        }
            else redirect(base_url(VIDUP_CONTROLLER . '/uploads/1'));
    }

    public function uploads($page = 1) {
        $this->load->model('Uploads/UserUploadModel');

        $count = $this->UserUploadModel->total_uploads();
        $pages = number_format((($count - 1) / 25) + 1);

        if($page > $pages) redirect(VIDUP_CONTROLLER . '/uploads/1');

        $data = array(
            'page_data'    => $this->page_data,
            'page_title'   => 'View Uploads',
            'user'         => $this->admin_user,
            'user_uploads' => $this->UserUploadModel->get_uploads($page)
        );

        $data['pagination'] = [
            'page'  => $page,
            'pages' => $pages,
            'url'   => base_url(VIDUP_CONTROLLER . '/uploads')
        ];

        $this->load->view('admin/vidup/uploads/main', $data);
    }

    public function delete_upload($id = null, $confirm = false) {
        $this->load->model('Uploads/UserUploadModel');
        if($id && $upload = $this->UserUploadModel->upload_by_id($id)) {
            $data = array(
                'page_data'  => $this->page_data,
                'page_title' => 'Delete ' . $upload['slug_id'],
                'user'       => $this->admin_user,
                'upload'     => $upload
            );

            if($confirm && !$data['user']['disabled']) {
                $this->UserUploadModel->delete_upload($upload['id']);
                $this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg' => 'Upload deleted successfully.'));
                redirect(base_url(VIDUP_CONTROLLER . '/uploads'));
            }

            $this->load->view('admin/vidup/uploads/delete', $data);
        } else
            redirect(VIDUP_CONTROLLER . '/uploads');
    }

    public function users($page = 1) {
        $this->load->model('Authentication/UserModel');
        
        $count = $this->UserModel->total_registrations();
        $pages = number_format((($count - 1) / 25) + 1);

        if($page > $pages) redirect(VIDUP_CONTROLLER . '/users/1');

        $data = array(
            'page_data'  => $this->page_data,
            'page_title' => 'Manage Users',
            'user'       => $this->admin_user,
            'end_users'  => $this->UserModel->get_users($page),
        );

        $data['pagination'] = [
            'page'  => $page,
            'pages' => $pages,
            'url'   => base_url(VIDUP_CONTROLLER . '/users')
        ];

        $this->load->view('admin/vidup/users/main', $data);
    }
    
    public function search_users($search = null) {
        if($search || $search = $this->input->post('search')) {
            $this->load->model('Authentication/UserModel');
    
            $data = array(
                'page_data'  => $this->page_data,
                'page_title' => 'Manage Users',
                'query'      => $search,
                'user'       => $this->admin_user,
                'end_users'  => $this->UserModel->get_users_by_query($search),
            );
    
            $this->load->view('admin/vidup/users/search_results', $data);
        } else
            redirect(VIDUP_CONTROLLER . '/users/1');
    }

    public function toggle_verification($id = null) {
        $this->load->model('Authentication/UserModel');
        if($id && $user = $this->UserModel->user_by_id($id) && !$data['user']['disabled']) {
            if(!$user['activated'])
                $this->UserModel->verify_user($id);
            else
                $this->UserModel->unverify_user($id);

            $this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg' => 'User was updated successfully.'));
            redirect(VIDUP_CONTROLLER . '/edit_user/' . $user['id']);
        } else
            redirect(VIDUP_CONTROLLER . '/users');
    }

    public function edit_user($id = null) {
        $this->load->model('Authentication/UserModel');
        if($id && $user = $this->UserModel->user_by_id($id)) {
            $data = array(
                'page_data'  => $this->page_data,
                'page_title' => 'Edit User',
                'user'       => $this->admin_user,
                'end_user'   => $user
            );

            if($this->input->post('submit') && !$data['user']['disabled']) {
                $username = $this->input->post('username');
                $email    = $this->input->post('email');

                $this->load->database();
                $rules = array();
                if($username) {
                    $username = strtolower($username);
                    if($username != $user['username']) {
                        array_push($rules, array(
                            'field' => 'username',
                            'label' => 'Username',
                            'rules' => 'required|is_unique[end-users.username]',
                            'errors' => array('is_unique' => 'That username is already registered.')
                        ));
                    }
                }
                if($email) {
                    $email = strtolower($email);
                    if($email != $user['email']) {
                        array_push($rules, array(
                            'field' => 'email',
                            'label' => 'E-Mail',
                            'rules' => 'required|is_unique[end-users.email]',
                            'errors' => array('is_unique' => 'That E-Mail is already registered.')
                        ));
                    }
                }

                if(count($rules) > 0) {
                    $this->form_validation->set_rules($rules);
                    $validation = $this->form_validation->run();

                    if($validation) {
                        if($username)
                            $this->UserModel->set_new_username($username, $user['id']);
                        if($email)
                            $this->UserModel->set_new_email($email, $user['id']);
                    
                        $data['end_user'] = $this->UserModel->user_by_id($user['id']);

                        $data['alert'] = array('type' => 'alert alert-success', 'msg' => 'User updated successfully.');
                    }
                }
            }

            $this->load->view('admin/vidup/users/edit', $data);
        } else
            redirect(VIDUP_CONTROLLER . '/users');
    }

    public function delete_user($id = null, $confirm = false) {
        $this->load->model('Authentication/UserModel');
        if($id && $user = $this->UserModel->user_by_id($id)) {
            $data = array(
                'page_data'  => $this->page_data,
                'page_title' => 'Delete ' . $user['username'],
                'user'       => $this->admin_user,
                'end_user'   => $user
            );

            if($confirm && !$data['user']['disabled']) {
                $this->UserModel->delete_user($user['id']);
                $this->session->set_flashdata('alert', array('type' => 'alert alert-success', 'msg' => 'User deleted successfully.'));
                redirect(base_url(VIDUP_CONTROLLER . '/users'));
            }

            $this->load->view('admin/vidup/users/delete', $data);
        } else
            redirect(VIDUP_CONTROLLER . '/users');
    }

    public function s3() {
        $this->page_data['s3'] = $this->WebsiteModel->s3_settings();
        $data = array(
            'page_data'  => $this->page_data,
            'page_title' => 'Amazon S3 Settings',
            'user'       => $this->admin_user
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $status = $this->input->post('s3-status');

            $data['alert'] = array(
                'type' => 'alert alert-success',
                'msg' => 'S3 Details Updated sucessfully.'
            );

            $this->load->model('Settings/S3Model');
            if($status) {
                $access = $this->input->post('s3-access');
                $secret = $this->input->post('s3-secret');
                $bucket = $this->input->post('s3-bucket');
                $region = $this->input->post('s3-region');

                $rules = array(
                    array(
                        'field' => 's3-access',
                        'label' => 'S3 Access Key',
                        'rules' => 'required'
                    ), 
                    array(
                        'field' => 's3-secret',
                        'label' => 'S3 Secret Key',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 's3-bucket',
                        'label' => 'S3 Bucket Name',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 's3-region',
                        'label' => 'S3 Region',
                        'rules' => 'required'
                    ),
                );

                $this->form_validation->set_rules($rules);
                $validation = $this->form_validation->run();

                if($validation) {
                    $this->S3Model->set(array(
                        'status' => true,
                        's3_access' => $access,
                        's3_secret' => $secret,
                        's3_bucket' => $bucket,
                        's3_region' => $region,
                    ));
                    
                    $data['alert'] = array(
                        'type' => 'alert alert-success',
                        'msg' => 'S3 Details Updated sucessfully.'
                    );
                } else {
                    $data['alert'] = array(
                        'type' => 'alert alert-danger',
                        'msg' => 'Please fix the following errors below.'
                    );
                }
            } else {
                $this->S3Model->set(array(
                    'status' => false
                ));
            }
            
            $data['page_data']['s3'] = $this->S3Model->get();
        }

        $this->load->view('admin/vidup/s3', $data);
    }

    public function upload_settings() {
        $this->page_data['upload']          = $this->WebsiteModel->upload_settings();
        $data = array(
            'page_data'     => $this->page_data,
            'page_title'    => 'Upload Settings',
            'user'          => $this->admin_user,
            'load_scripts'  => array('js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js', 'js/plugin/bootstrap-tagsinput/tags_init.js')
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $can_user_delete    = $this->input->post('can-user-delete');
            $max_size_kb        = $this->input->post('filesize');
            $chunk_size         = $this->input->post('chunksize');
            $mime_types         = $this->input->post('mime_types');
            $enable_api         = $this->input->post('enable-api');
            $enable_popup       = $this->input->post('enable-popup');
            $seconds_to_wait    = $this->input->post('seconds-to-wait');
        
            $this->form_validation->set_rules('filesize',          'Filesize',          'required|numeric|min_length[1]|max_length[20]');
            $this->form_validation->set_rules('chunksize',         'Chunk Size',        'required|numeric|min_length[1]|max_length[20]');
            $this->form_validation->set_rules('mime_types',        'Video Types',        'required');
            $this->form_validation->set_rules('seconds-to-wait',   'Seconds to Wait',   'required|numeric|min_length[1]|max_length[11]|greater_than_equal_to[0]');

            $validation = $this->form_validation->run();
            if($validation) {
                $to_set = array(
                    'can_user_delete' => $can_user_delete ? TRUE : FALSE,
                    'max_size_kb'     => $max_size_kb,
                    'chunk_size'      => $chunk_size,
                    'mime_types'      => $mime_types,
                    'enable_api'      => $enable_api ? true : false,
                    'enable_popup'    => $enable_popup ? true : false,
                    'seconds_to_wait' => $seconds_to_wait,
                );

                $this->load->model('Settings/UploadModel');
                $this->UploadModel->set($to_set);

                $data['page_data']['upload'] = $this->UploadModel->get();

                $data['alert'] = array(
                    'type' => 'alert alert-success',
                    'msg' => 'Upload settings were Updated sucessfully.'
                );
            }
        }

        $this->load->view('admin/vidup/upload_settings', $data);
    }
}
