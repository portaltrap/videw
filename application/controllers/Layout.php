<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* ------------------------------

Layout Controller

This controller is part of the Administrator Panel.
It is used to update Basic Settings for the Website.

Settings like Ads, Analytics, Header/Footer scripts, Meta Tags, Page Content are managed by this controller.

Only accessible to Administrators.

------------------------------ */

class Layout extends CI_Controller {
	private $page_data;
    private $admin_data;

	public function __construct() {
        parent::__construct();

        // Load Admin Model
        $this->load->model('Authentication/AdminModel');

        $this->page_data = $this->WebsiteModel->pageData(); // Retrieve Page Data from Cache or Database. WebsiteModel is autoloaded.
        $this->admin_data = $this->AdminModel->adminDetails(); // Retrieve details of the Admin user, If logged in.
        
        $this->page_data['update'] = $this->WebsiteModel->updates_settings();

        // Redirect to Login if not logged in.
        if(!$this->admin_data) {
            redirect(base_url(AUTH_CONTROLLER . '/login?redirect='.urlencode(current_url())));
        }
	}

	public function index() {
            redirect(base_url(LAYOUT_CONTROLLER . '/pages'));
    }
    
    // Used to update Header / Footer scripts of the website.
    public function scripts() {
        $data = array(                                                              // All Admin Side controller Methods follow this pattern.
            'page_data' => $this->page_data,                                        // Page data from WebsiteModel.
            'page_title' => 'Header / Footer Scripts',                              // Page title that is used in the views.
            'user' => $this->admin_data,                                            // Admin User Details
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $header = $this->input->post('site-header-scripts');
            $footer = $this->input->post('site-footer-scripts');

            $this->load->model('Settings/ScriptsModel');
            
            $this->ScriptsModel->set(array(
                'header' => htmlentities($header),
                'footer' => htmlentities($footer)
            ));

            $data['page_data']['scripts'] = $this->ScriptsModel->get(); // Refresh the Scripts inside $data['page_data];
            $data['alert'] = array(
                'type' => 'alert alert-success',
                'msg' => 'Scripts updated successfully'
            );
        }

        $this->load->view('admin/layout/scripts', $data);
    }

    // Used to Update analytics settings.
    public function analytics() {
        $data = array(
            'page_data' => $this->page_data,
            'page_title' => 'Analytics',
            'user' => $this->admin_data,
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $analytics = $this->input->post('site-analytics');

            $this->load->model('Settings/AnalyticsModel');
            
            $this->AnalyticsModel->set(array(
                'code' => htmlentities($analytics),
            ));

            $data['page_data']['analytics'] = $this->AnalyticsModel->get();
            $data['alert'] = array(
                'type' => 'alert alert-success',
                'msg' => 'Analytics Code updated successfully'
            );
        }

        $this->load->view('admin/layout/analytics', $data);
    }

    // Used to Update recaptcha settings.
    public function recaptcha() {
        $data = array(
            'page_data' => $this->page_data,
            'page_title' => 'Recaptcha',
            'user' => $this->admin_data,
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $site = $this->input->post('site-key');
            $secret = $this->input->post('secret-key');
            $status = $this->input->post('site-status');

            $this->load->model('Settings/RecaptchaModel');
            
            $rules = array();
            if($status) {
                $rules = array(
                    array(
                        'field' => 'site-key',
                        'label' => 'Site Key',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'secret-key',
                        'label' => 'Secret Key',
                        'rules' => 'required'
                    ),
                );
            }

            $this->form_validation->set_rules($rules);
            $validation = (count($rules) > 0) ? $this->form_validation->run() : true;
            if($validation) {
                $to_update = array(
                    'status' => ($status) ? true : false
                );

                if($site)   $to_update['site_key'] = $site;
                if($secret) $to_update['secret_key'] = $secret;

                $this->RecaptchaModel->set($to_update);

                $data['page_data']['recaptcha'] = $this->RecaptchaModel->get();
                $data['alert'] = array(
                    'type' => 'alert alert-success',
                    'msg' => 'Recaptcha Settings updated successfully'
                );
            }
        }

        $this->load->view('admin/layout/recaptcha', $data);
    }
    
