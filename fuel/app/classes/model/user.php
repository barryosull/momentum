<?php

class Model_UserException extends Exception {}

class Model_UserLoginException extends Model_UserException {}
class Model_UserHashException extends Model_UserException {}
class Model_UserNameException extends Model_UserException {}
class Model_UserEmailException extends Model_UserException {}
class Model_UserPasswordException extends Model_UserException {}

class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'email',
		'password',
		'name',
		'group',
		'profile_fields',
		'last_login',
		'login_hash',
		'created_at',
		'updated_at',
	);

	protected static $_has_one = array(
		'member'
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
		self::check_init_params($params);
	
		return self::create_user($params);
	}

	private static function check_init_params($params)
	{
		if($params['name'] == '')
		{
			throw new Model_UserNameException('Name cannot be blank');
		}
		if($params['email'] == '')
		{
			throw new Model_UserEmailException('Email cannot be blank');
		}
		if($params['password'] == '')
		{
			throw new Model_UserPasswordException('Password cannot be blank');
		}
		if($params['password'] != $params['password_confirm'])
		{
			throw new Model_UserPasswordException('Passwords are not the same');
		}
		if(self::is_existing_user($params['email'])){
			throw new Model_UserEmailException("Email '".$params['email']."' is already registered");
		}
	}

	private static function is_existing_user($email)
	{
		$user = self::get_user_by_email($email);
			
		if($user){
			return true;
		}
		return false;
	}

	private static function get_user_by_email($email)
	{
		return self::find()
			->where('email', '=', $email)
			->get_one();
	}

	private static function create_user($params)
	{
		Auth::create_user(
			$params['email'], 
			$params['password']
		);

		$user = self::get_user_by_email($params['email']);

		$user['name'] = $params['name'];
		$user->save();

		return $user;
	}

	public static function get_login_hash_for_login_details($params)
	{
		$successfull_login = Auth::login(
			$params['email'],
			$params['password']
		);

		if(!$successfull_login){
			throw new Model_UserLoginException('Invalid Email/Password combination');
		}

		$user = self::get_user_by_email(Auth::get_screen_name());
		$user->login_hash = Auth::create_login_hash();
		$user->save();

		return $user->login_hash;
	}

	public static function get_by_login_hash($hash)
	{
		$user = self::find()
			->where('login_hash', '=', $hash)
			->get_one();

		if(!$user){
			throw new Model_UserHashException("The user is logged out. Please login in again.");
		}

		return $user;
	}

	public function to_object()
	{
		$obj = (object)array();

		$obj->name = $this->name;
		$obj->login_hash = $this->login_hash;

		return $obj;
	}
}