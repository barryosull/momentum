<?php

namespace Fuel\Migrations;

class Create_member_relation_fields
{
	public function up()
	{
		$fields = array(
			'member_id' => array('type' => 'int', 'constraint' => 11)
		);

		\DBUtil::add_fields('projects', $fields);
		\DBUtil::add_fields('periodoftimes', $fields);
	}


	public function down()
	{
		\DBUtil::drop_fields('projects', array('member_id'));
		\DBUtil::drop_fields('periodoftimes', array('member_id'));
	}
}