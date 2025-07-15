<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    private $page_data;
    private $user;

	public function __construct() {
		parent::__construct();
 
		$this->page_data = $this->WebsiteModel->pageData();        
        $this->user = $this->WebsiteModel->user_session();
        $this->admin = $this->WebsiteModel->admin_settings();
        $this->upload_cfg = $this->WebsiteModel->upload_settings();

        if(!$this->upload_cfg['enable_api']) {
            redirect(base_url('404'));
        }
	}

    public function upload() {
        header('Content-Type: application/json');
        if(is_array($_FILES) && count($_FILES) > 0) {
            if(is_uploaded('upload') && is_image('upload')) {
                // File Exists;
                $this->load->helper('string');
                $this->load->library('idgen');
                $this->load->model('Uploads/UserUploadModel');
                $slug_id = $this->idgen->generate() . $this->UserUploadModel->final_id();
                $config = array(
                    'upload_path'   => FCPATH.'i/',
                    'allowed_types' => '*',
                    'overwrite'     => false,
                    'file_name'     => $slug_id,
                    'max_size'      => $this->upload_cfg['max_size_kb'],
                    'max_height'    => $this->upload_cfg['max_height'],
                    'max_width'     => $this->upload_cfg['max_width'],
                );
                $this->load->library('upload', $config);
                $success = $this->upload->do_upload('uploads');
                if($success) {
                    $this->load->model('Uploads/UserUploadModel');
                    $data = $this->upload->data();
    
                    $name = $data['file_name'];
                    if($this->UserUploadModel->create_upload($slug_id, $name, $this->user))
                        die(json_encode(array(
                            'type' => 'success',
                            'msg'  => 'Uploaded successfully.',
                            'data' => array(
                                'id'  => $slug_id,
                                'url' => base_url('i/' . $slug_id)
                            )
                        )));
                    else
                        die(json_encode(array(
                            'type' => 'error',
                            'msg'  => 'Server Error.'
                        )));
                } else {
                    die(json_encode(array(
                        'type'   => 'error',
                        'msg'    => 'Upload Error',
                        'errors' => strip_tags($this->upload->display_errors()),
                    )));
                }
            } else {
                die(json_encode(array(
                    'type'   => 'error',
                    'msg'    => 'No file uploaded.'
                )));
            }
        } else {
            die(json_encode(array(
                'type'   => 'error',
                'msg'    => 'Illegal Request.'
            )));
        }
    }
}
