<?php

defined('BASEPATH') || exit('Access Denied.');

class UserModel extends CI_Model {
    public function new_session($id, $username = null, $email = null, $privacy = null, $google = null, $facebook = null, $avatar = null) {
        $password_set = true;
        if(!$username || !$email || !$privacy || !$google || !$facebook || !$avatar) {
            $this->load->database();
            $res = $this->db->get_where('end-users', array('id' => $id));
            if($res->num_rows()) {
                $user = $res->row_array();

                $username   = $user['username'];
                $email      = $user['email'];
                $privacy    = $user['privacy'];
                $google     = $user['google'];
                $facebook   = $user['facebook'];
                $avatar     = $user['avatar'];

                if($user['password'] == 'OAUTH') $password_set = false;
            } else
                return false;
        }

        $this->session->set_userdata(array(
            'end_user_username'     => $username,
            'end_user_email'        => $email,
            'end_user_id'           => $id,
            'end_user_privacy'      => $privacy,
            'end_user_google'       => $google,
            'end_user_facebook'     => $facebook,
            'end_user_password_set' => $password_set,
            'end_user_avatar'       => $avatar
        ));

        return array(
            'username'      => $username,
            'email'         => $email,
            'id'            => $id,
            'privacy'       => $privacy,
            'google'        => $google,
            'facebook'      => $facebook,
            'password_set'  => $password_set,
            'avatar'        => $avatar
        );
    }

    // public function create_remember_cookie($id) {
    //     set_cookie(array(
    //         'name' => 'siu-usr-ssn',
    //         'value' => $id,
    //         'expire' => time() + (10 * 365 * 24 * 60 * 60),
    //         'httponly' => TRUE,
    //         'path' => '/'
    //     ));
        
    //     return true;
    // }

    public function user_info() {
        if($this->session->userdata('end_user_id')) {
            return array(
                'username'      => $this->session->userdata('end_user_username'),
                'email'         => $this->session->userdata('end_user_email'),
                'id'            => $this->session->userdata('end_user_id'),
                'privacy'       => $this->session->userdata('end_user_privacy'),
                'google'        => $this->session->userdata('end_user_google'),
                'facebook'      => $this->session->userdata('end_user_facebook'),
                'password_set'  => $this->session->userdata('end_user_password_set'),
                'avatar'        => $this->session->userdata('end_user_avatar'),
            );
        }

        return false;
    }

    public function refresh_info() {
        $id = $this->session->userdata('end_user_id');
        return $this->new_session($id);
    }

    public function set_new_password($password) {
        $this->load->database();
        $res = $this->db->where('id', $this->session->userdata('end_user_id'))->set('password', password_hash(trim($password), PASSWORD_DEFAULT))->update('end-users');
        
        return $res;
    }

    public function set_new_username($username, $id = null) {
        $this->load->database();
        $res = $this->db->where('id', !$id ? $this->session->userdata('end_user_id') : $id)->set('username', trim(strtolower($username)))->update('end-users');

        return $res;
    }
    
    public function set_new_avatar($filename, $id = null) {
        $this->load->database();
        $res = $this->db->where('id', !$id ? $this->session->userdata('end_user_id') : $id)->set('avatar', $filename)->update('end-users');

        return $res;
    }
    
    public function set_new_email($email, $id = null) {
        $this->load->database();
        $res = $this->db->where('id', !$id ? $this->session->userdata('end_user_id') : $id)->set('email', trim(strtolower($email)))->update('end-users');

        return $res;
    }

    public function set_privacy_true($id = null) {
        $this->load->database();
        $res = $this->db->where('id', !$id ? $this->session->userdata('end_user_id') : $id)->set('privacy', TRUE)->update('end-users');

        return $res;
    }
    
    public function set_privacy_false() {
        $this->load->database();
        $res = $this->db->where('id', $this->session->userdata('end_user_id'))->set('privacy', FALSE)->update('end-users');

        return $res;
    }

    public function verify_password($password) {
        $this->load->database();
        $res = $this->db->select('password')->where('id', $this->session->userdata('end_user_id'))->get('end-users');
        if($res->num_rows()) {
            return password_verify($password, $res->row_array()['password']);
        }

        return false;
    }

