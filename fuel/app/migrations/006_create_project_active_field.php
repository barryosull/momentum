<?php

namespace Fuel\Migrations;

class Create_project_active_field
{
    public function up()
    {
        $fields = array(
            'is_active' => array('type' => 'int', 'constraint' => 1, 'default' => 1)
        );

        \DBUtil::add_fields('projects', $fields);
    }

    public function down()
    {
        \DBUtil::drop_fields('projects', array('is_active'));
    }
}