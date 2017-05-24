<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_chat_room');
		$this->load->model('m_chat');
	}

	public function home()
	{
		$data['conversations'] = $this->m_chat_room->getAll();
		
		$this->load->view('adminhome', $data);

	}

	public function viewConversation($conversation_id)
	{
		$data['conversation'] = $this->m_chat_room->getRoomWithConversation($conversation_id);
		
		$data['roomId'] = $conversation_id;
 		$data['getAllUserMessage'] = function($users)use($conversation_id){
 			return $this->m_chat->getAllRoomMessagesByUsers($conversation_id, $users);
 		};
 		$userInfo = $this->session->userdata('user_profile');
 		$data['loggedInUserId'] = $this->session->userdata('user_id');
 		$data['fullname'] = $userInfo['name'];
		$data['picture'] = $userInfo['picture'];

		$this->load->view('admin_show_conversation', $data);
	}
}
