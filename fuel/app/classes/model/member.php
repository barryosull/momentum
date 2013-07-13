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

	public function get_projects()
	{
		return new Model_ProjectCollection($this->id);
	}
}