    public function do_logout() {
        $this->session->unset_userdata('end_user_username');
        $this->session->unset_userdata('end_user_email');
        $this->session->unset_userdata('end_user_id');
        $this->session->unset_userdata('end_user_privacy');
        $this->session->unset_userdata('end_user_google');
        $this->session->unset_userdata('end_user_facebook');
        $this->session->unset_userdata('end_user_password_set');

        return true;
    }

    public function do_social_login($email, $mode = null) {
        $id = $this->get_id_by_email($email);
        if($id) {
            $this->load->database();
            $this->db->where('id', $id)->set(array($mode => true, 'activated' => true))->update('end-users');
            return $this->new_session($id);
        } else {
            $new_user = array(
                'username'  => trim(strtolower(explode('@', $email)[0])),
                'email'     => trim(strtolower($email)),
                'password'  => 'OAUTH',
                'activated' => true,
                'avatar'    => 'usericon.svg',
                $mode       => true,
            );

            while($this->get_id_by_username($new_user['username'])) {
                $new_user['username'] .= rand(1,99);
            }

            $this->load->database();
            if($this->db->insert('end-users', $new_user)) { 
                return $this->new_session($this->db->insert_id());
            }
        }

        return false;
    }

    public function do_login($identifier, $password) {
        $identifier = trim(strtolower($identifier));
        $password   = trim($password);

        $this->load->database();

        $res = $this->db->where("`username` = '$identifier' OR `email` = '$identifier'")->limit(1)->get('end-users');

        if($res->num_rows()) {
            $user = $res->row_array();
            if(password_verify($password, $user['password'])) {
                if($user['activated']) {
                    // $this->create_remember_cookie($user['id']);
                    return $this->new_session($user['id'], $user['username'], $user['email'], $user['privacy'], $user['google'], $user['facebook']);
                } else
                    return 'unverified';
            }
        }

        return false;
    }

    public function do_activate($code) {
        $this->load->database();
        
        $res = $this->db->select('end_user_id')->where('code', $code)->get('end-users-verification');

        if($res->num_rows()) {
            $id = $res->row_array()['end_user_id'];

            $this->verify_user($id);

            $this->db
            ->where('end_user_id', $id)
            ->limit(1)
            ->delete('end-users-verification');

            return true;
        }

        return false;
    }

    public function do_register($username, $email, $password) {
        $username = trim(strtolower($username));
        $email    = trim(strtolower($email));
        $password = trim($password);

        $this->load->database();

        $new_user = array(
            'id'        => NULL,
            'username'  => $username,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'activated' => FALSE,
            'avatar'    => 'usericon.svg'
        );

        if($this->db->insert('end-users', $new_user)) {
            $verify_code = array(
                'id'          => NULL,
                'end_user_id' => $this->db->insert_id(),
                'code'        => sha1((time() + rand(1, 99)) . $username)
            );

            if($this->db->insert('end-users-verification', $verify_code)) {
                $options = $this->WebsiteModel->smtp_settings();
                $this->load->library('xl_mailer', $options);
                $general = $this->WebsiteModel->general_settings();
					$mail = array(
						'to' 		=> $email,
						'from'  	=> array($options['email'], 'Account Verification'),
						'reply_to' 	=> array($options['email'], 'Account Verification'),
						'subject'  	=> 'Verify your Account ' . $username . ' - ' . $general['title'],
						'message'  	=> compile_template(array(
							'logo' => base_url('application/uploads/img/' . $general['logo']),
							'web_url' => base_url(),
                            'username' => $username,
                            'verify_url' => base_url(USER_CONTROLLER . '/activate/' . $verify_code['code']),
							'year'		=> date('Y'),
							'name'		=> $general['title'],
						), file_get_contents(APPPATH . 'views/themes/' . $this->WebsiteModel->theme() . '/email_templates/user_verification.html')),
					);
	

					$res = $this->xl_mailer->send_mail($mail);
                    return true;
            }
        }

        return false;
    }

