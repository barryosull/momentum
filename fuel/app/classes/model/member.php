<?

class Model_MemberException extends Exception {}
class Model_MemberProjectException extends Model_MemberException {}

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
		$user = Model_User::init($params);

		$member = new self();

		$member->user = $user;

		$member->save();

		return $member;		
	}

	public static function login($params)
	{
		Model_User::login($params);
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
}