<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation');

        // $this->load->helper('url');
    }

    public function register()
    {
        $this->load->library('form_validation');
        if ($this->Auth_model->isLogin()) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('name', 'name', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == TRUE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->Auth_model->register($name, $email, $password)) {
                $this->session->set_flashdata('success', 'Pendaftaran berhasil. Silakan login.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan. Silakan coba lagi.');
                redirect('auth/register');
            }
        }
        $this->load->view('layouts/header');
        // $this->load->view('layouts/navbar');
        $this->load->view('auth/register');
        $this->load->view('layouts/footer');
    }

    public function login()
    {

        if ($this->Auth_model->isLogin()) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == TRUE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->Auth_model->login($email, $password)) {
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Email atau password salah');
                redirect('auth/login');
            }
        }
        $data['title'] = 'Login';
        $this->load->view('layouts/header');
        $this->load->view('auth/login', $data);
        $this->load->view('layouts/footer');
    }
}
