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

	protected static $_belongs_to = array(
		'member'
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

	protected static $_has_many = array('periodoftime');

	public static function init($params)
	{
		if($params['name'] == ''){
			throw new Model_ProjectException("Project name cannot be blank");
		}
		if(self::is_existing_project($params['name'])){
			throw new Model_ProjectException("Project '".$params['name']."' already exists");
		}

		$project = self::forge($params);

		$project->save();

		return $project;
	}

	private static function is_existing_project($name)
	{
		return (bool)self::find()->where('name', '=', $name)->count();
	}

	public static function get_by_id($id)
	{
		return self::find()->where('id', '=', $id)->get_one();
	}

	public static function get_all()
	{
		return self::find()->get();
	}
}
