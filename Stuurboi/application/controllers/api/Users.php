<?php

/*
 * The Users controller handles all the user login related works
 * 
 * @author The_Lion
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * User Management class created by CodexWorld
 */
require(APPPATH . '/libraries/REST_Controller.php');

class Users extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    /*     * *************************************************************************
     *     SIGNUP POSTS
     * ************************************************************************ */

    public function signup_post() {

        $userData = array(
            'name' => strip_tags($this->input->post('name')),
            'surname' => strip_tags($this->input->post('surname')),
            'gender' => $this->input->post('gender'),
            'email' => strip_tags($this->input->post('email')),
            'cellNumber' => strip_tags($this->input->post('cellNumber')),
            'password' => md5($this->input->post('password')),
            'userType' => $this->input->post('userType'),
            'verificationCode' => substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 5),
            'avatar' => $this->input->post('avatar')
        );

        if (!empty($userData['name']) && !empty($userData['surname']) && !empty($userData['gender']) && !empty($userData['email']) &&
                !empty($userData['cellNumber']) && !empty($userData['password'])) {

            $this->db->trans_begin();
            $insert = insert('users', $userData);  // it inserts and return to client
            // insertion complete
            if ($insert > 0) {
                $user = getSingle('users', array('id'=> $insert));
                //if ($user) {
                    $headers = 'donotreply@stuurboi.com' . "\r\n" .
                            'Reply-To: donotreply@stuurboi.com' . "\r\n";
                    $message = 'Hi ' . $userData['name'] . ' welcome to Struuboi please click the link to activate your account' . base_url('users/activate_account/').$insert.'/'.$user['verificationCode'];

                    mail($userData['email'], 'activate ziwavah account', $message);
                    $this->db->trans_commit();
                    $this->response(['status' => TRUE,
                        'userId' => $insert,
                        'message' => 'Successfully Registered, Please verify your email.'
                            ], REST_Controller::HTTP_OK);
                //}else{
                 //   $this->db->trans_rollback();
                ///    $this->response(['status' => FALSE, 'message' => "invalid email address."], REST_Controller::HTTP_BAD_REQUEST);
               // }
            } else {
                $this->db->trans_rollback();
                $this->response(['status' => FALSE, 'message' => "Some problems occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->db->trans_rollback();
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function dashboard_post() {
        $userData = array('id' => $this->input->post('userId'));
        if (!empty($userData['id'])) {
            $user = getSingle('users', $userData);
            if ($user) {
                $this->response(['status' => TRUE,
                    'user' => $user,
                    'message' => 'Query successfully'
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => FALSE, 'message' => "user with that id does not exist."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "fill in the missing fields"], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*     * ********ACTIVATE ACCOUNT***** */

    public function activateAccount_post() {
        $userData = array('id' => $this->input->post('id'),
            'verificationCode' => $this->input->post('verificationCode')
        );

        if (!empty($userData['id']) && !empty($userData['verificationCode'])) {

            $data = update_where('users', array('status' => 'active'), $userData);

            if ($data != false) {

                $this->response(['status' => TRUE,
                    'message' => "user account activated successfully"], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => FALSE,
                    'message' => "User not found , or Some problems occurred, please try again "], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*     * **************************************************************************
     * SIGNIN POSTS
     * ************************************************************************ */

    public function signin_post() {
        $userData = array(
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
        );

        if (!empty($userData)) {
            $user = getSingle('users', $userData);
            if ($user) {
                if ($user['status'] === Constants::STATUS_ACTIVE) {
                    $this->response(['status' => TRUE,
                        'user' => $user,
                        'message' => 'Successfully loggedin'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $this->response(['status' => FALSE, 'message' => "you need to verify your email before you login. Go to your email and click the link"], REST_Controller::HTTP_ACCEPTED);
                }
            } else {
                $this->response(['status' => FALSE, 'message' => "wrong username or password."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*     * ***************************
     *   VIEW PROFILE POST
     * ************************* */

    public function profile_post() {

        $data = array('email' => strip_tags($this->input->post('email')),
            'password' => md5($this->input->post('password')));


        $data = $this->getWhere('users', $data); // get their data from database 

        if ($data != 0) {

            $this->response(['status' => TRUE, 'message' => $data], REST_Controller::HTTP_OK);
        } else {
            $this->response("Server failure", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*     * **************************************
     * EDIT PROFILE
     * ************************************* */

    public function editProfile_post() {
        $userId = $this->input->post('id');

        if ($userId != 0) {

            $data = getWhere('users', $userId); // get their data from database 

            if ($data != 0) {
                $this->response(['status' => TRUE, $data], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*     * **********************************
     *  DEACTIVATE ACCOUNT 
     * ********************************** */

    public function deactivateAccount_put() {

        $Id = $this->input->post('id');
        $status['status'] = 'inactive';

        if ($Id != 0) {

            $data = update_where('users', $status, $Id); // get their data from database 

            if ($data != 0) {
                $this->response(['status' => TRUE,
                    'message' => 'Account deactivated successfully.',
                    'Id' => $data['id']], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*     * **********************************
     *  ACTIVATE ACCOUNT 
     * ********************************** */

    public function activateAccount_put() {

        $status['status'] = 'inactive';
        $userData = array('id' => $this->input->post('id'),
            'verificationCode' => $this->input->post('verificationCode'));

        if ($Id != 0) {

            $data = update_where('users', $status, $userData); // get their data from database 

            if ($data != false) {
                $this->response(['status' => TRUE,
                    'message' => 'Account activated successfully.'], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /*     * *****************
     * DELETE ACCOUNT
     * **************** */

    public function deleteAccount_delete() {

        $Id = $this->input->post('id');

        if ($Id != 0) {

            $data = deleteRow('users', $Id); // get their data from database 

            if ($data != 0) {
                $this->response(['status' => TRUE,
                    'message' => 'Account deleted successfully.',
                    'Id' => $data['id']], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function testAccount_post() {
        $Id = $this->input->post('id');

        if ($Id != 0) {

            $data = getRow('users', $Id); // get their data from database 

            if ($data != 0) {
                $this->response(['status' => TRUE,
                    'message' => 'Welcome', $data], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "id missing."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}
