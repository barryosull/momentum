<?php

class Model_ProjectException extends Exception {}

class Model_Project extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'member_id' => array(
			'default' => 0
		),
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'member'
	);	

	protected static $_has_many = array(
		'periodoftime'
	);

	protected static $_table_name = 'projects';

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
		'Orm\\Observer_Self' => array(
    		'events' => array('before_delete')
    	)
	);
	
	public static function init($params)
	{
		if($params['name'] == ''){
			throw new Model_ProjectException("Project name cannot be blank");
		}

		$project = self::forge($params);

		$project->save();

		return $project;
	}

	public static function get_by_id($id)
	{
		return self::find()->where('id', '=', $id)->get_one();
	}

	public static function get_all()
	{
		return self::find()->get();
	}

	public function add_periodoftime($time)
	{
		$this->periodoftime[] = $time;
		$this->save();
	}

	public function get_totaltime_for_date_range(DateTime $from, DateTime $to)
	{
		$times = Model_PeriodOfTime::find()
			->where('project_id', $this->id)
			->where('created_at', '>=', $from->getTimestamp()) 
			->where('created_at', '<', $to->getTimestamp()) 
			->get();

		$total = 0;

		foreach($times as $time){
			$total += $time->minutes;
		}

		return $total;
	}

	public function _event_before_delete()
	{
		$this->member->remove_project($this);
	}
}
