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

    public function inputpegawaiform() {
        $this->load->view('inputpegawaiform');
    }

    public function tables() {
        // Get employee list
        $data['pegawai_list'] = $this->pegawai_model->get_all();
        
        // Get statistics
        $data['counts'] = $this->pegawai_model->count_pegawai_stats();
        
        // Load view with both data sets
        $this->load->view('tables', $data);
    }
    public function submit_pegawai() {
        // Load form validation library
        $this->load->library('form_validation');
    
        // Set validation rules
        $this->form_validation->set_rules('nip', 'NIP', 'required');
        $this->form_validation->set_rules('nama', 'Nama Pegawai', 'required');
        // Add other validation rules as needed
    
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, reload the form with errors
            $this->load->view('inputpegawaiform');
        } else {
            // Validation passed, prepare data for insertion
            $data = array(
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'jabatan' => $this->input->post('jabatan'),
                'tglaktif_jabatan' => $this->input->post('tglaktif_jabatan'),
                'tglmasuk_jabatan' => $this->input->post('tglmasuk_jabatan'),
                'status' => $this->input->post('status'),
                'aktif' => $this->input->post('aktif')
            );
    
            // Insert data into the database
            $insert_id = $this->pegawai_model->create($data);
    
            if ($insert_id) {
                // Redirect to a success page or display a success message
                redirect('welcome/tables');
            } else {
                // Handle the error
                $this->load->view('inputpegawaiform', ['error' => 'Failed to insert data']);
            }
        }
    }
    public function delete($nip) {
        $nip = urldecode($nip); // Decode the URL-encoded string
        // Call the delete method from the model
        $result = $this->pegawai_model->delete($nip);
    
        if ($result) {
            // Redirect to the tables view with a success message
            $this->session->set_flashdata('message', 'Berhasil Menghapus Data Pegawai');
        } else {
            // Redirect to the tables view with an error message
            $this->session->set_flashdata('message', 'Gagal Menghapus Data Pegawai');
        }
    
        redirect('welcome/tables');
    }

    public function edit_pegawai($id) {
        $data['pegawai'] = $this->pegawai_model->get_by_id($id);
        $this->load->view('edit_pegawai', $data);
    }
    
    public function update() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nip', 'NIP', 'required');
        $this->form_validation->set_rules('nama', 'Nama Pegawai', 'required');
        // Add other validation rules as needed
    
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, reload the form with errors
            $this->load->view('edit_pegawai');
        } else {
            // Validation passed, prepare data for update
            $id = $this->input->post('id');
            $data = array(
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'jabatan' => $this->input->post('jabatan'),
                'tglaktif_jabatan' => $this->input->post('tglaktif_jabatan'),
                'tglmasuk_jabatan' => $this->input->post('tglmasuk_jabatan'),
                'status' => $this->input->post('status'),
                'aktif' => $this->input->post('aktif')
            );
    
            // Update data in the database
            $result = $this->pegawai_model->update($id, $data);
    
            if ($result) {
                // Redirect to a success page or display a success message
                redirect('welcome/tables');
            } else {
                // Handle the error
                $this->load->view('edit_pegawai', ['error' => 'Failed to update data']);
            }
        }
    }
}