<?

class Model_MemberException extends Exception {}

class Model_MemberProjectException extends Model_MemberException {}
class Model_MemberUserException extends Model_MemberException {}
class Model_MemberMissingParamException extends Model_MemberException {}

class Model_Member extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'user'
	);

	protected static $_has_many = array(
		'project',
		'periodoftime'
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function init($params)
	{
		try{
			$user = Model_User::init($params);
		}catch(Model_UserException $e){
			throw new Model_MemberUserException($e->getMessage());
		}

		$member = new self();

		$member->user = $user;

		$member->save();

		return $member;		
	}

	public static function login($params)
	{
		try{
			Model_User::login($params);
		}catch(Model_UserException $e){
			throw new Model_MemberUserException($e->getMessage());
		}
	}

	public static function logout()
	{
		try{
			Model_User::logout();
		}catch(Model_UserException $e){
			throw new Model_MemberUserException($e->getMessage());
		}
	}	

	public static function get_logged_in_member()
	{
		$user = Model_User::get_logged_in_user();
		return $user->member;
	}

	public function add_project(Model_Project $project)
	{
		if(isset($this->project[$project->id])){
			throw new Model_MemberProjectException('Member already has this project');
		}
		$this->project[] = $project;
		$this->save();
	}

	public function get_all_projects()
	{
		return $this->project;
	}

	public function add_period_of_time($time=array())
	{
		if(get_class($time) != 'Model_PeriodOfTime'){

			throw new Model_MemberMissingParamException('Model_PeriodOfTime param is missing');
		}
		$this->periodoftime[] = $time;
		$this->save();
	}

	public function get_all_period_of_time_by_date(DateTime $date)
	{
		$timestamp = $date->getTimestamp();
		$day_after_timestamp = $timestamp + 86400;
		
		$times = Model_PeriodOfTime::find()
					->where('member_id', $this->id)
					->where('created_at', '>=', $timestamp)
					->where('created_at', '<', $day_after_timestamp)
					->get();	

		return $times;
	}
}