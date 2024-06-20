<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SendMail
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->library('email');
    }

    public function send($from, $to, $subject, $message, $from_name, $email_settings, $attach = null)
    {
        $from_email = $from;
        $to_email = $to;
        $subject = $subject;
        $message = $message;

        // Email configuration
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => $email_settings->smtp_host,
            'smtp_port' => $email_settings->smtp_port,
            'smtp_user' => $email_settings->user,
            'smtp_pass' => $email_settings->password,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        );

        $this->CI->email->initialize($config);

        $this->CI->email->from($from_email, $from_name);
        $this->CI->email->to($to_email);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        $this->CI->email->attach($attach);
        $this->CI->email->send();

        return true;

        // if ($this->CI->email->send()) {
        //     return true;
        // } else {
        //     print_r($this->CI->email->print_debugger());
        //     die;
        // }
    }
}
