<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_chat_room');
		$this->load->model('m_chat');
		$this->load->library('encrypt');
		$this->load->model('m_user');
		$this->load->model('m_user_jhunnie_info');
	}

	public function home()
	{
		$data['conversations'] = $this->m_chat_room->getAll();
		
		$this->load->view('adminhome', $data);

	}

	public function setup()
	{
		$data['users'] = $this->m_user->getAllUser();
		$this->load->view('adminsetup', $data);
	}

	public function setupUserCreds($user_id)
	{
		$user_id = decode_url($user_id);
		$data['user'] = $this->m_user->getUser($user_id);
		$data['user_info'] = $this->m_user_jhunnie_info->get($user_id);

		if ($this->input->post()){

			$ins['user_id'] = $user_id;
			$ins['salary_rate'] = $this->input->post('salary');
			
			$work_start = $this->input->post('work_start');
			$start_timestamp = strtotime($work_start);
			$start_date = date("Y-m-d H:i:s", $start_timestamp);
			$ins['work_start'] = $start_date;

			$work_end = $this->input->post('work_end');
			$end_timestamp = strtotime($work_end);
			$end_date = date("Y-m-d H:i:s", $end_timestamp);
			$ins['work_end'] = $end_date;
			
			if ($data['user_info']) {
				$this->m_user_jhunnie_info->update($ins);
			}else{
				$this->m_user_jhunnie_info->insert($ins);
			}

			redirect(current_url());
		}

		$this->load->view('admin_user_setup', $data);
	}

	public function viewConversation($conversation_id)
	{
		$conversation_id = decode_url($conversation_id);
		$data['conversation'] = $this->m_chat_room->getRoomWithConversation($conversation_id);
		
		$data['roomId'] = $conversation_id;
 		$data['getAllUserMessage'] = function($users)use($conversation_id){
 			return $this->m_chat->getAllRoomMessagesByUsers($conversation_id, $users);
 		};

 		$data['loggedInUserId'] = $this->session->userdata('user_id');

		$this->load->view('admin_show_conversation', $data);
	}
}
