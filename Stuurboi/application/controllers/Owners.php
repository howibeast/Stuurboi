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
class Owners extends CI_Controller {
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('user');
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
                        $userData['clearenceDocument'] = $docsUploadData['clearenceDocument'];
                        $userData['facePhoto'] = $docsUploadData['facePhoto'];
                        $userData['userId'] = $userId;
                        //send to api
                        $res = sendToServer('owners', 'signup', Constants::HTTP_POST, $userData);
                        if ($res->status === true) {
                            redirect('owners/dashboard');
                        } else {
                            $this->session->set_flashdata(array('messageType'=>'danger', 'message' => $res->message));
                            redirect(current_url());
                        }
                    } else {
                        $this->session->set_flashdata(array('messageType'=>'danger', 'message' => 'please fill in all the details in the form'));
                        redirect(current_url());
                    }
                } else {
                    $this->session->set_flashdata(array('messageType'=>'danger', 'message' => 'we had hard time uploading documents. please try again later'));
                    redirect(current_url());
                }
            } else {
                $this->session->set_flashdata(array('messageType'=>'danger', 'message' => 'unexpected error occured'));
                redirect(current_url());
            }
        }

        if ($userId > 0) {
            $this->data['userId'] = $userId;
        } else {
            $this->session->set_flashdata(array('success', 'message' => 'corrupted activation link '));
            redirect(current_url());
        }
        $this->load->view('templates/header');
        $this->load->view('users/templates/navBar');
        $this->load->view('users/ownerSignup', $this->data);
        $this->load->view('templates/footer');
    }
    
    private function prepareSignup() {
        $this->form_validation->set_rules('idNumber', 'ID Number', 'trim|required');
        $this->form_validation->set_rules('licensePlate', 'Licence Plate', 'trim|required');
        $this->form_validation->set_rules('model', 'Car Model', 'trim|required');
        $this->form_validation->set_rules('vehicleType', 'Vehicle Type', 'trim|required');
        $this->form_validation->set_rules('criminalRecord', 'Criminal Record', 'trim|required');
        
        

        $data = false;
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'idNumber' => $this->input->post("idNumber"),
                'licensePlate' => $this->input->post("licensePlate"),
                'model' => $this->input->post("model"),
                'vehicleType' => $this->input->post("vehicleType"),
                'criminalRecord' => $this->input->post("criminalRecord")
            );
    }
        return $data;
}

    private function prepareSignupDoc() {
        $this->form_validation->set_rules('idDocument', 'ID Document');
        $this->form_validation->set_rules('clearenceDocument', 'Clearence Document');
       $this->form_validation->set_rules('facePhoto', 'Face Photo');
        
        $data = array();
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'idDocument', 'clearenceDocument', 'facePhoto'
            );
        }
        return $data;
    }

    private function uploadDriverDocs($filenames, $userId) {
        $uploads = array();
        $path = 'res/documents/owners/' . $userId;
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
                            $uploads[$filename] = $path.'/'.$uploadData['file_name'];
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
                        $uploads[$filename] = $path.'/'.$uploadData['file_name'];
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
