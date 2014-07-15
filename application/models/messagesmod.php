<?php

class Messagesmod extends CI_Model {

    /*Class Variables*/
    var $current_year; //declare current year class variable

    /**
     * model constructor
     * sets $current_year
     */
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->current_year = intval(date('Y')); //set the current year for group operations
    }

    /**
     * list threads (assumes latest message is thread head)
     * @param $user_id
     * @return mixed
     */
    public function listThreads($user_id) {
        return $this->db->select('message_id, year, code, message, read, MAX(timestamp)')
            ->from('messages')
            ->where('to_user_id', $user_id)
            ->or_where('from_user_id', $user_id)
            ->group_by('year, group_code')
            ->get()->result();
    }

    /**
     * Mark a message as read - message is addressed to current user
     * @param $user_id      to user id
     * @param $year
     * @param $code
     * @return mixed
     */
    public function markAsRead($user_id, $year, $code)
    {
        if ($year == null) $year = $this->current_year;
        return $this->db->update('messages', array('is_read' => true), array('to_user_id' => $user_id, 'year' => $year, 'group_code' => $code));
    }

    /**
     * Count total new messages addressed to user
     * @param $user_id      to user id
     * @return mixed
     */
    public function newMessageCount($user_id)
    {
        return $this->db->select('*')
            ->from('messages')
            ->where(array('to_user_id' => $user_id, 'is_read' => false))
            ->group_by('year, group_code')
            ->get()->num_rows();
    }

    /**
     * get a thread of messages based on user id and group code
     * @param $user_id
     * @param $year
     * @param $code
     * @return mixed
     */
    public function getThread($user_id, $year, $code) {
        if ($year == null) $year = $this->current_year;
        return $this->db->select('*')
            ->from('messages')
            ->where(array('user_id' => $user_id, 'year' => $year, 'group_code' => $code))
            ->order_by('timestamp', 'desc')
            ->get()->result();
    }

    public function send($from_id, $to_id, $year, $code, $message) {
        if ($year == null) $year = $this->current_year;
        $success = $this->db->insert('messages', array('from_user_id' => $from_id, 'to_user_id' => $to_id, 'year' => $year, 'group_code' => $code, 'message' => $message));

        if (!$success) return false;

        return $this->sendNotificationEmail($to_id);
    }

    private function sendNotificationEmail($to_id)
    {
        $this->load->library('email');
        $subject = "You have a new Secret Santa private message!";
        // TODO: get recipient name
        $message = "Hello $name,

        You have just received a new private message on the Secret Santa site!

        Log in to your profile now to check your messages.";

        $this->email->from($this->config->item('email_from_name'),
            $this->config->item('email_from_email'));
        $this->email->to($to); // TODO: get recipient email
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }

}