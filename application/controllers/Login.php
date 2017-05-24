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

	public function profile(){
		

		if($this->session->userdata('login') != true){
			redirect('');
		}
		$info = $contents['user_profile'] = $this->session->userdata('user_profile');
		$this->load->view('profile',$contents);

	}
	
	public function logout(){
		
		$this->session->sess_destroy();
		$this->googleplus->revokeToken();
		redirect('');
		
	}
	
}
