<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Drivers
 *
 * @author The_Lion
 */
require(APPPATH . '/libraries/REST_Controller.php');

class Drivers extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    /*     * **********DRIVER***************** */

    public function signup_post() {

        $userData = array(
            'userId' => $this->input->post('userId'),
            'idNumber' => $this->input->post('idNumber'),
            'idDocument' => $this->input->post('idDocument'),
            'licenceNumber' => $this->input->post('licenceNumber'),
            'licenceDocument' => $this->input->post('licenceDocument'),
            'drivingExperience' => $this->input->post('drivingExperience'),
            'criminalRecord' => $this->input->post('criminalRecord'),
            'clearenceCertificate' => $this->input->post('clearanceCertificate'),
            'facePhoto' => $this->input->post('facePhoto'),
            'pdp' => $this->input->post('pdp'),
        );

        if (!empty($userData['userId'])) {
            $this->db->trans_begin();
            $insert = insert('drivers', $userData);  // it inserts and return id
            if ($insert > 0) {
                $update = update_where('users', array('newUser' => false), array('id' => $userData['userId']));
                if ($update == true) {
                    //$this->sendEmail($this->input->post('email'));  // send cornfirmation email
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'userId' => $insert,
                        'message' => 'You have successfully registered, Please wait while your documents are being verified'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->db->trans_rollback();
                    $this->response(['status' => FALSE, 'message' => "Some problems occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response(['status' => FALSE, 'message' => "Some problems occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing or some documents are not uploaded"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function dashboard_post() {
        $driverId = $this->input->post('driverId');
        if ($driverId > 0) {
            $driver = getSingleJoin('drivers', 'users', 'userId', 'users.id', array());
            $requests = getWhere('requests', array('status' => Constants::STATUS_PENDING, 'vehicleType' => $driver['vehicleType']));
            if ($requests) {
                $this->response(['status' => TRUE,
                    'requests' => $requests,
                    'driver' => $driver,
                    'message' => 'request successfully '
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => TRUE,
                    'requests' => array(),
                    'message' => 'request successfully '
                        ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "unexpected error"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function rateDriver_post() {
        $userData = array(
            'driverId' => $this->input->post('driverId'),
            'rateVal' => $this->input->post('rateVal'),
            'userId' => $this->input->post('userId'),
            'tripId' => $this->input->post('tripId')
        );
        
        if ($userData['driverId'] > 0) {
            
            $insertId = insert('ratings', $userData);
            if ($insertId > 0) {
                $this->response(['status' => TRUE,
                    'message' => 'driver successfully rated '
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => FALSE, 'message' => "unable to rate driver"], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "unexpected error"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
