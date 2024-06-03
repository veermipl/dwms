<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Twilio\Rest\Client;
 
class Welcome {
 
    // public function index()
    // {
    //     $data = ['phone' => '+8801777282772', 'text' => 'Hello, CI'];
    //     print_r($this->sendSMS($data));
    // }

    public function index(){
        $this->load->view('help',true);
    }
 
    protected function sendSMS($data) {
          // Your Account SID and Auth Token from twilio.com/console
            $sid = 'test';
            $token = 'test';
            $client = new Client($sid, $token);
            
            // Use the client to do fun stuff like send text messages!
             return $client->messages->create(
                // the number you'd like to send the message to
                $data['phone'],
                array(
                    // A Twilio phone number you purchased at twilio.com/console
                    "from" => "+15183536455",
                    // the body of the text message you'd like to send
                    'body' => $data['text']
                )
            );
    }
}