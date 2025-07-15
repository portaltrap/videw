<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
    private $page_data;
    private $user;

	public function __construct() {
		parent::__construct();
 
		$this->page_data    = $this->WebsiteModel->pageData();        
        $this->user         = $this->WebsiteModel->user_session();
        $this->admin        = $this->WebsiteModel->admin_settings();
        $this->upload_cfg   = $this->WebsiteModel->upload_settings();
	}

    public function video($slug = null) {
        $this->load->model('Uploads/UserUploadModel');
        if($slug && $upload = $this->UserUploadModel->get_upload($slug)) {
            $data = $this->page_data;
            $data['user'] = $this->user;
            $data['admin'] = $this->admin;
            $data['upload'] = $upload;
            $data['can_user_delete'] = $this->upload_cfg['can_user_delete'];
            $data['load_css'] = array(base_url('public/css/magnific.popup.css'));
            $data['upload_cfg'] = $this->upload_cfg;
            $array = explode (",", rtrim($data['upload_cfg']['mime_types'], ','));
            array_walk($array, function(&$value, $key ) {  $value = 'video/'.$value; } ); 
            $data['upload_cfg']['mime_typ'] = implode(',', $array);

            $data['load_scripts'] = array(
                base_url('public/js/magnific.popup.min.js'),
                base_url('public/js/includes/view_video.js')
            );

            if($this->upload_cfg['enable_popup']) {
                array_push($data['load_scripts'], base_url('public/js/includes/popup.js'));
            }

            $this->lang->load(array('global', 'single'), $this->page_data['idiom']);
            $this->load->helper('language');
            
            $theme_view = $data['theme_view'];
            $theme_view('single', $data);
        } else
            show404();
    }

	public function chunks() {

            $this->form_validation->set_rules('dzuuid', 'Username/Email', 'required');

            $runValidation = $this->form_validation->run();

            if($runValidation){
                $fileId             = $this->input->post('dzuuid');
                $chunkIndex         = $this->input->post('dzchunkindex') + 1;
                $chunkTotal         = $this->input->post('dztotalchunkcount');
                // File Exists;
                $this->load->helper('string');
                $ds                 = DIRECTORY_SEPARATOR;
                $config = array(
                    'upload_path'   => FCPATH."chunks{$ds}",
                    'allowed_types' => '*',
                    'overwrite'     => false,
                    'file_name'     => "{$fileId}-{$chunkIndex}",
                    'max_size'      => $this->upload_cfg['max_size_kb'],
                    'chunk_size'    => $this->upload_cfg['chunk_size']
                );
                if(is_uploaded('uploads') && ($chunkIndex > 0 || is_video('uploads'))){
                    $this->load->library('upload', $config);
                    $success = $this->upload->do_upload('uploads');
                    if($success) {
                        die(json_encode(array(
                            'type' => 'success',
                            'msg'  => 'Uploaded successfully.',
                            'data' => array(
                                'id' => "{$fileId}-{$chunkIndex}"
                            )
                        )));
                    }
                    else {
                        die(json_encode(array(
                            'type'   => 'error',
                            'msg'    => 'Illegal Request.'
                        )));
                    }
                }
                else {
                    die(json_encode(array(
                        'type'   => 'error',
                        'msg'    => 'Illegal Request.'
                    )));
                }
            }

    }
	function unique_code($limit){
	  return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}
	public function new($fileId = '', $chunkTotal = '', $fileType = '') {
		
        header('Content-Type', 'application/json');
        // file path variables
        $ds         = DIRECTORY_SEPARATOR;
        $targetPath = FCPATH . "chunks{$ds}";
		
        $success    = false;
        $imgCode    = $this->input->post('imgCode');
		
        // File Exists;
        $this->load->helper('string');
        $this->load->model('Uploads/UserUploadModel');
        $slug_id = $this->unique_code(8) . $this->UserUploadModel->final_id();
				
        $base64_string = str_replace('data:image/jpeg;base64,', '', $imgCode);
        $base64_string = str_replace(' ', '+', $base64_string);
        $decoded = base64_decode($base64_string);
		
        file_put_contents(FCPATH . "i{$ds}{$slug_id}.jpeg", $decoded);
        
        // loop through temp files and grab the content
        for ($i = 1; $i <= $chunkTotal; $i++) {
            // target temp file
            $temp_file_path = realpath("{$targetPath}{$fileId}-{$i}.{$fileType}") or die(json_encode(array( 'type'   => 'error', 'msg'    => 'Your chunk was lost mid-upload.')));
            // copy chunk
            $chunk = file_get_contents($temp_file_path);
            if ( empty($chunk) ) die(json_encode(array( 'type'   => 'error', 'msg'    => 'Chunks are uploading as empty strings.')));
            // add chunk to main file
            if(file_put_contents(FCPATH . "v{$ds}{$slug_id}.{$fileType}", $chunk, FILE_APPEND | LOCK_EX)) $success = true;
            // delete chunk
            unlink($temp_file_path);
            if ( file_exists($temp_file_path) ) die(json_encode(array( 'type'   => 'error', 'msg'    => 'Your temp files could not be deleted.')));
        }
        
        if($success) {
            $this->load->model('Uploads/UserUploadModel');

            $name = "{$slug_id}.{$fileType}";
            $imgname = "{$slug_id}.jpeg";

			$res = $this->UserUploadModel->create_upload($slug_id, $name, $imgname, $this->user);
            if($res && !empty($res))
                die(json_encode(array(
                    'type' => 'success',
                    'msg'  => 'Uploaded successfully.',
                    'data' => array('id' => $slug_id),
					's3' => $res['type'] == 's3' ? true : false,
					'thumbnail' => $res['imgurl']
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
    }
}
