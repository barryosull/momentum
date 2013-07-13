
<?php

class Model_PeriodOfTimeException extends Exception {}

class Model_PeriodOfTime extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'project_id' => array(
			'default' => 0
		),
		'minutes',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
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
		if((int)$params['minutes'] < 1){
			throw new Exception_Input("Minutes field must be greater than 0, '".(int)$params['minutes']."' was entered");
		}
	}

	private static function create_time($params)
	{
		$time = self::forge($params);
		$time->member_id = 0;
		$time->save();

		if(isset($params['date'])){
			$time->created_at = $params['date']->getTimestamp();
			$time->save();
		}

		return $time;
	}

	public static function get_by_id($id)
	{
		return self::find()->where('id', '=', $id)->get_one();
	}

	public function get_created_at()
	{	
		$date = new DateTime('@'.$this->created_at);
		return $date;
	}
}
