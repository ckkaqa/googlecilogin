<?php
class M_User extends CI_Model {
	
	public function insert($data){
		$user = $this->checkIfUserExist($data['google_id']);
		
		if (!$user){
			$this->db->insert('user', $data);
        	return $this->db->insert_id();	
		}else{
			return $user->id;
		}
	}

	public function checkIfUserExist($googleId)
	{
		$sql = '
			SELECT
				*
			FROM user
			WHERE google_id = ?
		';

		$query = $this->db->query($sql, array($googleId));

		return $query->row();

	}

	public function getAllUser()
	{
		$sql = '
			SELECT
				*
			FROM user
		';

		$q = $this->db->query($sql);

		return $q->result();
	}

	public function getAvailableUserInRoom($roomId)
	{
		$sql = '
			SELECT
				*
			FROM user
		';

		$q = $this->db->query($sql);
		$users = $q->result();
		$data = array();
		foreach ($users as $key => $user) {
			$room = $this->getRoom($roomId, $user->id);
			if (!$room)
			{
				$data[$key] = $user;
			}
		}

		return $data;
	}

	public function getRoom($roomId, $userId){
		$sql = '
			SELECT 
				*
			FROM chat_room_members
			WHERE chat_room = ?
			AND member = ?
		';

		$q = $this->db->query($sql, array($roomId, $userId));

		return $q->row();
	}

	public function checkIfUserInRoom($roomId, $userId)
	{
		$sql = '
			SELECT 
				*
			FROM chat_room_members
			WHERE chat_room = ?
			AND member = ?
			AND is_member_removed = ?
		';

		$q = $this->db->query($sql, array($roomId, $userId, 0));

		$result = $q->row();

		return $result?true:false;

	}

	public function addTimeLog($data)
	{

		$this->db->insert('user_time_log', $data);
    	return $this->db->insert_id();
    	
	}

	public function getUserTimeLogs($userId)
	{

		$sql = '
			SELECT
				*
			FROM user_time_log
			WHERE user_id = ?
		';

		$q = $this->db->query($sql, [$userId]);

		return $q->result();

	}

	public function getlastLogStatus($userId)
	{

		$sql = '
			SELECT
				*
			FROM user_time_log
			WHERE user_id = ?
			ORDER BY time_log DESC
		';

		$q = $this->db->query($sql, [$userId]);

		return $q->row();

	}

	public function getUser($user_id)
	{
		$query = $this->db
			->where('id', $user_id)
			->get('user');

		return $query->row();
	}
}