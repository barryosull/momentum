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
			'email' => 'email',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		$this->assertEquals('Barry', $user->name);
		$this->assertEquals('email', $user->email);
	}

	public function test_user_can_login()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		Model_User::login(array(
			'email'=>'barry',
			'password'=>'password'
		));

		$user_again = Model_User::get_loggedin_user();

		$this->assertEquals(
			$user->id,
			$user_again->id
		);
	}

	/**
	 * @expectedException Model_UserLoginException
	 * @expectedExceptionMessage Invalid Email/Password combination
	 */
	public function test_bad_login_fails()
	{
		Model_User::login(array(
			'email'=>'barry',
			'password'=>'password'
		));
	}

	/**
	 * @expectedException Model_UserLoginException
	 * @expectedExceptionMessage No logged in user
	 */
	public function test_get_loggedin_user_fails_when_no_user()
	{
		Model_User::get_loggedin_user();
	}

	/**
	 * @expectedException Model_UserNameException
	 * @expectedExceptionMessage Name cannot be blank
	 */
	public function test_name_cannot_be_blank()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		$user = Model_User::init(array(
			'name' => 'Barry2',
			'email' => 'email',
			'password' => 'password',
			'password_confirm' => 'password',
		));
	}


	/**
	 * @expectedException Model_UserEmailException
	 * @expectedExceptionMessage Email 'email' is already registered
	 */
	public function test_email_must_be_unqiue()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		$user = Model_User::init(array(
			'name' => 'Barry2',
			'email' => 'email',
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
			'email' => 'email',
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
			'email' => 'email',
			'password' => 'password',
			'password_confirm' => 'not same password',
		));
	}
}