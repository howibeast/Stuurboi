<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment
 *
 * @author Ntuthuko punka
 */
class Payments extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    //put your code here
    public function paymentMethods() {

        if (isOnline()) {
            $userId = $this->session->userdata('userId');
            if ($userId > 0) {
                $res = sendToServer('payments', 'paymentMethods', Constants::HTTP_POST, array('userId' => $userId));
                if ($res->status == true) {
                    $this->data['paymentMethods'] = $res->paymentMethods;
                    $this->load->view('templates/header');
                    $this->load->view('users/templates/NavBar');
                    $this->load->view('users/templates/SideBar');
                    $this->load->view('payment/paymentMethod', $this->data);
                    $this->load->view('templates/footer');
                }
            }
        }
    }

    public function addCard() {

        if (isOnline()) {
            if ($this->input->post('addCard')) {
                $userdata = $this->prepareAddCard();
                if ($userdata != false) {
                    $userdata['userId'] = $this->session->userdata('userId');
                    $res = sendToServer('payments', 'addCard', Constants::HTTP_POST, $userdata);
                    if ($res->status == true) {
                        $this->session->set_flashdata(array('messageType' => 'success', 'message' => $res->message));
                        redirect('payments/paymentMethods');
                    } else {
                        $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                        redirect(current_url());
                    }
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'please fill in all the details in the form'));
                    redirect(current_url());
                }
            }

            $this->load->view('templates/header');
            $this->load->view('users/templates/NavBar');
            $this->load->view('users/templates/SideBar');
            $this->load->view('payment/addCard');
            $this->load->view('templates/footer');
        }
    }

    public function confirmPayment() {
        if (isOnline()) {
            $methodId = $this->input->post('methodId');
            $userId = $this->session->userdata('userId');
            if ($methodId > 0 && $userId > 0) {
                $res = sendToServer('payments', 'confirmPayment', Constants::HTTP_POST, array('methodId' => $methodId, 'userId' => $userId));
                if ($res->status == true) {
                    
                } else {
                    $this->session->set_flashdata(array('messageType' => 'danger', 'message' => $res->message));
                    redirect('payments/paymentMethods');
                }
            } else {
                $this->session->set_flashdata(array('messageType' => 'danger', 'message' => 'we do not accept this card type'));
                redirect('payments/paymentMethods');
            }
        }
    }

    private function prepareAddCard() {
        $this->form_validation->set_rules('cardNumber', 'Card Number', 'trim|required');
        $this->form_validation->set_rules('nameOnCard', 'Name on card', 'trim|required');
        $this->form_validation->set_rules('expiryMonth', 'Expiry Month', 'trim|required');
        $this->form_validation->set_rules('expiryYear', 'Expiry Year', 'trim|required');
        $this->form_validation->set_rules('cvv', 'CVV', 'trim|required');



        $data = false;
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'cardNumber' => $this->input->post("cardNumber"),
                'nameOnCard' => $this->input->post("nameOnCard"),
                'expiryMonth' => $this->input->post("expiryMonth"),
                'expiryYear' => $this->input->post("expiryYear"),
                'cvv' => $this->input->post("cvv")
            );
        }
        return $data;
    }

}
