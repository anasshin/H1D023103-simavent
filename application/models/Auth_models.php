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

    // register mahasiswa
    public function register($name, $email, $password, $role = 'mahasiswa')
    {
        $data = array(
            'name' => $name,
            //'nim' => $nim,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'email' => $email,
            'role' => $role
        );

        return $this->db->insert('users', $data);
    }

    //login
    public function login($email, $password)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();
            echo "Password input: $password<br>";
            echo "Password DB: {$user->password}<br>";
            echo "Password verify: " . password_verify($password, $user->password) . "<br>";

            if (password_verify($password, $user->password)) {
                // Set session data
                $session_data = array(
                    'user_id' => $user->id,
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

    //cek login
    public function isLogin()
    {
        return $this->session->userdata('logged_in');
    }

    // cek admin
    public function isAdmin()
    {
        return ($this->isLogin() && $this->session->userdata('role') == 'admin');
    }

    public function isMahasiswa()
    {
        return ($this->isLogin() && $this->session->userdata('role') == 'mahasiswa');
    }

    //curent Users
    public function getCurrentUser()
    {
        if ($this->isLogin()) {
            return array(
                'id' => $this->session->userdata('user_id'),
                'name' => $this->session->userdata('name'),
                'email' => $this->session->userdata('email'),
                'role' => $this->session->userdata('role')
            );
        }
        return NULL;
    }

    // Logout
    public function logout()
    {
        $this->session->sess_destroy();
    }

    // get User
    public function getUser($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    // update user
    public function updateUser($user_id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }
}
