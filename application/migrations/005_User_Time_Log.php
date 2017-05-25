<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_User_Time_Log extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'BIGINT',
			),
			'time_log' => array(
				'type' => 'DATETIME',
			),
			'status' => array(
				'type' => 'INT',
			),
			'created_at' => array(
				'type' => 'DATETIME',
			),
			'updated_at' => array(
				'type' => 'DATETIME',
			),
		));

		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('user_time_log');

	}

	public function down()
	{
		$this->dbforge->drop_table('user_time_log');
	}
}

