<?
/**
 * @group App
 * @group User
 */
class Tests_User extends \Fuel\Core\TestCase 
{
	public function setUp()
	{
		Model_User::find()->delete();
	}

	public function test_create_user()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		$this->assertEquals('Barry', $user->name);
		$this->assertEquals('email@email.com', $user->email);
	}

	public function test_user_can_login()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		Model_User::login(array(
			'email'=>'email@email.com',
			'password'=>'password'
		));

		$user_again = Model_User::get_logged_in_user();

		$this->assertEquals(
			$user->id,
			$user_again->id
		);
	}

	/**
	 * @expectedException Model_UserLoginException
	 * @expectedExceptionMessage Invalid Email/Password combination
	 */
	public function test_bad_login_for_nonexistant_user()
	{
		Model_User::login(array(
			'email'=>'does_not_exist',
			'password'=>'password'
		));
	}

	/**
	 * @expectedException Model_UserLoginException
	 * @expectedExceptionMessage Invalid Email/Password combination
	 */
	public function test_bad_login_for_existing_user()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		Model_User::login(array(
			'email'=>'wrong_email',
			'password'=>'password'
		));
	}

	/**
	 * @expectedException Model_UserLoginException
	 * @expectedExceptionMessage No logged in user
	 */
	public function test_get_loggedin_user_fails_when_no_user()
	{
		Model_User::get_logged_in_user();
	}

	/**
	 * @expectedException Model_UserLoginException
	 * @expectedExceptionMessage No logged in user
	 */
	public function test_logout()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));
		Model_User::login(array(
			'email'=>'email@email.com',
			'password'=>'password'
		));

		Model_User::logout();

		Model_User::get_logged_in_user();
	}

	/**
	 * @expectedException Model_UserLogoutException
	 * @expectedExceptionMessage No logged in user to logout
	 */
	public function test_logout_when_not_logged_in_errors()
	{
		Model_User::logout();
	}

	/**
	 * @expectedException Model_UserNameException
	 * @expectedExceptionMessage Name cannot be blank
	 */
	public function test_name_cannot_be_blank()
	{
		$user = Model_User::init(array(
			'name' => '',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));
	}


	/**
	 * @expectedException Model_UserEmailException
	 * @expectedExceptionMessage Email 'email@email.com' is already registered
	 */
	public function test_email_must_be_unqiue()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		$user = Model_User::init(array(
			'name' => 'Barry2',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));
	}

	/**
	 * @expectedException Model_UserEmailException
	 * @expectedExceptionMessage Email cannot be blank
	 */
	public function test_email_cannot_be_blank()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => '',
			'password' => 'password',
			'password_confirm' => 'password',
		));
	}

	/**
	 * @expectedException Model_UserPasswordException
	 * @expectedExceptionMessage Password cannot be blank
	 */
	public function test_password_cannot_be_blank()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => '',
			'password_confirm' => '',
		));
	}

	/**
	 * @expectedException Model_UserPasswordException
	 * @expectedExceptionMessage Passwords are not the same
	 */
	public function test_passwords_must_be_equal()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'not same password',
		));
	}


}