<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payments
 *
 * @author The_Lion
 */
require(APPPATH . '/libraries/REST_Controller.php');

class Payments extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    public function paymentMethods_post() {
        $userId = $this->input->post('userId');
        if ($userId > 0) {
            $paymentMethods = getWhere('creditCards', array('userId' => $userId));
            if ($paymentMethods) {
                $this->response(['status' => TRUE,
                    'paymentMethods' => $paymentMethods,
                    'message' => 'Query successful'
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => TRUE,
                    'paymentMethods' => array(),
                    'message' => 'request successfully '
                        ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "invalid userId"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function addCard_post() {
        $userData = array(
            'userId' => $this->input->post('userId'),
            'nameOnCard' => $this->input->post('nameOnCard'),
            'cardNumber' => $this->input->post('cardNumber'),
            'expiryMonth' => $this->input->post('expiryMonth'),
            'expiryYear' => $this->input->post('expiryYear'),
            'cvv' => $this->input->post('cvv')
        );

        if (!empty($userData['userId']) && !empty($userData['nameOnCard']) && !empty($userData['cardNumber'])) {
            $insertId = insert('creditCards', $userData);
            if ($insertId > 0) {
                $this->response(['status' => TRUE,
                    'message' => 'card successfully added'
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => FALSE, 'message' => "couldn't save your card, pleas try again"], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing please fill all the missing fields"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function confirmPayment() {
        $userData = array(
            'userId' => $this->input->post('userId'),
            'id' => $this->input->post('methodId')
        );

        if (!empty($userData['userId']) && !empty($userData['id'])) {
            $this->db->trans_begin();
            $paymentMethod = getWhere('creditCards', $userData);
            if ($paymentMethod) {
                $trips = getWhere('trips', array('fate' < 0));
            } else {
                $this->db->trans_rollback();
                $this->response(['status' => FALSE, 'message' => "This card is currently not registerd on our system"], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->db->trans_rollback();
            $this->response(['status' => FALSE, 'message' => "Some fields are missing please fill all the missing fields"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
