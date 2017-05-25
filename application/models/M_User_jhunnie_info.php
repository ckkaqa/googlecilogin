<?php
class M_User_jhunnie_info extends CI_Model {
	
	public function insert($data){
		$this->db->insert('user_jhunnie_info', $data);
		
        return $this->db->insert_id();	
	}

}