<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tester
 *
 * @author Ntuthuko punka
 */
class Tester extends CI_Controller {

    //put your code here
    public function numberofusers() {
        $this->load->view('templates/header');
        $this->load->view('users/templates/NavBar');
        $this->load->view('users/templates/SideBar');
        $this->load->view('admin/reports/NumberOfUsers');
        // $this->load->view('users/signup');
        $this->load->view('templates/footer');
    }

    public function mostrequestedplaces() {
        $this->load->view('templates/header');
        $this->load->view('users/templates/NavBar');
        $this->load->view('users/templates/SideBar');
        $this->load->view('admin/reports/MostRequested');
        // $this->load->view('users/signup');
        $this->load->view('templates/footer');
    }

    public function ratingperdriver() {
        $this->load->view('templates/header');
        $this->load->view('users/templates/NavBar');
        $this->load->view('users/templates/SideBar');
        $this->load->view('admin/reports/RatingsPerDriver');
        // $this->load->view('users/signup');
        $this->load->view('templates/footer');
    }

    public function peakmonth() {
        $this->load->view('templates/header');
        $this->load->view('users/templates/NavBar');
        $this->load->view('users/templates/SideBar');
        $this->load->view('admin/reports/PeakMonthForAreas');
        // $this->load->view('users/signup');
        $this->load->view('templates/footer');
    }

}
