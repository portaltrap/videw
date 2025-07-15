<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* ------------------------------

General Controller

This controller is part of the Administrator Panel.
It is used to update General Settings, View Dashboard, and Update Themes for the Website.

Only accessible to Administrators.

------------------------------ */

class General extends CI_Controller {
	private $page_data;
    private $admin_user;

    /* This is a Controller only accessible to Administrator Accounts */
	public function __construct() {
        parent::__construct();

        // Load Admin model
        $this->load->model('Authentication/AdminModel');

        $this->page_data = $this->WebsiteModel->pageData();     // Retrieve Page Data, WebsiteModel is Auto-loaded
        $this->admin_user = $this->AdminModel->adminDetails();  // Retrive Admin Details, if logged in.
        
        $this->page_data['update'] = $this->WebsiteModel->updates_settings();

        // Check if Admin is logged in. Redirect to login otherwise.
        if(!$this->admin_user) {
            redirect(base_url(AUTH_CONTROLLER . '/login?redirect='.urlencode(current_url())));
        }
	}

	public function index() {
        redirect(base_url(GENERAL_CONTROLLER . '/settings'));
    }
    
    // This Function is responsible for Loading and Visualizing Basic Information for the User.
    public function dashboard() {
        $this->load->model('Authentication/UserModel');
        $this->load->model('Uploads/UserUploadModel');

        $this->page_data['recent_users'] = $this->UserModel->recent_registrations(10);
        $this->page_data['weekly_users'] = $this->UserModel->weekly_registrations();
        $this->page_data['total_users'] = $this->UserModel->total_registrations();
        
        $this->page_data['recent_uploads'] = $this->UserUploadModel->recent_uploads(10);
        $this->page_data['weekly_uploads'] = $this->UserUploadModel->weekly_uploads();
        $this->page_data['total_uploads'] = $this->UserUploadModel->total_uploads();
        $data = array(
            'page_title' => 'Dashboard',
            'page_data' => $this->page_data,
            'user' => $this->admin_user
        );

        $this->load->view('admin/general/dashboard', $data);
    }

    public function _alpha_dash_space($str_in) {
        if (! preg_match("/^([-a-z0-9_ ])+$/i", $str_in)) {
			$this->form_validation->set_message('_alpha_dash_space', 'The %s field may only contain alpha-numeric characters, spaces, underscores, and dashes.');
			return FALSE;
		}
		else {
			return TRUE;
		}
    }

    // This method is used to update General Settings.
    public function settings() {
        $data = array(
            'page_title' => 'General Settings',
            'page_data' => $this->page_data,
            'user' => $this->admin_user
        );

        // If the User Submits the Form.
        if($this->input->post('submit') && !$data['user']['disabled']) {
            // Retrieve POST fields
            $title               = $this->input->post('site-title');
            $description         = $this->input->post('site-description');
            $keywords            = $this->input->post('site-keywords');
            $enable_registration = $this->input->post('site-enable-registration');

            // Load GeneralModel
            $this->load->model('Settings/GeneralModel');

            $this->form_validation->set_rules(
                'site-title',
                'Website Title',
                'required|callback__alpha_dash_space|max_length[70]'
            );

            if($this->form_validation->run()) {
                // Fields to Update
                $to_update = array(
                    'title' => $title,
                    'description' => $description,
                    'keywords' => $keywords,
                    'enable_registration' => $enable_registration ? true : false,
                );

                // Check if Logo or Favicon was uploaded by User. - is_uploaded & is_image are functions from the 'upload' helper.
                if(is_uploaded('site-logo') || is_uploaded('site-favicon')) {

                    // Load the Uploader Class if true.
                    $this->load->library('upload', array(
                        'upload_path' => APPPATH.'uploads/img/',
                        'allowed_types' => '*',
                        'overwrite' => false,
                    ));

                    // This block of code runs for both Favicon & Logo. It will upload the Logo. If there is an error, It will push that error inside $data
                    if(is_uploaded('site-logo')) {
                        if(is_image('site-logo')) {
                            $success = $this->upload->do_upload('site-logo');
                            if($success) {
                                $res = $this->upload->data();
                                $name = $res['file_name'];
                                $to_update['logo'] = $name;
                            } else {
                                $data['logo_error'] = 'An unexpected error occured.';
                            }
                        } else {
                            $data['logo_error'] = 'Only .gif, .jpg, .jpeg, .png, .svg Files are allowed.';
                        }
                    }

                    if(is_uploaded('site-favicon')) {
                        if(is_image('site-favicon')) {
                            $success = $this->upload->do_upload('site-favicon');
                            if($success) {
                                $res = $this->upload->data();
                                $name = $res['file_name'];
                                $to_update['favicon'] = $name;
                            } else {
                                $data['logo_error'] = 'An unexpected error occured.';
                            }
                        } else {
                            $data['logo_error'] = 'Only .gif, .jpg, .jpeg, .png, .svg Files are allowed.';
                        }
                    }
                        
                }

                // GenralModel is a Getter & Setter model. Passing an array of Fields to Update.
                $this->GeneralModel->set($to_update);
                $data['page_data']['general'] = $this->GeneralModel->get(); // Refresh General Settings after the Update.
                $data['alert'] = array('type' => 'alert alert-success', 'msg' => 'General settings updated successfully.');
            }
        }

        $this->load->view('admin/general/settings', $data);
    }

