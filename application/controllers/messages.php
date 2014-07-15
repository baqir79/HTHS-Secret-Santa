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
        $this->user_id = $this->session->userdata('id');
        $this->current_year = intval(date('Y'));
        $this->first_year = $this->datamod->getJoinYear($this->user_id);
    }

    public function index() {
        $threads = $this->messagesmod->listThreads($this->session->userdata('id'));
        render('messages', array('threads' => $threads, 'first_year'=>$this->first_year, 'current_year'=>$this->current_year));
    }

    public function send($code) {
        $pair = $this->datamod->getPair($code, $this->user_id);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');

        if (!$this->form_validation->run()) {
            render('messages_send', array('code' => $code));
        } else {
            $this->messagesmod->send($pair, null, $this->input->post('code'), $this->input->post('message'));
            redirect('messages');
        }
    }

    public function markRead($code)
    {
        $this->messagesmod->markAsRead($this->user_id, null, $code);
        redirect('messages');
    }

}