    public function verify_user($id) {
        $this->load->database();

        return $this->db
            ->where('id', $id)
            ->set('activated', TRUE)
            ->update('end-users');
    }
    
    public function unverify_user($id) {
        $this->load->database();

        return $this->db
            ->where('id', $id)
            ->set('activated', FALSE)
            ->update('end-users');
    }

    public function send_reset_code($id) {
        $new_code = array(
            'id'            => NULL,
            'end_user_id'   => $id,
            'code'          => sha1((time() + rand(1, 99)) . $id)
        );
        $user = $this->user_by_id($id);
        $this->load->database();
        $this->db->where('end_user_id', $id)->delete('end-users-password-reset');
        if($this->db->insert('end-users-password-reset', $new_code)) {
            $options = $this->WebsiteModel->smtp_settings();
                $this->load->library('xl_mailer', $options);
                $general = $this->WebsiteModel->general_settings();
					$mail = array(
						'to' 		=> $user['email'],
						'from'  	=> array($options['email'], 'Account Verification'),
						'reply_to' 	=> array($options['email'], 'Account Verification'),
						'subject'  	=> 'Reset your Password ' . $user['username'] . ' - ' . $general['title'],
						'message'  	=> compile_template(array(
							'logo' => base_url('application/uploads/img/' . $general['logo']),
							'web_url' => base_url(),
                            'username' => $user['username'],
                            'reset_url' => base_url(USER_CONTROLLER . '/reset/' . $new_code['code']),
							'year'		=> date('Y'),
							'name'		=> $general['title'],
						), file_get_contents(APPPATH . 'views/themes/' . $this->WebsiteModel->theme() . '/email_templates/password_reset.html')),
					);
	

					$res = $this->xl_mailer->send_mail($mail);

            return true;
        }

        return false;
    }

    public function reset_via_code($id, $new_pass) {
        $new_pass = trim($new_pass);
        $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        if($this->db->where('id', $id)->set('password', $new_pass)->limit(1)->update('end-users')) {
            if($this->db->where('end_user_id', $id)->delete('end-users-password-reset'))
                return true;
        }

        return false;
    }

    public function get_id_by_reset_code($code) {
        $this->load->database();

        $res = $this->db->select('end_user_id')->limit(1)->where('code', $code)->get('end-users-password-reset');
        if($res->num_rows()) {
            return $res->row_array()['end_user_id'];
        }

        return false;
    }

    public function get_id_by_username($username) {
        $email = trim(strtolower($email));

        $this->load->database();
        
        $res = $this->db->select('id')->limit(1)->where('username', $username)->get('end-users');
        if($res->num_rows()) {
            return $res->row_array()['id'];
        }

        return false;
    }

    public function get_id_by_email($email) {
        $email = trim(strtolower($email));

        $this->load->database();
        
        $res = $this->db->select('id')->limit(1)->where('email', $email)->get('end-users');
        if($res->num_rows()) {
            return $res->row_array()['id'];
        }

        return false;
    }

    public function total_registrations() {
        $this->load->database();
        return $this->db->get('end-users')->num_rows();
    }

    public function recent_registrations($num) {
        $this->load->database();
        return $this->db->order_by('id', 'desc')->limit($num)->get('end-users')->result_array();
    }
    
    public function weekly_registrations() {
        $this->load->database();
        return $this->db->where('YEARWEEK(`register_date`) = YEARWEEK(NOW())')->get('end-users')->num_rows();
    }

    public function user_by_id($id) {
        $this->load->database();

        return $this->db->where('id', $id)->limit(1)->get('end-users')->row_array();
    }

    public function delete_user($id) {
        $this->load->database();

        return $this->db->where('id', $id)->limit(1)->delete('end-users');
    }

    public function get_users($page) {
        $this->load->database();

        $amount = 25;

        return $this->db->offset(($page - 1) * $amount)->limit($amount)->get('end-users')->result_array();
    }

    public function get_users_by_query($query) {
        $query = trim(strtolower($query));

        $this->load->database();

        return $this->db->like('username', $query)->or_like('email', $query)->get('end-users')->result_array();
    }
}