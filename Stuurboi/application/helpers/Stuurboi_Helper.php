<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function sendToServer($controller, $method, $httpRequest, $data = null) {

    if (!empty($controller) && !empty($method) && !empty($httpRequest)) {
        $url = 'http://192.168.8.101/api/' . $controller . '/' . $method;
        //initialising the url
        $ch = curl_init($url);
        /**
         * Authenticating access to the server and sending user data from registration form
         */
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . Constants::API_KEY,
            $httpRequest == Constants::HTTP_PUT ? 'Content-Type: application/x-www-form-urlencoded' : ''));
        curl_setopt($ch, CURLOPT_USERPWD, Constants::API_AUTH_USERNAME . ':' . Constants::API_AUTH_PASSWORD);

        switch ($httpRequest) {
            case Constants::HTTP_POST:
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                break;
            case Constants::HTTP_PUT:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

                break;
            case Constants::HTTP_GET:
                break;
            case Constants::HTTP_DELETE:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default:
                return null;
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $res = json_decode($result);
    }
}

function sendEmail($from, $to, $message, $subject = null, $mailType = 'html') {
    $CI =& get_instance();
    $CI->load->email('encrypt');

    $config = array(
        'protocol' => 'SMTP',
        'smtp_host' => 'http://localhost',
        'smtp_port' => 465,
        'smtp_user' => $from,
        'smtp_pass' => Constants::API_AUTH_PASSWORD,
        'mailtype' => $mailType,
        'charset' => 'utf-8'
    );
    $CI->email->initialize($config);
    $CI->email->set_mailtype($mailType);
    $CI->set_newline("\r\n");

    if (!empty($message) && !empty($from) && !empty($to)) {
        $CI->email->to($to);
        $CI->email->from($from);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if ($CI->email->send()) {
            return true;
        }
    }
    return false;
}

function isOnline() {
    $CI =& get_instance();
return isset($_SESSION['userId']) && isset($_SESSION['userType']) && isset($_SESSION['name']);}

/***************************************************************************
     * EMAIL VERIFICATION
     **************************************************************************/
function sendEmail_post($receiver, $from, $message, $subject = ''){
        //config email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = $from;
        $config['smtp_pass'] = '******';  //sender's password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = 'TRUE';
        $config['newline'] = "\r\n"; 
        
        $this->load->library('email', $config);
		$this->email->initialize($config);
        //send email
        $this->email->from($from);
        $this->email->to($receiver);
        $this->email->subject($subject);
        $this->email->message($message);
        
        if($this->email->send()){
            return false;
        }
        return false;
    }
//upload file on local server
function uploadFile($filename, $path, $allowedTypes = '*') {
    $CI = get_instance();
    $config['upload_path'] = $path;
    $config['allowed_types'] = $allowedTypes;
    $CI->upload->initialize($config);
    if ($CI->upload->do_upload($filename)) {
        return $CI->upload->data();
    } else {
        return null;
    }
}
//upload file to remote server
function uploadToRemote($sourceServer, $destServer, $host = 'htpp://localhost') {
    $CI = get_instance();
    $this->load->library('ftp');

    //FTP Configurations
    $ftp_config['hostname'] = $host;
    $ftp_config['username'] = Constants::API_AUTH_USERNAME;
    $ftp_config['password'] = Constants::API_AUTH_PASSWORD;
    $ftp_config['debug'] = TRUE;

    //Connect to remote server
    $CI->ftp->connect($ftp_config);
    //upload to remote server
    if ($CI->ftp->upload($sourceServer, $destServer)) {
        //close ftp connection
        $CI->ftp->close();
        //delete file from local server
        @unlink($sourceServer);
        return true;
    }
    return false;
}