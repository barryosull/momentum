<?

class Model_MemberException extends Exception {}

class Model_MemberProjectException extends Model_MemberException {}
class Model_MemberUserException extends Model_MemberException {}
class Model_MemberProjectMismatchException extends Model_MemberException {}
class Model_MemberProjectNotFoundException extends Model_MemberException {}
class Model_MemberProjectDuplicateException extends Model_MemberException {}

class Model_MemberPeriodoftimeNotFoundException extends Model_MemberException {}
class Model_MemberPeriodoftimeMismatchException extends Model_MemberException {}

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

	public static function get_login_hash_for_login_details($params)
	{
		try{
			return Model_User::get_login_hash_for_login_details($params);
		}catch(Model_UserException $e){
			throw new Model_MemberUserException($e->getMessage());
		}
	}

	public static function get_by_login_hash($hash)
	{
		$user = Model_User::get_by_login_hash($hash);
		return $user->member;
	}

	public function add_project(Model_Project $project)
	{
		$this->check_if_project_already_added($project);
		$this->check_for_project_with_same_name($project);

		$this->project[$project->id] = $project;
		$this->save();
	}

	private function check_if_project_already_added($project)
	{
		if(isset($this->project[$project->id])){
			throw new Model_MemberProjectException('Member already has this project');
		}
	}

	private function check_for_project_with_same_name($project)
	{
		$num_same_project = Model_Project::find()
			->where('name', $project->name)
			->where('member_id', $this->id)
			->count();

		if($num_same_project != 0){
			throw new Model_MemberProjectDuplicateException('Member has project with this name already');
		}
	}

	public function get_all_projects()
	{
		return $this->sort_project_alphabetically($this->project);
	}

	private function sort_project_alphabetically($projects)
	{
		$projects = array();
		foreach($this->project as $project){
			$projects[$project->name] = $project;
		}
		ksort($projects);

		return $projects;
	}

	public function get_all_period_of_time_by_date(DateTime $date)
	{
		if(count($this->project) == 0){
			return array();
		}
		$timestamp = $date->getTimestamp();
		$day_after_timestamp = $timestamp + 86400;
		
		$query = Model_PeriodOfTime::find()					
					->where('created_at', '>=', $timestamp)
					->where('created_at', '<', $day_after_timestamp);

		foreach($this->project as $project){
			$query->or_where('project_id', $project->id);
		}

		$times = $query->get();	

		return $times;
	}

	public function get_project_by_id($id=0)
	{
		$project = Model_Project::get_by_id($id);
		$this->check_project_is_accessible($project);
		return $project;
	}

	private function check_project_is_accessible($project)
	{
		if(!$project){
			throw new Model_MemberProjectNotFoundException("Project does not exist");
		}
		if($project->member_id != $this->id){
			throw new Model_MemberProjectMismatchException("Project does not belong to member");
		}
	}

	public function remove_project($project)
	{
		unset($this->project[$project->id]);
		$this->save();
	}

	public function get_periodotime_by_id($id)
	{
		$time = Model_PeriodOfTime::get_by_id($id);
		$this->check_periodoftime_is_accessible($time);
		return $time;
	}

	private function check_periodoftime_is_accessible($time)
	{
		if(!$time){
			throw new Model_MemberPeriodoftimeNotFoundException("PeriodOfTime could not be found");
		}
		$project = $time->project;

		if(!$project || $project->member_id != $this->id){
			throw new Model_MemberPeriodoftimeMismatchException("PeriodOfTime does not belong to member");
		}
	}
}