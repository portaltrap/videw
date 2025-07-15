<?php

defined('BASEPATH') || exit('Direct script access is prohibited.');

class PageModel extends CI_Model {
    private $cache_var;
    
    public function __construct() {
        parent::__construct();
        $this->cache_var = 'pages';
		$this->load->driver('cache', array('adapter' => 'file'));
    }
    
    public function get() {
        if(!$res = $this->cache->get($this->cache_var)) {
            $this->load->database();
        
            $res = $this->db
            ->select('page_order,position,status,permalink,title')
            ->order_by('page_order', 'asc')
            ->get('pages')
            ->result_array();

            $this->cache->save($this->cache_var, $res, 86400);
        }
    
        return $res;
    }

    public function set_order($array_permalinks) {
        $this->load->database();

        foreach($array_permalinks as $order => $page) {
            if(!$this->db
            ->where('permalink', $page)
            ->set('page_order', $order)
            ->update('pages'))
                return false;
        }

        $this->cache->delete($this->cache_var);
        return true;
    }

    public function get_new_page_order() {
        $pages = $this->get();
        $latest = array_pop($pages);

        return ($latest['page_order'] + 1);
    }

    public function get_page($permalink) {
        $permalink = strtolower($permalink);
        $cv = $this->cache_var . '-' . $permalink;
        if(!$res = $this->cache->get($cv)) {
            $this->load->database();
        
            $res = $this->db
            ->limit(1)
            ->where('permalink', strtolower($permalink))
            ->get('pages')
            ->row_array();

            $this->cache->save($cv, $res, 86400);
        }
    
        return $res;
    }

    public function create_page($insert) {
        $this->cache->delete($this->cache_var);
        if($this->db->insert('pages', $insert))
            return true;
        return false;
    }
    

    public function delete_page($permalink) {
        $permalink = strtolower($permalink);
        $cv = $this->cache_var . '-' . $permalink;

        $this->load->database();

        $this->db
        ->where('permalink', $permalink)
        ->delete('pages');

        $this->cache->delete($cv);
        $this->cache->delete($this->cache_var);

        $pages = $this->get();
        foreach($pages as $order => $page) {
            $this->db
            ->where('permalink', $page['permalink'])
            ->set('page_order', $order)
            ->update('pages');
        }
    }

    public function set_page($permalink, $fields) {
        $permalink = strtolower($permalink);
        $cv = $this->cache_var . '-' . $permalink;
        
        $this->load->database();

        $this->db
        ->where('permalink', strtolower($permalink))
        ->set($fields)
        ->update('pages');

        $this->cache->delete($cv);
        $this->cache->delete($this->cache_var);
    }
}