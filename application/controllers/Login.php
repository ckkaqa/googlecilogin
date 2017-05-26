<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		
		if($this->session->userdata('login') == true){
			redirect('welcome/index');
		}
		
		if (isset($_GET['code'])) {
			
			$this->googleplus->getAuthenticate();
			$this->session->set_userdata('login',true);
			$this->session->set_userdata('user_profile',$this->googleplus->getUserInfo());
			redirect('login/saveUser');
			
		} 
			
		$contents['login_url'] = $this->googleplus->loginURL();
		$this->load->view('login',$contents);
		
	}
	
	public function saveUser()
	{
		$info = $contents['user_profile'] = $this->session->userdata('user_profile');

		$data['email']= $info['email'];
		$data['fullname']= $info['name'];
		$data['firstname']= $info['given_name'];
		$data['lastname']= $info['family_name'];
		$data['google_id'] = $info['id'];
		$data['gender']= isset($info['gender']) ? $info['gender'] : '';
		$data['dob']= false;
		$data['profile_image']= $info['picture'];
		$data['gpluslink'] = false;

		$this->load->model('m_user');
		$ins = $this->m_user->insert($data);
		$this->session->set_userdata('user_id', $ins);
		redirect('welcome/index');
	}

	public function profile($check = false){
		$this->load->model('m_user');
		$this->load->model('m_User_jhunnie_info');

		if($this->session->userdata('login') != true){
			redirect('');
		}
		
		$timeLogs = $this->m_user->getUserTimeLogs($this->session->userdata('user_id'));
		$salaryRate = $this->m_User_jhunnie_info->get($this->session->userdata('user_id'));

		$contents['getHours'] = function($id)
		{
			return $this->m_user->getDailyHour($id);
		};


		$info = $contents['user_profile'] = $this->session->userdata('user_profile');
		$contents['salaryRate'] = $salaryRate;
		$contents['check'] = $check;
		$contents['time_logs'] = $timeLogs;

		$this->load->view('profile',$contents);

	}

	public function addTimeLog($check)
	{
		$this->load->model('m_user');
		$this->load->helper('date');

		$status = 'morningin';
		$stat = 'morning_in_log';
		$timeLog = $this->m_user->getlastLogStatus($this->session->userdata('user_id'));
		if ($timeLog) {
			
			$diff = timespan(strtotime($timeLog->morning_in_log), strtotime(date('Y-m-d H:i:s')));

			$time_diff = round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($timeLog->morning_in_log)) / 60 / 60,2);

			if ((int)$time_diff >= 12) {
				$status = 'morningin';
				$stat = 'morning_in_log';
			}elseif ($timeLog->status == 'morningin') {
				$status = 'morningout';
				$stat = 'morning_out_log';
			}elseif ($timeLog->status == 'morningout') {
				$status = 'noonin';
				$stat = 'noon_in_log';
			}elseif ($timeLog->status == 'noonin') {
				$status = 'noonout';
				$stat = 'noon_out_log';
			}

		}
		if ($check) {
			$date = date('Y-m-d H:i:s');
			$data['created_at'] = $date;
			$data[$stat] = $date;
			$data['status'] = $status;
			$data['user_id'] = $this->session->userdata('user_id');
			if ($status == 'morningin') {
				$ins = $this->m_user->addTimeLog($data);	
			}else{
				$ins = $this->m_user->updateTimeLog($data, $timeLog->id);
			}
		}

		redirect('login/profile');
	}
	
	public function logout(){
		
		$this->session->sess_destroy();
		$this->googleplus->revokeToken();
		redirect('');
		
	}
	
}
