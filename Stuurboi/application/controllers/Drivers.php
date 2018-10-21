<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Drivers
 *
 * @author VetjurV4
 */
class Drivers extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function signup() {
        $userId = $this->uri->segment(3, 0);
        if ($this->input->post('signup')) {
            //get user input from the form
            $userData = $this->prepareSignup();
            //prepare array for docs uplaods
            $docNames = $this->prepareSignupDoc();
            //upload eaach doc from the array
            if ($userId > 0) {
                $docsUploadData = $this->uploadDriverDocs($docNames, $userId);
                //if upload is successful
                if (count($docsUploadData) > 0) {
                    if ($userData != false) {
                        //add upload data to userData array
                        $userData['idDocument'] = $docsUploadData['idDocument'];
                        $userData['licenceDocument'] = $docsUploadData['licenceDocument'];
                        $userData['clearanceCertificate'] = $docsUploadData['clearanceCertificate'];
                        $userData['facePhoto'] = $docsUploadData['facePhoto'];
                        $userData['pdp'] = $docsUploadData['pdp'];
                        $userData['userId'] = $userId;
                        //send to api
                        $res = sendToServer('drivers', 'signup', Constants::HTTP_POST, $userData);
                        if ($res->status === true) {
                            redirect('drivers/dashboard');
                        } else {
                            $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                            redirect(current_url());
                        }
                    } else {
                        $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'please fill in all the details in the form'));
                        redirect(current_url());
                    }
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'we had hard time uploading documents. please try again later'));
                    redirect(current_url());
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'unexpected error occured'));
                redirect(current_url());
            }
        }

        if ($userId > 0) {
            $this->data['userId'] = $userId;
        } else {
            $this->session->set_flashdata(array('success', 'message' => 'corrupted activation link '));
            redirect('users/signin');
        }
        $this->load->view('templates/header');
        $this->load->view('users/templates/navBar');
        $this->load->view('users/DriverSignup', $this->data);
        $this->load->view('templates/footer');
    }

    public function dashboard() {

        if (isOnline()) {

            $driverId = $this->session->userdata('userId');
            if ($driverId > 0) {
                $res = sendToServer('drivers', 'dashboard', Constants::HTTP_POST, array('driverId' => $driverId));
                if ($res->status === true) {
                    $this->data['requests'] = $res->requests;
                    $this->load->view('users/templates/navBar');
                    $this->load->view('users/templates/SideBar');
                    $this->load->view('dashboard/driverDashboard', $this->data);
                    $this->load->view('templates/footer');
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'you must be loggedin to access this page'));
                redirect('users/signout');
            }
        } else {
            $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'you must be loggedin to access this page'));
            redirect('users/signin');
        }
    }

    public function rateDriver() {
        $results = new stdClass();
        $userData = array(
            'userId' => $this->session->userdata('userId'),
            'driverId' => $this->input->post('driverId'),
            'rateVal' => $this->input->post('rateVal'),
            'tripId' => $this->input->post('tripId')
        );

        if ($userData['userId'] > 0 && $userData['driverId'] > 0) {
            $res = sendToServer('drivers', 'rateDriver', Constants::HTTP_POST, $userData);
             if ($res->status === true) {
                 $results = $res;
             }else{
                 $results = $res;
             }
        } else {
            $results->message = 'you need to login before accessing this page';
            $results->status = false;
        }
        echo json_encode($results);
    }

    private function prepareSignup() {
        $this->form_validation->set_rules('idNumber', 'ID Number', 'trim|required');
        $this->form_validation->set_rules('licenceNumber', 'Licence Number', 'trim|required');
        $this->form_validation->set_rules('drivingExperience', 'Driving Experience', 'trim|required');
        $this->form_validation->set_rules('criminalRecord', 'Criminal Record', 'trim|required');
        $this->form_validation->set_rules('licencePlate', 'Number plate', 'trim|required');
        $this->form_validation->set_rules('model', 'Transport model', 'trim|required');
        $this->form_validation->set_rules('transportType', 'Transport Type', 'trim|required');

        $data = false;
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'idNumber' => $this->input->post("idNumber"),
                'licenceNumber' => $this->input->post("licenceNumber"),
                'drivingExperience' => $this->input->post("drivingExperience"),
                'criminalRecord' => $this->input->post("criminalRecord"),
                'licencePlate' => $this->input->post("licencePlate"),
                'model' => $this->input->post("model"),
                'transportType' => $this->input->post("transportType")
            );
        }
        return $data;
    }

    private function prepareSignupDoc() {
        $this->form_validation->set_rules('idDocument', 'ID Document', 'trim|required');
        $this->form_validation->set_rules('licenceDocument', 'Licence Document', 'trim|required');
        $this->form_validation->set_rules('facePhoto', 'Face Photo');
        $this->form_validation->set_rules('clearanceCertificate', 'Clearence Certificate', 'trim|required');
        $this->form_validation->set_rules('pdp', 'PDP', 'trim|required');
        $data = array();
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'idDocument', 'licenceDocument', 'facePhoto', 'clearanceCertificate', 'pdp'
            );
        } else {
            echo 'prepare signup';
        }

        return $data;
    }

    private function uploadDriverDocs($filenames, $userId) {
        $uploads = array();
        $path = 'res/documents/driver/' . $userId;
        //if directory does not exist
        if (!is_dir($path)) {
            //create a new directory 
            $folder = mkdir($path, 0777, TRUE);

            if ($folder) {
                //for each filename in an array
                if (count($filenames) > 0) {
                    foreach ($filenames as $filename) {
                        //upload document and return upload data
                        $uploadData = $this->uploadFile($filename, $path);
                        if ($uploadData != null) {
                            $uploads[$filename] = $path . '/' . $uploadData['file_name'];
                        }
                    }
                }
                //return uploads array;
                return $uploads;
            }
        } else {
            if (count($filenames) > 0) {
                //for each filename in an array
                foreach ($filenames as $filename) {
                    //upload document and return upload data
                    $uploadData = $this->uploadFile($filename, $path);

                    if ($uploadData != null) {
                        $uploads[$filename] = $path . '/' . $uploadData['file_name'];
                    }
                }
            }
            //return uploads array
            return $uploads;
        }
        //if the whole process fails return empty array;
        return $uploads;
    }

    private function uploadFile($filename, $path, $allowedTypes = '*') {
        $config['upload_path'] = $path;
        $config['allowed_types'] = $allowedTypes;
        $this->upload->initialize($config);
        if ($this->upload->do_upload($filename)) {
            return $this->upload->data();
        } else {
            return null;
        }
    }

}
