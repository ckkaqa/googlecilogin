<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('login') != true){
			redirect('');
		}
		$this->load->model('m_chat');
		$this->load->model('m_chat_room');
		$this->load->model('m_user');
	}

	public function index($roomId=false)
	{
		$userInfo = $this->session->userdata('user_profile');

		$rooms = $this->m_chat_room->getAllRoom();
		$room = $this->m_chat_room->get($roomId);
		$data['loggedInUserId'] = $this->session->userdata('user_id');
		$data['fullname'] = $userInfo['name'];
		$data['picture'] = $userInfo['picture'];
		$data['rooms'] = $rooms;
		$data['activeRoom'] = $room;
		$data['activeRoomId'] = $room?$room->id:0;
		$data['availableUser'] = $this->m_user->getAvailableUserInRoom($roomId);

		$this->load->view('welcome_message', $data);
	}

	public function getConversation($roomId=false){
		
		$data['conversation'] = $this->m_chat_room->getRoomWithConversation($roomId);
		$data['roomId'] = $roomId;
		$s = $data['getAllUserMessage'] = function($users)use($roomId){
			return $this->m_chat->getAllRoomMessagesByUsers($roomId, $users);
		};

		$theHTMLResponse    = $this->load->view('conversations', $data, true);
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode(array('messages'=> $theHTMLResponse)));

	}

	public function createMessage($roomId = false)
	{
		$data['message'] = $this->input->post('message');
		$date = date('Y-m-d H:i:s');
		$data['created_at'] = $date;
		$data['user'] = $this->session->userdata('user_id');
		$data['room_id'] = $roomId;
		$ret = $this->m_chat->createMessage($data);

		echo json_encode('success');
	}

	public function addRoom()
	{

		if ($this->input->post()) {
			$data['name'] = $this->input->post('room-name');
			$data['status'] = $this->input->post('happy');
			$date = date('Y-m-d H:i:s');
			$data['created_at'] = $date;
			$data['owner'] = $this->session->userdata('user_id');

			$ret = $this->m_chat_room->createRoom($data);

			if ($ret) {
				$member['chat_room'] = $ret; 
				$member['member'] = 1;
				$this->m_chat_room->addChatRoomMember($member);
			}

			redirect('/welcome/index/'.$userId);
		}
	}

	public function kickMember($userId = false, $roomId = false)
	{
		$ret = $this->m_chat_room->kickMember($userId, $roomId);
		
		if ($ret) {
			
		}

		redirect('/welcome/index/'.$roomId);

	}
	
}
