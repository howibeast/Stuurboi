<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paypal
 *
 * @author The_Lion
 */
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Paypal extends CI_Controller{
    
     public function sendIpn() {
        if ($this->input->post('cardSubmit')) {
            $userId = $this->session->userdata('userId');
            if ($userId > 0) {
                $userData = $this->prepareCard();
                if ($userData != FALSE) {
                    $res = sendToServer('payments', 'ipn', Constants::HTTP_POST, $userData);
                    if ($res->status === true) {
                        $this->data['ipn'] = $res->ipnInfo;
                        $this->load->view('users/templates/navBar');
                        $this->load->view('payment/paypal');
                        $this->load->view('templates/footer');
                    } else {
                        $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                        redirect('users/dashboard');
                    }
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'fill in the required fields'));
                    redirect('users/dashboard');
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'You must be logged in to access that page'));
                redirect('users/signin');
            }
        }
    }

    private function prepareCard() {
        $this->form_validation->set_rules('cardNumber', 'Card Number', 'trim|required');
        $this->form_validation->set_rules('expiryMonth', 'Expiry Month', 'trim|required');
        $this->form_validation->set_rules('expiryYear', 'Expiry Year', 'trim|required');
        $this->form_validation->set_rules('cvv', 'CVV', 'trim|required');
        $this->form_validation->set_rules('nameOnCard', 'Name of the card', 'trim|required');
        
        $data = false;
        $month=$this->input->post('expiryMonth');
        $year=$this->input->post('expiryYear');
        $date=$month+"|"+$year;
        
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'userId' => $_SESSION['userId'], 
                'cardNumber'=>$this->input->post('txtId'),
                'expiryMonth'=>$this->input->post('expiryMonth'),
                'expiryYear'=>$this->input->post('expiryYear'),
                'cvv'=>$this->input->post('cvv'),
                'nameOnCard'=>$this->input->post('nameOnCard')
            );
        }

        return $data;
    }

     
    
}
