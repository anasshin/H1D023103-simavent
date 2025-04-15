<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation');
    }

    // Login page
    public function login()
    {
        if ($this->Auth_model->is_logged_in()) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == TRUE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            echo "Email: $email, Password: $password<br>";

            $login_result = $this->Auth_model->login($email, $password);
            var_dump($login_result);

            if ($this->Auth_model->login($email, $password)) {
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Email atau password salah');
                redirect('auth/login');
            }
        }

        $data['title'] = 'Login - SIMAVENT';
        $this->load->view('layouts/header');
        $this->load->view('auth/login', $data);
        $this->load->view('layouts/footer');
    }

    // Register page for mahasiswa
    public function register()
    {
        if ($this->Auth_model->is_logged_in()) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_check_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');

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

        $data['title'] = 'Daftar Akun - SIMAVENT';
        $this->load->view('layouts/header');
        $this->load->view('auth/register', $data);
        $this->load->view('layouts/footer');
    }

    // Check if email already exists
    public function check_email($email)
    {
        if ($this->Auth_model->email_exists($email)) {
            $this->form_validation->set_message('check_email', 'Email sudah terdaftar');
            return FALSE;
        }
        return TRUE;
    }

    // Logout
    public function logout()
    {
        $this->Auth_model->logout();
        redirect('auth/login');
    }
}