    // This method lets the user choose the theme for their website.
    public function themes() {
        $this->load->model('Settings/ThemesModel');

        $data = array(
            'page_title' => 'Theme Settings',
            'page_data' => $this->page_data,
            'user' => $this->admin_user,
            'current_theme' => $this->ThemesModel->get(),
            'themes' => $this->ThemesModel->getAvailableThemes(), // This method inside ThemesModel will return an array of installed themes.
        );

        $this->load->view('admin/general/themes/main', $data);
    }

    // This method lets the user upload a theme.
    public function upload_theme() {
        $this->load->model('Settings/ThemesModel');

        $data = array(
            'page_title' => 'Theme Upload',
            'page_data' => $this->page_data,
            'user' => $this->admin_user,
        );

        // If Submitted.
        if($this->input->post('submit')) {
            // If file uploaded.
            if(file_exists($_FILES['theme']['tmp_name'])) {
                $this->load->library('upload', array(
                    'upload_path' => APPPATH.'uploads/themes/',
                    'allowed_types' => 'zip|rar',
                    'overwrite' => false,
                ));

                $success = $this->upload->do_upload('theme');
                if($success && !$data['user']['disabled']) {
                    $file = $this->upload->data();

                    // Set up a ZipArchive and open up the newly uploaded zip file.
                    $zip = new ZipArchive;
                    $res = $zip->open($file['full_path']);

                    if($res) {
                        // If successful, Extract the Theme to it's own folder inside /views/themes - And Close the Zip archive.
                        $zip->extractTo(APPPATH.'views/themes/' . str_replace('.zip', '', strtolower($file['file_name'])));
                        $zip->close();
                        $this->session->set_flashdata('alert', array(
                            'type' => 'alert alert-success',
                            'msg'  => 'Theme Installed successfully. To use the new theme, Activate it from the list below.'
                        ));

                        // Redirect to Themes page.
                        redirect(base_url(GENERAL_CONTROLLER . '/themes'));
                    }

                    // Delete the uploaded file.
                    unlink($file['full_path']);
                } else 
                    // Show an error if anything other than .zip was uploaded.
                    $data['alert'] = array(
                        'type' => 'alert alert-danger',
                        'msg'  => !$data['user']['disabled'] ? 'Unknown file format. Please only upload .zip files.' : 'Disabled in Demo Mode.'
                    );
            }
        }

        $this->load->view('admin/general/themes/upload', $data);
    }

    // This function is a middleware function. This function checks if the Theme exists using the method provided by ThemesModel, and applies the theme if it exists.
    public function set_theme($theme = null) {
        if($theme) {
            // Check if Theme exists. If yes, load it's manifest.
            if($manifest = $this->ThemesModel->doesThemeExist($theme)) {
                // Set the Website theme to the theme.
                if(!$data['user']['disabled'])
                    $this->ThemesModel->set(array(
                        'theme' => trim(strtolower($theme))
                    ));

                // Set an alert.
                $this->session->set_flashdata('alert', array(
                    'type' => 'alert alert-success',
                    'msg' => $manifest['name'] . ' was applied successfully.'
                ));
            }
        }

        // Redirect back to Themes.
        redirect(base_url(GENERAL_CONTROLLER . '/themes'));
    }

    // This function is also a middleware function. It is used to clean the cache entirely.
    public function purge_cache() {
        // Load cache driver & Clean it.
        $this->load->driver('cache', array('adapter' => 'file'));
        $this->cache->clean();

        // Set an Alert.
        $this->session->set_flashdata('alert', array(
            'type' => 'alert alert-success',
            'msg' => 'Destroyed all cache successfully.'
        ));

        // Redirect to Dashboard.
        redirect(base_url(GENERAL_CONTROLLER . '/dashboard'));
    }
}
