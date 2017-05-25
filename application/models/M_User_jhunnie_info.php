<?php
class M_User_jhunnie_info extends CI_Model {
	
	public function insert($data){
		$this->db->insert('user_jhunnie_info', $data);
		
        return $this->db->insert_id();

	}

	public function update($data)
	{
		$this->db->update('user_jhunnie_info', $data);

		return true;

	}
	
	public function get($user_id)
	{
		$this->db->where('user_id', $user_id);
		$q = $this->db->get('user_jhunnie_info');

		return $q->row();
	}
}