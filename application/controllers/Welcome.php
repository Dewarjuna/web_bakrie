<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('pegawai_model'); // Load the model
    }

    public function index() {
        $this->load->view('index');
    }

    public function tables() {
        // Get employee list
        $data['pegawai_list'] = $this->pegawai_model->get_all();
        
        // Get statistics
        $data['counts'] = $this->pegawai_model->count_pegawai_stats();
        
        // Load view with both data sets
        $this->load->view('tables', $data);
    }


}