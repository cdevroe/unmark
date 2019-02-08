<?php defined("BASEPATH") or exit("No direct script access allowed");

class Plain_Email extends CI_Email {

    public function __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();
    }

    protected function getTextVersion($message)
    {
        $find     = array('<br/>', '<br>', '<br />', '</p>');
        $replace  = array("\r\n", "\r\n", "\r\n", "\r\n\r\n");
        return strip_tags(str_replace($find, $replace, $message));
    }

    public function resetPassword($email, $url)
    {
        $file     = (file_exists(CUSTOMPATH . 'views/email/forgot-password.php')) ? CUSTOMPATH . 'views/email/forgot-password.php' : APPPATH . 'views/email/forgot-password.php';
        $message  = file_get_contents($file);
        $find     = array('{URL}', '{BASEURL}');
        $replace  = array($url, $this->CI->config->item('base_url'));
        $message  = str_replace($find, $replace, $message);
        $text     = $this->getTextVersion($message);

        $email_from = $this->CI->config->item('email_from');
        if(empty($email_from) || empty($email_from['address'])){
            $this->CI->exceptional->createTrace(E_ERROR, 'No sender address for outgoing email set in config - cannot send.', __FILE__, __LINE__);
            return false;
        }
        $this->from($email_from['address'], $email_from['description']);
        $reply_to = $this->CI->config->item('email_reply_to');
        if(empty($reply_to) || empty($reply_to['address'])){
            $reply_to = $email_from;
        }
        $this->reply_to($reply_to['address'], $reply_to['description']);
        $this->to($email);
        $subject = $this->CI->config->item('password_reset_email_subject');
        if(empty($subject)){
            $subject = 'Unmark - Password reset';
        }
        $this->subject($subject);
        $this->message($message);
        $this->set_alt_message($text);

        $result = $this->send();
        if ($result === false) {
            $this->CI->exceptional->createTrace(E_ERROR, 'Could not send reset password email.', __FILE__, __LINE__, array('debug' => $this->print_debugger()));
        }
        return $result;
    }

    public function subject( $subject )
    {
        parent::subject($subject);
        $this->_subject = $subject;
        return $this;
    }

    public function updatePassword($email)
    {
        $file     = (file_exists(CUSTOMPATH . 'views/email/update-password.php')) ? CUSTOMPATH . 'views/email/update-password.php' : APPPATH . 'views/email/update-password.php';
        $find     = array('{BASEURL}');
        $replace  = array($this->CI->config->item('base_url'));
        $message  = file_get_contents($file);
        $message  = str_replace($find, $replace, $message);
        $text     = $this->getTextVersion($message);

        $email_from = $this->CI->config->item('email_from');
        if(empty($email_from) || empty($email_from['address'])){
            $this->CI->exceptional->createTrace(E_ERROR, 'No sender address for outgoing email set in config - cannot send.', __FILE__, __LINE__);
            return false;
        }
        $this->from($email_from['address'], $email_from['description']);
        $reply_to = $this->CI->config->item('email_reply_to');
        if(empty($reply_to) || empty($reply_to['address'])){
            $reply_to = $email_from;
        }
        $this->reply_to($reply_to['address'], $reply_to['description']);
        $this->to($email);
        $subject = $this->CI->config->item('password_updated_email_subject');
        if(empty($subject)){
            $subject = 'Unmark - Password updated';
        }
        $this->subject($subject);
        $this->message($message);
        $this->set_alt_message($text);

        $result = $this->send();
        if ($result === false) {
            $this->CI->exceptional->createTrace(E_ERROR, 'Could not send update password email.', __FILE__, __LINE__, array('debug' => $this->print_debugger()));
        }
        return $result;
    }

    public function initialize(array $config = array() ){
        // Use passed config first
        if(!empty($config)){
            return parent::initialize($config);
        } else{
            // If nothing passed - try loading settings from config
            $emailConfig = config_item('plain_email_settings');
            if(!empty($emailConfig)){
                return parent::initialize($emailConfig);
            // No settings in config - use defaults
            } else{
                return parent::initialize();
            }
        }
    }

}