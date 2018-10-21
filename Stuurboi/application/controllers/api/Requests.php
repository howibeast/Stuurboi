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
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * User Management class created by CodexWorld
 */
require(APPPATH . '/libraries/REST_Controller.php');

class Requests extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    public function request_post() {

        $userData = array(
            'userId' => $this->input->post('userId'),
            'toAddress' => $this->input->post('toAddress'),
            'fromAddress' => $this->input->post('fromAddress'),
            'receiverName' => $this->input->post('receiverName'),
            'receiverCell' => $this->input->post('receiverCell'),
            'vehicleType' => $this->input->post('vehicleType'),
            'estimationPrice' => $this->input->post('estimationPrice'),
            'fragile' => $this->input->post('fragile'),
        );

        if (!empty($userData['userId']) && !empty($userData['toAddress']) && !empty($userData['fromAddress']) && !empty($userData['receiverCell']) &&
                !empty($userData['vehicleType'])) {
            $this->db->trans_begin();
            $insert = insert('requests', $userData);  // it inserts and return to client
            // insertion complete
            if ($insert > 0) {

                $requestInfo = getSingle('requests', array('id' => $insert));
                if ($requestInfo) {
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'requestInfo' => $requestInfo,
                        'message' => 'Successfully Requested'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->db->trans_rollback();
                    $this->response(['status' => FALSE, 'message' => "Couldn\'t get info for the request ."], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->db->trans_rollback();
                $this->response(['status' => FALSE, 'message' => "Some problems occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function acceptRequest_post() {
        $userData = array(
            'userId' => $this->input->post('userId'),
            'requestId' => $this->input->post('requestId')
        );

        if ($userData['userId'] > 0 && $userData['requestId'] > 0) {
            $driver = $user = getSingleJoin('users', 'drivers', 'users.id', 'userId', array(), array('users.id' => $userData['userId']));
            if ($driver) {
                $accept = update_where('requests', array('status' => Constants::STATUS_ACCEPTED, 'driverId' => $driver['id']), array('id' => $userData['requestId']));
                if ($accept) {
                    $request = getSingle('requests', array('id' => $userData['requestId']));
                    if ($request) {
                        $duration = time() - strtotime($request['dateCreated']);

                        $tripId = insert('trips', array('requestId' => $userData['requestId'], 'duration' => $duration, 'mileage' => '0', 'fare' => $request['estimationPrice'], 'status' => Constants::STATUS_COMPLETED));
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Request Accepted.',
                            'tripId' => $tripId
                                ], REST_Controller::HTTP_OK);
                    }
                } else {
                    $this->response(['status' => FALSE, 'message' => "Someone must have already accepted the request please try again"], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response(['status' => FALSE, 'message' => "we have detected suspicious activity with your account. Please try to signout and in again."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function cancelRequest_post() {
        $where = array(
            'id' => $this->input->post('requestId'),
            'userId' => $this->input->post('userId')
        );


        if ($where['id'] > 0 && $where['userId'] > 0) {

            $this->db->trans_begin();

            $request = getSingle('requests', array('userId' => $where['userId'], 'id' => $where['id']));

            if ($request) {
                if (strtotime($request['dateCreated']) < (time() - 60)) {

                    $tripData = array(
                        'requestId' => $where['id'],
                        'duration' => '0.00',
                        'mileage' => '0.00',
                        'fare' => '25.00',
                        'status' => Constants::STATUS_CANCELLED
                    );
                    $inserId = insert('trips', $tripData);

                    if ($inserId < 0) {
                        $this->db->trans_rollback();
                    }
                }
                //change request status to cancelled
                $update = update_where('requests', array('status' => Constants::STATUS_CANCELLED), $where);

                if ($update) {
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'message' => 'You have cancelled the request.'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->db->trans_rollback();
                    $this->response(['status' => FALSE, 'message' => "we couldn't cancel your request please try again"], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->db->trans_rollback();
                $this->response(['status' => FALSE, 'message' => "request does not exits"], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->db->trans_rollback();
            $this->response(['status' => FALSE, 'message' => "unexpected error occured please try again"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
