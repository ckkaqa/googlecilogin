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

}