<?php

defined('BASEPATH') || exit('Access Denied.');

class Xl_mailer {
    private $CI;
    private $options;

    public function __construct($options) {
        $this->CI =& get_instance();
        $this->options = $options;
    }

    public function send_mail($mail) {
        // Is SMTP Enabled ?
        
        $this->CI->load->library('email');
        $config = array();
        if($this->options['status']) { // Use SMTP where Available.
            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => $this->options['host'],
                'smtp_user' => $this->options['username'],
                'smtp_pass' => $this->options['password'],
                'smtp_port' => $this->options['port'],
            );
        } else { // Fallback to PHP Mail function.
            $config = array(
                'protocol' => 'mail',
            );
        }

        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n";

        $this->CI->email->initialize($config);
        $this->CI->email->to($mail['to']);
        $this->CI->email->from($mail['from'][0], $mail['from'][1]);
        $this->CI->email->reply_to($mail['reply_to'][0], $mail['reply_to'][1]);
        if(isset($mail['cc'])) 
            $this->CI->email->cc($mail['cc']);
        if(isset($mail['bcc'])) 
            $this->CI->email->cc($mail['bcc']);

        $this->CI->email->subject($mail['subject']);
        $this->CI->email->message(nl2br($mail['message']));

        return $this->CI->email->send();
    }
}