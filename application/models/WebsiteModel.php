<?php

defined('BASEPATH') || exit('Direct script access is prohibited.');

class WebsiteModel extends CI_Model {
    private $theme;
    private $language;

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings/ThemesModel');
        $this->config->load('languages');
        $this->language = get_language();
    }

    public function admin_settings() {
        $this->load->model('Authentication/AdminModel');
        return $this->AdminModel->adminDetails();
    }

    public function general_settings() {
        $this->load->model('Settings/GeneralModel');
        return $this->GeneralModel->get();
    }

    public function scripts_settings() {
        $this->load->model('Settings/ScriptsModel');
        return $this->ScriptsModel->get();
    }
    
    public function ads_settings() {
        $this->load->model('Settings/AdsModel');
        return $this->AdsModel->get();
    }
    
    public function meta_settings() {
        $this->load->model('Settings/MetaModel');
        return $this->MetaModel->get();
    }
    
    public function analytics_settings() {
        $this->load->model('Settings/AnalyticsModel');
        return $this->AnalyticsModel->get();
    }
    
    public function smtp_settings() {
        $this->load->model('Settings/SmtpModel');
        return $this->SmtpModel->get();
    }
    
    public function all_pages() {
        $this->load->model('Settings/PagesModel');
        return $this->PagesModel->get();
    }
    
    public function recaptcha_settings() {
        $this->load->model('Settings/RecaptchaModel');
        return $this->RecaptchaModel->get();
    }
    
    public function updates_settings() {
        $this->load->model('UpdatesModel');
        return $this->UpdatesModel->update_info();
    }
    
    public function theme() {
        return $this->ThemesModel->get();
    }

    public function theme_view() {
        $theme = $this->ThemesModel->get();
        return function($view, $data = null) use ($theme) { return $this->load->view('themes/'.$theme.'/'.$view, $data); };
    }
    
    public function theme_assets() {
        $theme = $this->ThemesModel->get();
        return function($path) use ($theme) { echo base_url('application/views/themes/'.$theme.'/assets/'.$path); return true; };
    }
    
    public function language() {
        return $this->language;
    }

    public function language_mode() {
        return $this->config->item('idioms')[$this->language];
    }

    public function user_session() {
        $this->load->model('Authentication/UserModel');
        return $this->UserModel->user_info();
    }

    public function social_keys() {
        $this->load->model('Settings/SocialKeysModel');
        return $this->SocialKeysModel->get();
    }

    public function s3_settings() {
        $this->load->model('Settings/S3Model');
        return $this->S3Model->get();
    }
    
    public function upload_settings() {
        $this->load->model('Settings/UploadModel');
        return $this->UploadModel->get();
    }

    public function pageData() {
        /* Loading all Basic Models */
        $this->load->model('Settings/GeneralModel');
        $this->load->model('Settings/ScriptsModel');
        $this->load->model('Settings/MetaModel');
        $this->load->model('Settings/AdsModel');
        $this->load->model('Settings/RecaptchaModel');
        $this->load->model('Settings/AnalyticsModel');
        $this->load->model('Settings/ThemesModel');
        $this->load->model('Settings/PageModel');
        
        /* Initializing Settings with Data from Cache or Database */

        $idiom = get_language();
        $settings = array(
            'general'   => $this->GeneralModel->get(),
            'scripts'   => $this->ScriptsModel->get(),
            'meta_tags' => $this->MetaModel->get(),
            'ads'       => $this->AdsModel->get(),
            'recaptcha' => $this->RecaptchaModel->get(),
            'analytics' => $this->AnalyticsModel->get(),
            'pages'     => $this->PageModel->get(),
            'theme'     => $this->ThemesModel->get(),
            'idiom'     => $this->language,
            'lang_mode' => $this->language_mode()
        );

        $theme = $settings['theme'];

        /* Returning Anonymous functions to Retrieve Theme Specific Views & Assets */

        $settings['theme_view'] = function($view, $data = null) use ($theme) { return $this->load->view('themes/'.$theme.'/'.$view, $data); };
        $settings['assets']     = function($path) use ($theme) { echo base_url('application/views/themes/'.$theme.'/assets/'.$path); return true; };
        return $settings;
    }
}