    // Used to Update analytics settings.
    public function email() {
        $this->page_data['email'] = $this->WebsiteModel->smtp_settings();
        $data = array(
            'page_data' => $this->page_data,
            'page_title' => 'E-Mail Settings',
            'user' => $this->admin_data,

            'load_scripts' => array(
                'js/includes/email_settings.js'
            )
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $email      = $this->input->post('site-smtp-email');
            $status     = $this->input->post('site-smtp-status');
            $host       = $this->input->post('site-smtp-host');
            $port       = $this->input->post('site-smtp-port');
            $username   = $this->input->post('site-smtp-username');
            $password   = $this->input->post('site-smtp-password');

            $rules = array();
            if($status) {
                $rules = array(
                    array(
                        'field' => 'site-smtp-host',
                        'label' => 'Host',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'site-smtp-port',
                        'label' => 'host',
                        'rules' => 'required|numeric'
                    ),
                    array(
                        'field' => 'site-smtp-username',
                        'label' => 'Username',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'site-smtp-password',
                        'label' => 'Password',
                        'rules' => 'required'
                    )
                );
            }

            array_push($rules, array(
                'field' => 'site-smtp-email',
                'label' => 'E-Mail Address',
                'rules' => 'required|valid_email',
            ));

            $this->form_validation->set_rules($rules);
            $validation = $this->form_validation->run();

            if($validation) {
                $to_update = array(
                    'email' => strtolower($email)
                );

                if($status) {
                    $to_update['status']   = true;
                    $to_update['host']     = strtolower($host);
                    $to_update['port']     = $port;
                    $to_update['username'] = $username;
                    $to_update['password'] = $password;
                }

                $this->load->model('Settings/SmtpModel');

                $this->SmtpModel->set($to_update);
                $data['page_data']['email'] = $this->SmtpModel->get();
                $data['alert'] = array(
                    'type' => 'alert alert-success',
                    'msg' => 'E-Mail settings updated successfully'
                );
            } else {
                $data['alert'] = array(
                    'type' => 'alert alert-danger',
                    'msg' => 'Please fix the following errors below.'
                );
            }
        }

        $this->load->view('admin/layout/email', $data);
    }
    
    // Used to update Meta Tags.
    public function meta_tags() {
        $data = array(
            'page_data' => $this->page_data,
            'page_title' => 'Meta Tags',
            'user' => $this->admin_data,
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $tags = $this->input->post('site-meta-tags');

            $this->load->model('Settings/MetaModel');
            
            $this->MetaModel->set(array(
                'meta_tags' => htmlentities($tags),
            ));

            $data['page_data']['meta_tags'] = $this->MetaModel->get();
            $data['alert'] = array(
                'type' => 'alert alert-success',
                'msg' => 'Meta Tags updated successfully'
            );
        }

        $this->load->view('admin/layout/meta_tags', $data);
    }

    // Used to update Ads settings
    public function ads() {
        $data = array(
            'page_data' => $this->page_data,
            'page_title' => 'Ad Settings',
            'user' => $this->admin_data
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            // Fetch the Statuses of Ad settings.
            $top_status     = $this->input->post('site-top-ad-status');
            $bottom_status  = $this->input->post('site-bottom-ad-status');
            $pop_status     = $this->input->post('site-pop-ad-status');

            // Top Ad Code is required if Status is enabled.
            if($top_status)
                $this->form_validation->set_rules('site-top-ad-code', 'Top Ad Code', 'required');

            // Bottom Ad Code is required if Status is enabled.
            if($bottom_status)
                $this->form_validation->set_rules('site-bottom-ad-code', 'Bottom Ad Code', 'required');

            // Pop Ad Code is required if status is enabled.
            if($pop_status)
                $this->form_validation->set_rules('site-pop-ad-code', 'Pop Ad Code', 'required');

            
            // Fetch Ad codes
            $top_code       = $this->input->post('site-top-ad-code');
            $bottom_code    = $this->input->post('site-bottom-ad-code');
            $pop_code       = $this->input->post('site-pop-ad-code');

            // $to_update fields, True or False based on statuses
            $to_update = array(
                'top_ad_status'     => ($top_status) ? true : false,
                'bottom_ad_status'  => ($bottom_status) ? true : false,
                'pop_ad_status'     => ($pop_status) ? true : false,
            );
        
            // If they exist, then convert to htmlentities and add ad codes into $to_update.
            if($top_code)       $to_update['top_ad']    = htmlentities($top_code);
            if($bottom_code)    $to_update['bottom_ad'] = htmlentities($bottom_code);
            if($pop_code)       $to_update['pop_ad']    = htmlentities($pop_code);

            // Load Ads Model
            $this->load->model('Settings/AdsModel');

            if($this->form_validation->run()) {
                $this->AdsModel->set($to_update);

                $data['alert'] = array(
                    'type'  => 'alert alert-success',
                    'msg'   => 'Ad settings updated successfully.'
                );
            } else if(!$top_status && !$bottom_status && !$pop_status) {
                $this->AdsModel->set($to_update);

                $data['alert'] = array(
                    'type'  => 'alert alert-success',
                    'msg'   => 'Ad settings updated successfully.'
                );
            }

            // Refresh Ads settings.
            $data['page_data']['ads'] = $this->AdsModel->get();                
        }

        $this->load->view('admin/layout/ads', $data);
    }

