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
class Paypal extends REST_Controller {
    //put your code here
    
         function  __construct(){
        parent::__construct();
        $this->load->library('paypal_lib');
     }
     
     
     public function ipn_post(){  
        $userData=array(
            'RequestId'=>$this->input->post('RequestId'),
            'userId'=>$this->input->post('userId'), 
            'txnId'=>$this->input->post('txtId'),
            'currencyCode'=>$this->input->post('currencyCode'),
            'payerEmail'=>$this->input->post('payerEmail'),
            'paymentStatus'=>$this->input->post('paymentStatus'),
            'cardNumber'=>$this->input->post('cardNumber'),
            'expiryMonth'=>$this->input->post('expiryMonth'),
            'expiryYear'=>$this->input->post('expiryYear'),
            'cvv'=>$this->input->post('cvv'),
            'nameOnCard'=>$this->input->post('nameOnCard')
        );
    // send to paypal
        $paypalURL = $this->paypal_lib->paypal_url;
        $result     = $this->paypal_lib->curlPost($paypalURL,$userData);
        // Check whether the payment is verified
        //if(preg_match("/VERIFIED/i",$result)){
            // Insert the transaction data into the database  
        //}
         if (!empty($userData['userId']) && !empty($userData['RequestId']) && !empty($userData['txnId']) && 
                 !empty($userData['currencyCode']) &&!empty($userData['payerEmail'])&&!empty($userData['paymentStatus'])) {

            $insert = insert('payments', $userData);  // it inserts and return to client
           
            // insertion complete
            if ($insert > 0) {

                //$this->sendEmail($this->input->post('email'));  // send cornfirmation email

                $this->response(['status' => TRUE,
                    'userId' => $insert,
                    'message' => 'Successfully Recorded'
                        ], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => FALSE, 'message' => "Some problems occurred, please try again."], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['status' => FALSE, 'message' => "Some fields are missing."], REST_Controller::HTTP_BAD_REQUEST);
        }

    }
}


/** Paypal return transaction details array
        /**
     * Display transaction data on payment success.
      
    function success(){
        // Get the transaction data
        $paypalInfo = $this->input->get();
        
        $data['item_number'] = $paypalInfo['item_number']; 
        $data['txn_id'] = $paypalInfo["tx"];
        $data['payment_amt'] = $paypalInfo["amt"];
        $data['currency_code'] = $paypalInfo["cc"];
        $data['status'] = $paypalInfo["st"];
        
        // Pass the transaction data to view
        $this->load->view('paypal/success', $data);
    }
     /**
      * Display error message on payment falied or cancel.
      
     function cancel(){
        // Load payment failed view
        $this->load->view('paypal/cancel');
     }
     
}

 */
        