<?php

class Users extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user');
    }

    public function home() {
        $this->load->view('users/templates/navBar');
        $this->load->view('templates/homepage');
    }

    public function signup() {
        if (!isOnline()) {
            if ($this->input->server('REQUEST_METHOD') == 'POST') {// when user clicks button
                $userData = $this->prepareSignup();
                if ($userData != FALSE) {

                    $res = sendToServer('users', 'signup', Constants::HTTP_POST, $userData);  // sends to api and return results

                    if ($res->status === true) {  //
                        $this->data['message'] = $res->message;
                    } else {
                        $this->data['message'] = $res->message;
                    }
                    $this->load->view('templates/header');
                    $this->load->view('users/templates/navBar');
                    $this->load->view('users/success', $this->data);
                    $this->load->view('templates/footer');
                    
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'fill in all the missing details'));
                    redirect(current_url());
                }
            } else {
                $this->load->view('users/templates/navBar');
                $this->load->view('users/signup');
                $this->load->view('templates/footer');
            }
        } else {
            redirect('users/dashboard');
        }
    }

    public function trip() {

        if (isOnline()) {

            $driverId = $this->session->userdata('userId');
            if ($driverId > 0) {
                $res = sendToServer('drivers', 'dashboard', Constants::HTTP_POST, array('driverId' => $driverId));
                if ($res->status === true) {
                    $this->data['trips'] = $res->trips;
                    $this->load->view('users/templates/navBar');
                    $this->load->view('users/templates/SideBar', $this->data);
                    $this->load->view('dashboard/UserTrips', $this->data);
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

    public function dashboard() {

        if (isOnline()) {
            $userId = $this->session->userdata('userId');
            $res = sendToServer('users', 'dashboard', Constants::HTTP_POST, array('userId' => $userId));
            if ($res->status === true) {
                $user = $res->user;
                if ($user->userType == Constants::USER_CLIENT) {
                    $this->data['user'] = $res->user;
                    $this->load->view('users/templates/navBar');
                    $this->load->view('users/templates/SideBar');
                    $this->load->view('dashboard/userDashboard', $this->data);
                    $this->load->view('templates/footer');
                } else if ($user->userType == Constants::USER_DRIVER) {
                    redirect('drivers/dashboard');
                } else if ($user->userType == Constants::USER_OWNER) {
                    redirect('owners/dashboard');
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'unknown user type'));
                    $this->signout();
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                redirect('users/signin');
            }
        } else {
            $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'you must be loggedin to access this page'));
            redirect('users/signin');
        }
    }

    public function activate_account() {
        //takes an id and verificationCode
        $userId = $this->uri->segment(3, 0);
        $verificationCode = $this->uri->segment(4, 0);

        if ($userId > 0 && !empty($verificationCode)) {

            $res = sendToServer('users', 'activateAccount', Constants::HTTP_POST, array('id' => $userId,
                'verificationCode' => $verificationCode)
            );

            if ($res->status === true) {
                $this->session->set_flashdata(array('messageType' => 'success', 'message' => $res->message));
                redirect('users/signin');
            } else {
                $this->data['message'] = $res->message;
                $this->load->view('templates/header');
                $this->load->view('users/templates/navBar');
                $this->load->view('users/success', $this->data);
                $this->load->view('templates/footer');
            }
        } else {
            $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'your activation link is corrupt, please request for a new code'));
            redirect('users/signin');
        }
    }

    public function signin() {
        if (!isOnline()) {
            //if user press signin button
            if ($this->input->post('singin')) {

                //assign data from the form to userData array
                $userData = $this->prepareSignin();

                //if data is successfully loaded from html form
                if ($userData != FALSE) {
                    //send data to api and assign results to $res
                    $res = sendToServer('users', 'signin', Constants::HTTP_POST, $userData);
                    //if query is successful
                    if ($res->status === true) {
                        //assign user from db to a user variable
                        $user = $res->user; // table in db
                        $this->session->set_userdata(array('userId' => $user->id, 'userType' => $user->userType, 'name' => $user->name, 'surname' => $user->surname));
                        if ($user->newUser == true && $user->userType != Constants::USER_CLIENT) {  // if its a new user
                            switch ($user->userType) {  // change user type
                                case Constants::USER_DRIVER:
                                    redirect('drivers/signup/' . $user->id);
                                    break;
                                case Constants::USER_OWNER:
                                    redirect('owners/signup/' . $user->id);
                                    break;
                                case Constants::USER_ADMIN:
                                    redirect('tester/mostrequestedplaces/' . $user->id);
                                    break;
                                default :
                                    $this->session->set_flashdata(array('messageType' => 'success', 'message' => 'unknown user type'));
                                    redirect('users/dashboard');
                                    break;
                            }
                        } else {
                            switch ($user->userType) {

                                case Constants::USER_CLIENT:
                                    redirect('users/dashboard/' . $user->id);
                                    break;
                                case Constants::USER_DRIVER:
                                    redirect('drivers/dashboard/' . $user->id);
                                    break;
                                case Constants::USER_ADMIN:
                                    redirect('tester/mostrequestedplaces/' . $user->id);
                                    break;
                                default :
                                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'unknown user type'));
                                    redirect(current_url());
                                    break;
                            }
                        }
                    } else {
                        $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                        redirect(current_url());
                    }
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'please fill all the fields in the form'));
                    redirect(current_url());
                }
            } else {
                $this->load->view('templates/header');
                $this->load->view('users/templates/navBar');
                $this->load->view('users/signin');
                $this->load->view('templates/footer');
            }
        } else {
            $this->signout();
        }
    }

    public function signout() {
        if (isOnline()) {
            unset($_SESSION['userId'], $_SESSION['userType'], $_SESSION['name']);
            redirect('users/signin');
        } else {
            redirect('users/signin');
        }
    }

    private function prepareSignin() {
        $this->form_validation->set_rules('email', 'Email must be unique, ', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $data = false;
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'email' => $this->input->post("email"),
                'password' => $this->input->post("password")
            );
        }
        return $data;
    }

    private function prepareSignup() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('surname', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        $this->form_validation->set_rules('email', 'Email must be unique, ', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('cellNumber', 'Cell Number', 'trim|required|is_unique[users.cellNumber]');
        $this->form_validation->set_rules('userType', 'User Type', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('passVerif', 'Password Confirmation', 'trim|required|matches[password]');


        $data = false;

        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'name' => $this->input->post("name"),
                'surname' => $this->input->post("surname"),
                'gender' => $this->input->post("gender"),
                'email' => $this->input->post("email"),
                'cellNumber' => $this->input->post("cellNumber"),
                'userType' => $this->input->post("userType"),
                'password' => $this->input->post("password"),
                'avatar' => 'res/images/profilePics/default.jpg'
            );
        }
        return $data;
    }
	
	public function request() {

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            
            $userData = array(
                'fromAddress' => $this->input->post("fromAddress"),
                'toAddress' => $this->input->post("toAddress"),
                'receiverCell' => $this->input->post("receiverCell"),
                'vehicleType' => $this->input->post("vehicleType")
            
            );
            
            if(insert('requests', $userData) > 0){
                echo 'Request Created';
            }else{
                echo 'Request Failed';   
            }
        }
    }
     
      public function confirm(){
        
         if ($this->input->server('REQUEST_METHOD') == 'POST'){

            $userData = array(
                'driverId' => $_POST['driverId'],
                'pickupLocation' => $_POST['pickupLocation'],
                'destinationLocation' => $_POST['destinationLocation'],
                'confirmPickup' => $_POST['confirmPickup']
            );
        
     
            $insert = insert('confimations', $userData);  // it inserts and return to client
            
            // insertion complete
            if ($insert > 0) {
                $user = getSingle('confimations', array('userId'=> $insert));
                if ($user) {
                  
                     echo $user['userId'];
                }else{
                    echo 'an error has occured';
                }
               
              }else{
                   echo 'returns nothing';
              }
              
         }   
      
    }
    
    public function update(){
        
         if ($this->input->server('REQUEST_METHOD') == 'POST'){

             $data = array('confirmDelivery'=>$_POST['confirmDelivery']);
             
              $this->db->where('userId', $_POST['userId']);
              $update=$this->db->update('confimations', $data);
            
               if($update){
                  echo "successfully updated";
              }else
              {
                  echo "error occured";
              }
         }
           
    }
    
    public function requestStatus(){
        
         if ($this->input->server('REQUEST_METHOD') == 'POST'){

             $data = array('driverId'=>$_POST['driverId'],
                           'status'=>$_POST['status']);
             
              $this->db->where('id', $_POST['id']);
              $update=$this->db->update('requests', $data);
            
               if($update){
                  echo "successfully updated";
              }else
              {
                  echo "error occured";
              }
         }
           
    }
    
    public function rateUser(){
        
         if ($this->input->server('REQUEST_METHOD') == 'POST'){

             $data = array('ratings'=>$_POST['ratings']);
             
              $this->db->where('id', $_POST['id']);
              $update=$this->db->update('requests', $data);
            
               if($update){
                  echo "Thanks for rating";
              }else
              {
                  echo "error occured while rating user";
              }
         }
           
    }
    
     
     public function getRequests() {

        if ($this->input->server('REQUEST_METHOD') == 'GET') {

                $data['status']='pending';
                $this->db->select("id, fromAddress, toAddress, receiverCell, receiverName, estimationPrice");
                $this->db->where($data);
                $this->db->from('requests');  
                $query = $this->db->get();
                
                 echo json_encode($query->result_array());
        }      
     }
        
        public function trips(){
        
         if ($this->input->server('REQUEST_METHOD') == 'POST'){

            $userData = array(
                'driverId' => $this->input->post("driverId"),
                'requestId' => $_POST['requestId'],
                'duration' => $_POST['duration'],
                'pickupLocation'=> $_POST['pickupLocation'],
                'destinationLocation'=> $_POST['destinationLocation'],
                'mileage' => $_POST['mileage'],
                'fare' => $_POST['fare'],
                'payment'=>'paid',
                'status' => $_POST['status']
            );
        
     
            $insert = insert('trips', $userData);  // it inserts and return to client
            
            // insertion complete
            if ($insert > 0) {
                     echo "successfully updated";
                }else{
                    echo 'an error has occured';
                }
               
         }   
      
    }
        
        public function getTrips() {

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data = array(
                'driverId' => $this->input->post("driverId"),
                'status' => "completed"
            );
            
                $this->db->select("pickupLocation, destinationLocation, fare, mileage, dateCreated");
                $this->db->where($data);
                $this->db->from('trips');  
                $query = $this->db->get();
                
                 echo json_encode($query->result_array());
        }      
 }


}
