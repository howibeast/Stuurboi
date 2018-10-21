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
class Trips extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function driverTrips() {
        if (isOnline()) {
            $userId = $this->session->userdata('userId');
            if ($userId > 0) {
                $res = sendToServer('trips', 'driverTrips', Constants::HTTP_POST, array('userId' => $userId));
                if ($res->status == true) {
                    $this->data['trips'] = $res->trips;
                    $this->load->view('templates/header');
                    $this->load->view('users/templates/navBar');
                    $this->load->view('users/templates/sideBar');
                    $this->load->view('trips/driverTrips', $this->data);
                    $this->load->view('templates/footer');
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                    redirect('drivers/dashboard');
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'unexpected error occured'));
                redirect('users/signin');
            }
        } else {
            $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'You should firstly login to acces the page'));
            redirect('users/signin');
        }
    }

    public function userTrips() {
        if (isOnline()) {
            $userId = $this->session->userdata('userId');

            if ($userId > 0) {

                $res = sendToServer('trips', 'userTrips', Constants::HTTP_POST, array('userId' => $userId));
                if ($res->status == true) {
                    $this->data['trips'] = $res->trips;
                    $this->load->view('templates/header');
                    $this->load->view('users/templates/navBar');
                    $this->load->view('users/templates/sideBar');
                    $this->load->view('trips/userTrips', $this->data);
                    $this->load->view('templates/footer');
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                    redirect('users/dashboard');
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'unexpected error occured'));
                redirect('users/signin');
            }
        } else {
            $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'You should firstly login to acces the page'));
            redirect('users/signin');
        }
    }

    public function tripSummary() {
        $result = new stdClass();
        if (isOnline()) {

            $tripId = $this->input->post('tripId');
            if ($tripId > 0) {
                $res = sendToServer('trips', 'tripSummary', Constants::HTTP_POST, array('tripId' => $tripId));
               
                if ($res->status == true) {
                    $result->status = $res->status;
                    $result->message = $res->message;
                    $result->tripInfo = $res->trip;
                } else {
                    $result->status = $res->status;
                    $result->message = $res->message;
                }
            } else {
                $result->status = false;
                $result->message = 'unexpected error occured. invalid trip id';
            }
        } else {
            $result->status = false;
            $result->message = 'You need to login to access that page';
        }
        echo json_encode($result);
    }

}
