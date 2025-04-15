<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    // Register new user (mahasiswa)
    public function register($name, $email, $password)
    {
        $data = array(
            // 'nim' => $nim,
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'mahasiswa'
        );

        return $this->db->insert('users', $data);
    }

    // Login user
    public function login($email, $password)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();

            if (password_verify($password, $user->password)) {
                // Set session data
                $session_data = array(
                    'user_id' => $user->id,
                    // 'nim' => $user->nim,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($session_data);
                return TRUE;
            }
        }

        return FALSE;
    }

    // Check if user is logged in
    public function is_logged_in()
    {
        return $this->session->userdata('logged_in');
    }

    // Check if user is admin
    public function is_admin()
    {
        return ($this->is_logged_in() && $this->session->userdata('role') == 'admin');
    }

    // Check if user is mahasiswa
    public function is_mahasiswa()
    {
        return ($this->is_logged_in() && $this->session->userdata('role') == 'mahasiswa');
    }

    // Get current user data
    public function get_current_user()
    {
        if ($this->is_logged_in()) {
            return array(
                'id' => $this->session->userdata('user_id'),
                'name' => $this->session->userdata('name'),
                'email' => $this->session->userdata('email'),
                'role' => $this->session->userdata('role')
            );
        }
        return NULL;
    }

    // Logout user
    public function logout()
    {
        $this->session->sess_destroy();
    }

    // Get user by ID
    public function get_user($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    // Update user profile
    public function update_profile($user_id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    // Check if email exists
    public function email_exists($email)
    {
        $this->db->where('email', $email);
        return $this->db->get('users')->num_rows() > 0;
    }

    // Check if NIM exists

}
