<?php

defined('BASEPATH') || exit('Access Denied.');

class UserUploadModel extends CI_Model {
    private $s3;
    public function __construct() {
        parent::__construct();

        $this->s3 = $this->WebsiteModel->s3_settings();
        $this->load->driver('cache', array('adapter' => 'file'));
    }
    
    public function get_upload($slug) {
        $this->load->database();
        $cv = 'imgcache-' . strtolower($slug);

        if(!$upload = $this->cache->get($cv)) {
            $res = $this->db->limit(1)->where('slug_id', strtoupper($slug))->get('user-uploads');
            if($res->num_rows()) {
                $upload = $res->row_array();
    
                $this->cache->save($cv, $upload, 86400);
            }
        }

        if($upload['user']) {
            $res = $this->db->where('id', $upload['user'])->limit(1)->get('end-users');
            if($res->num_rows()) {
                $user = $res->row_array();
                $upload['user'] = $user;
            }
        }
        
        return $upload;
    }

    public function delete_upload($id) {
        $this->load->database();
        $upload = $this->db->select('id, filename, s3, slug_id')->get_where('user-uploads', array('id' => $id));
        if($upload->num_rows()) {
            $upload = $upload->row_array();
            if($upload['s3']) {
                $this->load->library('s3_uploader');
                $this->s3_uploader->init($this->s3['s3_access'], $this->s3['s3_secret'], $this->s3['s3_bucket'], $this->s3['s3_region']);

                $this->s3_uploader->delete($upload['filename']);
                $this->s3_uploader->delete_img($upload['imgname']);
            } else {
                $path       = FCPATH . 'v/' . $upload['filename'];
                $imgpath    = FCPATH . 'i/' . $upload['imgname'];
                if(file_exists($path))
                    unlink($path);
                    unlink($imgpath);
            }

            $cv = 'imgcache-' . strtolower($upload['slug_id']);
            $this->cache->delete($cv);
            $this->db->where('id', $id)->delete('user-uploads');

            return true;
        }

        return false;
    }

    public function create_upload($name, $file_name, $imgname, $user) {
        $this->load->database();


        if($this->s3['status']) {
            $this->load->library('s3_uploader');
            $this->s3_uploader->init($this->s3['s3_access'], $this->s3['s3_secret'], $this->s3['s3_bucket'], $this->s3['s3_region']);

            $path       = FCPATH . 'v/' . $file_name;
            $imgpath    = FCPATH . 'i/' . $imgname;
            $res1 = $this->s3_uploader->new($file_name, $path);
            $res2 = $this->s3_uploader->new_img($imgname, $imgpath);
            if($res1 & $res2) {
                if(file_exists($path))
                    unlink($path);
                    unlink($imgpath);
                $file_name = $res1;
                $imgname = $res2;
            }
        }

        $new_upload = array(
            'id'        => NULL,
            'slug_id'   => $name,
            'filename'  => $file_name,
            'imgname'   => $imgname,
            'user'      => ($user) ? $user['id'] : NULL,
            's3'        => $this->s3['status'],
        );

        if($this->db->insert('user-uploads', $new_upload)) {
			return [
				'type' => $this->s3['status'] ? 's3' : 'local',
				'imgurl' => $imgname,
				'fileurl' => $file_name
			];
		} else {
			return false;
		}
    }

    
    public function total_uploads($user = null) {
        $this->load->database();
        $query = $this->db;
        if($user) $query->where('user', $user);
        return $query->count_all_results('user-uploads');
    }

    public function final_id() {
        $this->load->database();
        $query = $this->db->select('id')->limit(1)->order_by('id', 'desc')->get('user-uploads');
		
        if($query->num_rows())
            return $query->row_array()['id'];

        return 1;
    }

    public function recent_uploads($num) {
        $this->load->database();
        $res = $this->db->order_by('id', 'desc')->limit($num)->get('user-uploads')->result_array();
        foreach($res as $i => $upload) {
            if($upload['user']) {
                $res[$i]['user'] = $this->db->select('id,username')->where('id', $upload['user'])->get('end-users')->row_array();
            }
        }

        return $res;
    }
    
    public function weekly_uploads() {
        $this->load->database();
        return $this->db->order_by('id', 'desc')->where('YEARWEEK(`upload_date`) = YEARWEEK(NOW())')->get('user-uploads')->num_rows();
    }

    public function get_uploads($page, $user = null) {
        $this->load->database();

        $amount = 25;

        $query = $this->db->offset(($page - 1) * $amount)->limit($amount);
        if($user) $query->where('user', $user);
        $res = $query->order_by('id', 'desc')->get('user-uploads')->result_array();
        foreach($res as $i => $upload) {
            if($upload['user']) {
                $res[$i]['user'] = $this->db->select('id,username')->where('id', $upload['user'])->get('end-users')->row_array();
            }
        }

        return $res;
    }
    
    public function upload_by_id($id) {
        $this->load->database();

        return $this->db->where('id', $id)->limit(1)->get('user-uploads')->row_array();
    }
}