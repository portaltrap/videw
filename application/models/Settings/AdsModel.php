<?php

defined('BASEPATH') || exit('Direct script access is prohibited.');

class AdsModel extends CI_Model {
    private $cache_var;

    public function __construct() {
        parent::__construct();
        $this->cache_var = 'ad-settings';
		$this->load->driver('cache', array('adapter' => 'file'));
	}

    public function get() {
        if(!$res = $this->cache->get($this->cache_var)) {
            $this->load->database();
        
            $res = $this->db
            ->limit(1)
            ->get('ad-settings')
            ->row_array();

            $this->cache->save($this->cache_var, $res, 86400);
        }
    
        return array(
            'top' => array(
                'status' => $res['top_ad_status'],
                'code' => $res['top_ad']
            ),
            'bottom' => array(
                'status' => $res['bottom_ad_status'],
                'code' => $res['bottom_ad']
            ),
            'pop' => array(
                'status' => $res['pop_ad_status'],
                'code' => $res['pop_ad']
            )
        );
    }
    
    public function set($fields) {
        $this->load->database();

        $this->db
        ->set($fields)
        ->where('id', 1)
        ->update('ad-settings');

        $this->cache->delete($this->cache_var);
    }
}