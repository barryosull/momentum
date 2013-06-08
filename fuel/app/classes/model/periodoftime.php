
<?php

class Model_PeriodOfTimeException extends Exception {}

class Model_PeriodOfTime extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'project_id',
		'member_id',
		'minutes',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'member',
		'project'
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
	protected static $_table_name = 'periodoftimes';

	public static function init($params)
	{
		self::check_init_params($params);		
		return self::create_time($params);
	}

	private static function check_init_params($params)
	{
		if(!isset($params['project'])){
			throw new Model_PeriodOfTimeException("Project field is required, it must be of type Model_Project");
		}
		if(get_class($params['project']) != 'Model_Project'){
			throw new Model_PeriodOfTimeException("Project field is required, it must be of type Model_Project");
		}
		if((int)$params['minutes'] < 1){
			throw new Model_PeriodOfTimeException("Minutes field must be greater than 0, '".(int)$params['minutes']."' was entered");
		}
	}

	private static function create_time($params)
	{
		$time = self::forge($params);
		$time->project_id = $params['project']->id;
		$time->member_id = 0;
		$time->save();

		return $time;
	}

	public static function get_by_id($id)
	{
		return self::find()->where('id', '=', $id)->get_one();
	}

	public static function get_all_by_date(DateTime $date)
	{
		$timestamp = $date->getTimestamp();
		$day_after_timestamp = $timestamp + 86400;
		
		$times = self::find()
					->where('created_at', '>=', $timestamp)
					->where('created_at', '<', $day_after_timestamp)
					->get();	

		return $times;
	}
}
