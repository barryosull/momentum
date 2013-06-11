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

	public function test_get_user_by_loginhash()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));
		$login_hash = Model_User::get_login_hash_for_login_details(array(
			'email'=>'email@email.com',
			'password'=>'password'
		));
		
		$user_by_hash = Model_User::get_by_login_hash(
			$login_hash
		);

		$this->assertEquals($user->id, $user_by_hash->id);
	}

	/**
	 * @expectedException Model_UserHashException
	 * @expectedExceptionMessage The user is logged out. Please login in again.
	 */
	public function test_bad_get_user_by_loginhash()
	{
		$user_by_hash = Model_User::get_by_login_hash(
			'dasfasdfasdf'
		);
	}

	/**
	 * @expectedException Model_UserLoginException
	 * @expectedExceptionMessage Invalid Email/Password combination
	 */
	public function test_bad_login_for_nonexistant_user()
	{
		Model_User::get_login_hash_for_login_details(array(
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

		Model_User::get_login_hash_for_login_details(array(
			'email'=>'wrong_email',
			'password'=>'password'
		));
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

	public function test_as_object()
	{
		$user = Model_User::init(array(
			'name' => 'Barry',
			'email' => 'email@email.com',
			'password' => 'password',
			'password_confirm' => 'password',
		));

		$to_object = $user->to_object();

		$this->assertEquals('Barry', $to_object->name);
		$this->assertObjectHasAttribute('login_hash', $to_object);
	}
}