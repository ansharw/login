<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $data['title'] = 'Welcome';
        $this->load->model('model_auth');
        $this->load->view('_partials/header', $data);
        $this->load->view('_partials/js', $data);
    }
    public function login()
    {

        $this->logged_in();

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            $username_exists = $this->model_auth->check_username($this->input->post('username'));

            if ($username_exists == TRUE) {
                $login =
                    $this->model_auth->login($this->input->post('username'), $this->input->post('password'));

                if ($login) {

                    $logged_in_sess = array(
                        'users_id' => $login['users_id'],
                        'username'  => $login['username'],
                        'logged_in' => TRUE
                    );

                    $this->session->set_userdata($logged_in_sess);
                    redirect('dashboard', 'refresh');
                } else {
                    $this->data['errors'] = '<div class="alert alert-danger" role="alert">Incorrect username / password combination</div>';
                    $this->load->view('login', $this->data);
                }
            } else {
                $this->data['errors'] = '<div class="alert alert-danger" role="alert">Username not valid</div>';
                $this->load->view('login', $this->data);
            }
        } else {
            // false case
            $this->load->view('login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login', 'refresh');
    }
}