    // Function responsible for changing Page Settings.
    public function pages() {
        $this->load->model('Settings/PageModel');

        $data = array(
            'page_data'     => $this->page_data,
            'page_title'    => 'Page Settings',
            'user'          => $this->admin_data,
            'all_pages'     => $this->PageModel->get(),

            'load_scripts'  => array(
                'js/includes/sortables.min.js',
                'js/includes/sortable_list.js'
            )
        );

        $this->load->view('admin/layout/pages/main', $data);
    }

    // Responsible for editing a specific page.
    public function edit_page($permalink = null) {
        $this->load->model('Settings/PageModel');
        if($permalink && $page = $this->PageModel->get_page($permalink)) {
            $data = array(
                'page_data'     => $this->page_data,
                'page_title'    => 'Editing ' . html_entity_decode($page['title']),
                'user'          => $this->admin_data,
                'page'          => $page,

                'load_scripts'  => array(
                    'js/plugin/summernote/summernote-bs4.min.js',
                    'js/includes/editor.js'
                )
            );

            if($this->input->post('submit') && !$data['user']['disabled']) {
                $title      = $this->input->post('page-title');
                $content    = $this->input->post('page-content');
                $permalink  = $this->input->post('page-permalink');
                $position   = $this->input->post('page-position');
                $status     = $this->input->post('page-status');
                
                $rules = array(
                    array(
                        'field'     => 'page-title',
                        'label'     => 'Title',
                        'rules'     => 'required'
                    ),
                    array(
                        'field'     => 'page-content',
                        'label'     => 'Content',
                        'rules'     => 'required'
                    ),
                    array(
                        'field'     => 'page-position',
                        'label'     => 'Position',
                        'rules'     => 'required|in_list[1,2,3]'
                    )
                );

                if($permalink != $page['permalink']) {
                    $this->load->database();
                    array_push($rules, array(
                        'field'     => 'page-permalink',
                        'label'     => 'Permalink',
                        'rules'     => 'required|is_unique[pages.permalink]|alpha_dash',
                        'errors'    => array(
                            'is_unique' => 'That permalink is already in use.',
                            'alpha_dash' => 'Please only use Alphanumeric characters, undescrores & dashes.'
                        )
                    ));
                }

                $this->form_validation->set_rules($rules);
                $validation = $this->form_validation->run();

                if($validation) {
                    $to_update = array(
                        'title' => htmlentities($title),
                        'content' => htmlentities($content),
                        'permalink' => strtolower($permalink),
                        'position'  => $position,
                        'status' => $status ? true : false
                    );

                    $this->PageModel->set_page($page['permalink'], $to_update);

                    $this->session->set_flashdata('alert', array(
                        'type' => 'alert alert-success',
                        'msg'  => 'Successfully updated Page.'
                    ));

                    redirect(LAYOUT_CONTROLLER . '/edit_page/' . $to_update['permalink']);
                }
            }

            $this->load->view('admin/layout/pages/edit_page', $data);
        } else
            redirect(base_url(LAYOUT_CONTROLLER . '/pages'));
    }

