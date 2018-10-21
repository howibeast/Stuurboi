<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trips
 *
 * @author VetjurV4
 */
require(APPPATH . '/libraries/REST_Controller.php');

class Trips extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    public function driverTrips_post() {

        $userData = array(
            'userId' => $this->input->post('userId'),
        );
        if ($userData['userId'] > 0) {
            $this->db->trans_begin();
            $user = getSingleJoin('users', 'drivers', 'users.id', 'userId'); //getSingle('users', array('id' => $userData['userId']));
            if ($user) {
                $trips = getJoin('requests', 'trips', 'requests.id', 'requestId', array(), array('requests.driverId' => $user['id']));
                if ($trips) {
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'message' => 'Your query is succesful.',
                        'user' => $user,
                        'trips' => $trips
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'message' => 'You have no trips available',
                        'user' => $user,
                        'trips' => array()
                            ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->db->trans_rollback();
                $this->response(['status' => FALSE, 'message' => "unexpected problem occurred, please try to singout and signin again"], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "unexpected problem occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function userTrips_post() {

        $userData = array(
            'userId' => $this->input->post('userId'),
        );
        if ($userData['userId'] > 0) {
            $this->db->trans_begin();
            $user = getSingle('users', array('id' => $userData['userId']));
            if ($user) {
                $trips = $this->db->query("SELECT r.fromAddress, r.toAddress, ds.name, ds.surname, t.duration, t.fare, t.status, t.dateCreated FROM requests r INNER JOIN trips t ON r.id = t.requestId INNER JOIN drivers d ON r.driverId = d.id INNER JOIN users ds ON d.userId = ds.id WHERE r.userId = '" . $user['id'] . "' AND r.driverId > 0 ")->result();
                if ($trips) {
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'message' => 'Your query is succesful.',
                        'user' => $user,
                        'trips' => $trips
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'message' => 'You have no trips available',
                        'user' => $user,
                        'trips' => array()
                            ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->db->trans_rollback();
                $this->response(['status' => FALSE, 'message' => "unexpected problem occurred, please try to singout and signin again"], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "unexpected problem occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function tripSummary_post() {
        $tripId = $this->input->post('tripId');
        if ($tripId > 0) {
            $trip = $this->db->query("SELECT * FROM requests r INNER JOIN trips t ON r.id = t.requestId INNER JOIN drivers d ON r.driverId = d.id INNER JOIN users ds ON d.userId = ds.id WHERE t.id = $tripId")->row_array();
            if ($trip) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Your query is succesful.',
                    'trip' => $trip
                        ], REST_Controller::HTTP_OK);
            }else{
                $this->response(['status' => FALSE, 'message' => "the link is broken please try again."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "unexpected problem occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
