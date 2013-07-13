<?
/**
 * @group App
 * @group Member
 */
class Tests_Member extends \Fuel\Core\TestCase 
{
	protected $member;

	public function setUp()
	{
		Model_Member::find()->delete();
		Model_User::find()->delete();

		$this->member = Model_Member::init(array(
			'name'=>'name',
			'email'=>'email@email.com',
			'password'=>'password',
			'password_confirm'=>'password',
		));
	}

	/**
	 * @expectedException Model_MemberUserException
	 */
	public function test_init_exceptions_are_returned_as_member_exception()
	{
		$this->member = Model_Member::init(array(
			'name'=>'',
			'email'=>'email@email.com',
			'password'=>'password',
			'password_confirm'=>'password',
		));
	}

	/**
	 * @expectedException Model_MemberUserException
	 */
	public function test_logout_exceptions()
	{
		Model_Member::logout();
	}

	public function test_create_member()
	{
		$user = $this->member->user;

		$this->assertEquals(
			'name',
			$user->name
		);
	}	

	public function test_get_logged_in_member()
	{
		Model_Member::login(array(
			'email'=>'email@email.com',
			'password'=>'password'
		));

		$member = Model_Member::get_logged_in_member();

		$this->assertEquals(
			$this->member->id,
			$member->id
		);
	}

	/**
	 * @expectedException Model_MemberUserException
	 */
	public function test_login_exceptions_are_returned_as_member_exception()
	{
		Model_Member::login(array(
			'email'=>'email@email.com',
			'password'=>'not the right password'
		));
	}

	public function test_logout()
	{
		Model_Member::login(array(
			'email'=>'email@email.com',
			'password'=>'password'
		));

		Model_Member::logout();
	}

	public function test_login_while_logged_in_causes_member_changeover()
	{
		Model_Member::login(array(
			'email'=>'email@email.com',
			'password'=>'password'
		));
		$changeover_member = Model_Member::init(array(
			'name'=>'name2',
			'email'=>'email2@email.com',
			'password'=>'password',
			'password_confirm'=>'password',
		));

		Model_Member::login(array(
			'email'=>'email2@email.com',
			'password'=>'password'
		));

		$member_logged_in = Model_Member::get_logged_in_member();
		$this->assertEquals(
			$changeover_member->id,
			$member_logged_in->id
		);
	}

	public function test_get_projects_returns_collection()
	{
		$projects = $this->member->get_projects();

		$this->assertEquals('Model_ProjectCollection', get_class($projects));
	}

	public function test_projectcollection_is_configured_correctly()
	{
		$projects = $this->member->get_projects();
		$project = Model_Project::init(array(
			'name' => 'Project Name'
		));
		$project->member_id = $this->member->id;
		$project->save();

		$this->assertEquals(
			$project->id, 
			$projects->get_by_id($project->id)->id
		);
	}
}