    // Function responsible for Creating Page.
    public function create_page() {
        $data = array(
            'page_data'     => $this->page_data,
            'page_title'    => 'Create A New Page',
            'user'          => $this->admin_data,
        
            'load_scripts'  => array(
                'js/plugin/summernote/summernote-bs4.min.js',
                'js/includes/editor.js'
            )
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $title      = $this->input->post('page-title');
            $content    = $this->input->post('page-content');
            $permalink  = $this->input->post('page-permalink');
            $position   = $this->input->post('page-position');

            $this->load->database();
            $rules = array(
                array(
                    'field'     => 'page-title',
                    'label'     => 'Title',
                    'rules'     => 'required'
                ),
                array(
                    'field'     => 'page-content',
                    'label'     => 'Content',
                    'rules'     => 'required'
                ),
                array(
                    'field'     => 'page-position',
                    'label'     => 'Position',
                    'rules'     => 'required|in_list[1,2,3]'
                )
            );

            if($permalink) {
                array_push($rules, array(
                    'field'     => 'page-permalink',
                    'label'     => 'Permalink',
                    'rules'     => 'required|is_unique[pages.permalink]|alpha_dash',
                    'errors'    => array(
                        'is_unique' => 'That permalink is already in use.',
                        'alpha_dash' => 'Please only use Alphanumeric characters, undescrores & dashes.'
                    )
                ));
            } else {
                $permalink = securePermalink($title);
            }

            $this->form_validation->set_rules($rules);
            $validation = $this->form_validation->run();

            if($validation) {
                $this->load->model('Settings/PageModel');

                $order = $this->PageModel->get_new_page_order();

                $new_page = array(
                    'title'         => htmlentities($title),
                    'content'       => htmlentities($content),
                    'permalink'     => strtolower($permalink),
                    'position'      => $position,
                    'status'        => true,
                    'page_order'    => $order
                );

                $this->PageModel->create_page($new_page);

                $this->session->set_flashdata('alert', array(
                    'type' => 'alert alert-success',
                    'msg'  => 'New page created successfully.'
                ));

                redirect(base_url(LAYOUT_CONTROLLER . '/edit_page/' . strtolower($permalink)));
            }
        }

        $this->load->view('admin/layout/pages/create_page', $data);
    }
    
    // Used as the Confirmation for deleting a page.
    public function delete_page($permalink = null, $confirm = false) {
        $this->load->model('Settings/PageModel');
        if($permalink && $page = $this->PageModel->get_page($permalink)) {
            $data = array(
                'page_data'     => $this->page_data,
                'page_title'    => 'Delete ' . html_entity_decode($page['title']),
                'user'          => $this->admin_data,
                'page'          => $page,
            );

            if($confirm && !$data['user']['disabled']) {
                $this->PageModel->delete_page($permalink);
                redirect(base_url(LAYOUT_CONTROLLER . '/pages'));
            }

            $this->load->view('admin/layout/pages/delete_page.php', $data);
        } else
            redirect(base_url(LAYOUT_CONTROLLER . '/pages'));
    }

    // AJAX Route for Updating Page Order.
    public function update_page_order() {
        $data = $this->input->post('order');

        if($data && $this->input->post('ref')) {
            $this->load->model('Settings/PageModel');
            $res = $this->PageModel->set_order(json_decode($data, true));

            if($res) {
                echo json_encode(array(
                    'success' => true
                ));
                return true;
            }
        }

        echo json_encode(array(
            'success' => false
        ));

        return false;
    }

    public function social_keys() {
        $this->page_data['social_keys'] = $this->WebsiteModel->social_keys();

        $data = array(
            'page_data' => $this->page_data,
            'page_title' => 'Social Login Keys',
            'user'      => $this->admin_data
        );

        if($this->input->post('submit') && !$data['user']['disabled']) {
            $g_public = $this->input->post('google-public');
            $g_secret = $this->input->post('google-secret');
            $f_public = $this->input->post('facebook-public');
            $f_secret = $this->input->post('facebook-secret');

            $to_update = array();
            if($g_public) $to_update['google_public'] = trim($g_public);
            if($g_secret) $to_update['google_secret'] = trim($g_secret);
            if($f_public) $to_update['facebook_public'] = trim($f_public);
            if($f_secret) $to_update['facebook_secret'] = trim($f_secret);

            $this->load->model('Settings/SocialKeysModel');
            if(count($to_update) > 0)
                $this->SocialKeysModel->set($to_update);

            $data['page_data']['social_keys'] = $this->SocialKeysModel->get();
            $data['alert'] = array(
                'type' => 'alert alert-success',
                'msg'  => 'Social Login API Keys updated successfully.'
            );
        }

        $this->load->view('admin/layout/social_keys', $data);
    }
}
