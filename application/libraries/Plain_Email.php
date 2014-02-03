<?php defined("BASEPATH") or exit("No direct script access allowed");

require_once(BASEPATH.'Libraries/Email.php');

class Plain_Email extends CI_Email {

    public $is_bulk   = false;
    private $postmark = false;

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
        $message  = file_get_contents(APPPATH . 'views/email/forgot-password.php');
        $find     = array('{URL}');
        $replace  = array($url);
        $message  = str_replace($find, $replace, $message);
        $text     = $this->getTextVersion($message);

        $email_from = $this->CI->config->item('email_from');
        if(empty($email_from) || empty($email_from['address'])){
            log_message('ERROR', 'No sender address for outgoing email set in config - cannot send.');
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
            $subject = 'Nilai - Password reset';
        }
        $this->subject($subject);
        $this->message($message);
        $this->set_alt_message($text);
        return $this->send();
    }

    public function subject( $subject )
    {
        parent::subject($subject);
        $this->_subject = $subject;
        return $this;
    }

    public function updatePassword($email)
    {

        $message  = file_get_contents(APPPATH . 'views/email/update-password.php');
        $text     = $this->getTextVersion($message);

        $this->from('support@getbarley.com', 'Barley Support');
        $this->reply_to('support@getbarley.com', 'Barley Support');
        $this->to($email);
        $this->subject('Barley Password Updated');
        $this->message($message);
        $this->set_alt_message($text);
        return $this->send();
    }

}