<?php

class Messages extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation'); //form validation helper
        if ($this->session->userdata('auth') != 'true') {
            redirect(base_url("login/timeout"));
        }
        $this->load->model('messagesmod');
        $this->load->model('datamod');
    }

    public function index() {
        render('messages',
            array('first_year' => $this->datamod->getJoinYear($this->session->userdata('id')),
                  'current_year' => intval(date('Y')),
                  'messages_array' => array()
            )
        );
    }

    public function compose() {

    }

    public function thread() {

    }

}
