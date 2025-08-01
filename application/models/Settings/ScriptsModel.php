<?php

defined('BASEPATH') || exit('Direct script access is prohibited.');

class ScriptsModel extends CI_Model {
    private $cache_var;
    
    public function __construct() {
        parent::__construct();
        $this->cache_var = 'scripts-settings';
		$this->load->driver('cache', array('adapter' => 'file'));
    }
    
    public function get() {
        if(!$res = $this->cache->get($this->cache_var)) {
            $this->load->database();
        
            $res = $this->db
            ->limit(1)
            ->get('scripts-settings')
            ->row_array();

            $this->cache->save($this->cache_var, $res, 86400);
        }
    
        return $res;
    }

    public function set($fields) {
        $this->load->database();

        $this->db
        ->set($fields)
        ->where('id', 1)
        ->update('scripts-settings');

        $this->cache->delete($this->cache_var);
    }
}