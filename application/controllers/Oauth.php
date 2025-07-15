<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Oauth extends CI_Controller {
    private $keys;

    public function __construct() {
        parent::__construct();

        if(!$this->WebsiteModel->general_settings()['enable_registration']) {
            redirect(base_url('404'));
        }

        $this->load->model('Authentication/UserModel');
        $this->keys = $this->WebsiteModel->social_keys();

        $user = $this->UserModel->user_info();
        if($user)
            redirect(base_url(USER_CONTROLLER . '/profile'));
    }

	public function google() {
        if(!$this->keys['google_status']) redirect(base_url());

        $this->load->library('google_auth', array(
            'client_id'     => $this->keys['google_public'],
            'client_secret' => $this->keys['google_secret']
        ));

        $profile = $this->google_auth->authenticate();
        $email = $profile->email;

        $this->UserModel->do_social_login($email, 'google');

        redirect(base_url());
    }

    public function facebook() {
        if(!$this->keys['facebook_status']) redirect(base_url());

        $this->load->library('facebook_auth', array(
            'public_key' => $this->keys['facebook_public'],
            'secret_key' => $this->keys['facebook_secret']
        ));

        $profile = $this->facebook_auth->authenticate();
        $email = $profile->email;

        $this->UserModel->do_social_login($email, 'facebook');

        redirect(base_url());
    }
}
