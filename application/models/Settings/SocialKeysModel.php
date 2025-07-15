<?php

defined('BASEPATH') || exit('Direct script access is prohibited.');

class SocialKeysModel extends CI_Model {
    private $cache_var;
    
    public function __construct() {
        parent::__construct();
        $this->cache_var = 'social-keys-settings';
		$this->load->driver('cache', array('adapter' => 'file'));
    }
    
    public function get() {
        if(!$res = $this->cache->get($this->cache_var)) {
            $this->load->database();
        
            $res = $this->db
            ->limit(1)
            ->get('social-keys-settings')
            ->row_array();

            $this->cache->save($this->cache_var, $res, 86400);
        }
        
        if($res['google_secret'] != '' && $res['google_public'] != '') $res['google_status'] = true;
        else $res['google_status'] = false;
                
        if($res['facebook_secret'] != '' && $res['facebook_public'] != '') $res['facebook_status'] = true;
        else $res['facebook_status'] = false;

        return $res;
    }
    
    public function set($fields) {
        $this->load->database();

        $this->db
        ->set($fields)
        ->where('id', 1)
        ->update('social-keys-settings');

        $this->cache->delete($this->cache_var);
    }
}