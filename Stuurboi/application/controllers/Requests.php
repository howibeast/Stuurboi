<?php

/**
 * Description of Request
 *
 * @author Ntuthuko punka
 */
class Requests extends CI_Controller {

    //put your code here
    public function request() {
        if ($this->input->post('btnRequest')) {
            $userId = $this->session->userdata('userId');
            if ($userId > 0) {
                $userData = $this->prepareRequest();
                if ($userData != FALSE) {
                    $res = sendToServer('requests', 'request', Constants::HTTP_POST, $userData);
                    if ($res->status === true) {
                        $this->data['request'] = $res->requestInfo;
                        $this->load->view('users/templates/navBar');
                        $this->load->view('users/templates/Sidebar');
                        $this->load->view('dashboard/RequestSummary', $this->data);
                        $this->load->view('templates/footer');
                    } else {
                        $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                        redirect('users/dashboard');
                    }
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'fill in the required fields' . 'fill in all the missing details' . validation_errors()));
                    redirect('users/dashboard');
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'You must be logged in to access that page'));
                redirect('users/signin');
            }
        }
    }

    public function acceptRequest() {
        $result = new stdClass();
        if (isOnline()) {
            $userData = array(
                'requestId' => $this->input->post('requestId'),
                'userId' => $this->session->userdata('userId')
            );

            if ($userData['requestId'] > 0 && $userData['userId'] > 0) {

                $res = sendToServer('requests', 'acceptRequest', Constants::HTTP_POST, $userData);
                if ($res->status == true) {
                    $result->tripId = $res->tripId;
                    $result->message = $res->message;
                    $result->status = $res->status;
                } else {
                    $result->tripId = $res->tripId;
                    $result->message = $res->message;
                }
            } else {
                $result->tripId = 'false';
                $result->message = 'unexpected error occured. please try to login and out again';
            }
        } else {
            $result->tripId = 'false';
            $result->message = 'You must be logged in to access that page';
        }

        echo json_encode($result);
    }

    public function cancelRequest() {
        if (isOnline()) {
            $userId = $this->session->userdata('userId');
            $requestId = $this->uri->segment(3, 0);
            if ($userId > 0 && $requestId > 0) {
                $res = sendToServer('requests', 'cancelRequest', Constants::HTTP_POST, array(
                    'userId' => $userId, 'requestId' => $requestId));
                if ($res->status == true) {
                    $this->session->set_flashdata(array('messageType' => 'success', 'message' => $res->message));
                    redirect('users/dashboard/' . $userId);
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                    redirect('users/dashboard/' . $userId);
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'unexpected error occured please try again'));
                redirect(current_url());
            }
        }
    }

    private function prepareRequest() {
        $this->form_validation->set_rules('toAddress', 'Destination Address', 'trim|required');
        $this->form_validation->set_rules('fromAddress', 'Pickup Location', 'trim|required');
        $this->form_validation->set_rules('receiverCell', 'Reciever\'s cellnumber', 'trim|required');
        $this->form_validation->set_rules('vehicleType', 'Type of transport', 'trim|required');
        $this->form_validation->set_rules('fragile', 'Type of transport', 'trim|required');


        $data = false;
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'userId' => $_SESSION['userId'],
                'toAddress' => $this->input->post('toAddress'),
                'fromAddress' => $this->input->post('fromAddress'),
                'receiverName' => $this->input->post('receiverName'),
                'receiverCell' => $this->input->post('receiverCell'),
                'vehicleType' => $this->input->post('vehicleType'),
                'estimationPrice' => $this->input->post('estimationPrice'),
                'fragile' => $this->input->post('fragile')
            );
        }

        return $data;
    }

    public function calculateDistance() {
        $pickup = urlencode($this->input->post('pickup'));
        $destination = urlencode($this->input->post('destination'));
        //$carType = urlencode($this->input->post('carType'));
        $myArray = explode('%2C+', $pickup);
        $myArray2 = explode('%2C+', $destination);
        //$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . str_replace(" ","+",str_replace(",","+",$pickup)) . "&destinations=" . str_replace(" ","+",str_replace(",","+",$destination)) . "&mode=bicycling&language=fr-FR&key=AIzaSyDbskBXR0Kfei73dGkmDbx2DVJx-_Pft54";
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=.''.$myArray2[1].'+'.$myArray2[2].&destinations=''.$myArray[1].'+'.$myArray[2].&mode=veja&language=fr-FR&key=AIzaSyDbskBXR0Kfei73dGkmDbx2DVJx-_Pft54";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        $status = $response_a->status;
        $res = '';
        if ($status == 'ZERO_RESULTS') {
            return FALSE;
        } else {
            $rows = $response_a->rows;
            foreach ($rows as $row) {
                foreach ($row->elements as $elem) {
                    $res = $elem->distance->value;
                }
            }
            echo $res;
        }
    }

}
