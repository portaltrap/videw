<?php

defined('BASEPATH') || exit('Access Denied.');

require_once APPPATH . 'third_party/libraries/s3/index.php';

class S3_uploader {
    private $s3;
    private $access;
    private $secret;
    private $region;
    private $bucket;
    
    public function init($s3_access, $s3_secret, $s3_bucket, $s3_region) {
        $this->access = $s3_access;
        $this->secret = $s3_secret;
        $this->bucket = $s3_bucket;
        $this->region = $s3_region;

        $this->s3 = new S3($this->access, $this->secret);
    }


    public function new($filename, $abs_path) {
        if($this->s3->putObject($this->bucket, 'direct/' . $filename, file_get_contents($abs_path), array('x-amz-acl' => 'public-read')))
            return 'https://s3.'.$this->region.'.amazonaws.com/'.$this->bucket.'/direct/'.$filename;
        
        return false;
    }
	
	public function new_img($imgname, $abs_path) {
        if($this->s3->putObject($this->bucket, 'i/' . $imgname, file_get_contents($abs_path), array('x-amz-acl' => 'public-read')))
            return 'https://s3.'.$this->region.'.amazonaws.com/'.$this->bucket.'/i/'.$imgname;
        
        return false;
    }

    private function name_from_url($url) {
        return explode('direct/', $url)[1];
    }

    public function delete($key) {
        return $this->s3->deleteObject($this->bucket, 'direct/' . $this->name_from_url($key));
    }
	public function delete_img($key) {
        return $this->s3->deleteObject($this->bucket, 'i/' . $this->name_from_url($key));
    }
}