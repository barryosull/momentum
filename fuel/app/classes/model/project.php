<?php

class Model_ProjectException extends Exception {}

class Model_Project extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);
	protected static $_table_name = 'projects';

	public static function init($params)
	{
		if($params['name'] == '')
		{
			throw new Model_ProjectException("Project name cannot be blank");
		}

		$is_existing_project = (bool)self::find()->where('name', '=', $params['name'])->count();

		if($is_existing_project)
		{
			throw new Model_ProjectException("Project '".$params['name']."' already exists");
		}

		$project = self::forge($params);

		$project->save();

		return $project;
	}

	public static function get_by_id($id)
	{
		return self::find()->where('id', '=', $id)->get_one();
	}
}
