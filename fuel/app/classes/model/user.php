<?php

class Model_UserException extends Exception {}

class Model_UserLoginException extends Model_UserException {}
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
		$params['group'] = 1;
		$params['profile_fields'] = '{}';

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

		$user = self::find()
			->where('email', '=', $params['email'])
			->get_one();

		if($user){
			throw new Model_UserEmailException("Email '".$params['email']."' is already registered");
		}

		Auth::create_user(
			$params['email'], 
			$params['password']
		);

		$user = self::find()
			->where('email', '=', $params['email'])
			->get_one();

		$user['name'] = $params['name'];
		$user->save();

		return $user;
	}

	public static function login($params)
	{
		$successfull_login = Auth::login(
			$params['email'],
			$params['password']
		);

		if(!$successfull_login){
			throw new Model_UserLoginException('Invalid Email/Password combination');
		}
	}

	public static function get_logged_in_user()
	{
		$user_query = self::find()
			->where('email', '=', Auth::get_screen_name());

		if($user_query->count() == 0){
			throw new Model_UserLoginException('No logged in user');
		}
		return $user_query->get_one();
	}
}