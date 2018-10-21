<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Owners
 *
 * @author VetjurV4
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Management class created by CodexWorld
 */
require(APPPATH . '/libraries/REST_Controller.php');
class Owners  extends REST_Controller {
    
  
    function __construct() {     
        parent::__construct();               
    }

      /*****OWNER***********************/
     public function signup_post(){
     
        $userData = array(
                'UserId' => $this->input->post('userId'),
                'idNumber' => $this->input->post('idNumber'),
                'idDocument'=>$this->input->post('idDocument'),
                'clearenceDocument'=>$this->input->post('clearenceDocument'),
                'licensePlate'=>$this->input->post('licensePlate'),
                'model'=>$this->input->post('model'),
                'vehicleType'=>$this->input->post('vehicleType'),
                'facePhoto'=>$this->input->post('facePhoto'),
                'criminalRecord'=>$this->input->post('criminalRecord')  
            );
       
        if(!empty($userData['UserId']) && !empty($userData['idNumber'])&& !empty($userData['idDocument']) && !empty($userData['clearenceDocument'])&&
           !empty($userData['licensePlate']) && !empty($userData['model']) && !empty($userData['vehicleType']) &&
           !empty($userData['facePhoto']) && !empty($userData['criminalRecord']))
            {
               
             $insert = insert('owners', $userData);  // it inserts and return id
                // insertion complete
                if($insert !=0){ 

                 $this->response(['status' => TRUE,
                                   'message' => 'Data Successfully Saved',
                                   'userId' => $insert], REST_Controller::HTTP_OK);
               
                }else{  
                   $this->response(['status' => FALSE,'message'=>"Some problems occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
                }
            }else{
                $this->response(['status' => FALSE,'message'=>"Data Fields Incomplete"], REST_Controller::HTTP_BAD_REQUEST);
            }
        
    }
        
}
