<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_chat_room');
		$this->load->model('m_chat');
		$this->load->library('encrypt');
		$this->load->model('m_user');
		$this->load->model('m_user_payroll');
		$this->load->model('m_user_jhunnie_info');
		$this->load->model('m_user_time_log');
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

	public function viewUserLog($user_id)
	{
		$data['hashed_id'] = $user_id;
		$user_id = decode_url($user_id);
		$data['time_logs'] = $this->m_user->getUserTimeLogs($user_id);
		$data['user'] = $this->m_user->getUser($user_id);

		if ($this->input->post())
		{
			$updateArray = array();
			$id = $this->input->post('id');
			$morning_in_log = $this->input->post('morning_in_log');
			$morning_out_log = $this->input->post('morning_out_log');
			$noon_in_log = $this->input->post('noon_in_log');
			$noon_out_log = $this->input->post('noon_out_log');

			for($x = 0; $x < sizeof($id); $x++){

			    $updateArray[] = array(
			        'id'=>$id[$x],
			        'morning_in_log' => $morning_in_log[$x] == '' ? '0000-00-00 00:00:00' : date("Y-m-d H:i", strtotime($morning_in_log[$x])),
			        'morning_out_log' => $morning_out_log[$x] == '' ? '0000-00-00 00:00:00' : date("Y-m-d H:i", strtotime($morning_out_log[$x])),
			        'noon_in_log' => $noon_in_log[$x] == '' ? '0000-00-00 00:00:00' : date("Y-m-d H:i", strtotime($noon_in_log[$x])),
			        'noon_out_log' => $noon_out_log[$x] == '' ? '0000-00-00 00:00:00' : date("Y-m-d H:i", strtotime($noon_out_log[$x])),
			    );
			}  

			$this->m_user_time_log->update_user_logs($updateArray);

			redirect(current_url());
		}

		$this->load->view('admin_user_time_log', $data);
	}

	public function recomputePayroll($user_id, $user_log_id)
	{
		$data['user_id'] = decode_url($user_id);
		$data['time_log_id'] = $user_log_id;
		$userRate = $this->m_user_jhunnie_info->get(decode_url($user_id));
		$timelog = $this->m_user_time_log->get($user_log_id);
		$totalDailyHour = $this->m_user->getDailyHour($user_log_id);
		$day_break = $this->m_user->getDailyBreak($user_log_id);

		if ($day_break->hours < 0)
		{
			$hoursActive = $totalDailyHour != null && $day_break ? $totalDailyHour->hours - 1 : 0;
		}else{
			$hoursActive = $totalDailyHour != null && $day_break ? $totalDailyHour->hours - $day_break->hours : 0;
		}
		
		$data['salary_rate'] = $userRate ? $userRate->salary_rate : false;

		$userDaily = $userRate ? $userRate->salary_rate/20 : 0;
		$userHourly = $userDaily/8;
		$lateMin = 8-$hoursActive < 0 ? 0 : 8-$hoursActive;
		$otMin = 8-$hoursActive < 0 ? abs(8-$hoursActive): 0;

		$data['late'] = $lateMin * $userHourly;
		$data['overtime'] = $otMin * $userHourly;
		// night diff %total hrs rate * .20
		$data['night_diff'] = false;
		$salary = $userDaily - $data['late'] + $data['overtime'];
		$data['salary_receive'] = $salary;
		
		// echo "<pre>";
		// var_dump($data);
		// echo "</pre>";
		// die();

		$this->m_user_payroll->recompute($user_log_id, $data);

		redirect(site_url('admin/viewUserLog/'.$user_id));
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
