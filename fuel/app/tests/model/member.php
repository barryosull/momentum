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
		Model_Project::find()->delete();
		Model_PeriodOfTime::find()->delete();

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

	/**
	 * @expectedException Fuel\Core\PhpErrorException
	 */ 
	public function test_add_project_requires_proper_model()
	{
		$this->member->add_project(array());
	}

	/**
	 * @expectedException Model_MemberProjectException
	 * @expectedExceptionMessage Member already has this project
	 */
	public function test_member_cannot_have_multiple_versions_of_same_project()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));

		$this->member->add_project($project);
		$this->member->add_project($project);
	}
	
	public function test_get_all_projects_is_initially_empty()
	{
		$projects = $this->member->get_all_projects();

		$this->assertEquals(0, count($projects));
	}

	public function test_get_all_projects_returns_array_of_projects()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$this->member->add_project($project);

		$projects = $this->member->get_all_projects();
		
		$project_again = current($projects);
		$this->assertEquals($project, $project_again);
	}

	public function test_get_all_projects_returns_all()
	{
		$projecta = Model_Project::init(array(
			'name'=>'projecta'
		));
		$projectb = Model_Project::init(array(
			'name'=>'projectb'
		));
		$this->member->add_project($projecta);
		$this->member->add_project($projectb);

		$projects = $this->member->get_all_projects();

		$this->assertEquals(2, count($projects));
	}

	/**
	 * @expectedException Model_MemberMissingParamException
	 * @expectedExceptionMessage Model_PeriodOfTime param is missing
	 */
	public function test_add_period_of_time_requires_proper_model()
	{
		$this->member->add_period_of_time(new DateTime());
	}
	
	public function test_get_all_period_of_time_by_date_returns_period_of_time()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$time = Model_PeriodOfTime::init(array(
			'project' => $project,
			'minutes' => 20
		));
		$this->member->add_period_of_time($time);

		$datetime = new DateTime();
		$date = new DateTime($datetime->format('Y-m-d'));

		$times = $this->member->get_all_period_of_time_by_date($date);
		
		$time_again = current($times);
		$this->assertEquals($time, $time_again);
	}

	public function test_get_project_by_id()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$this->member->add_project($project);
		
		$project_again = $this->member->get_project_by_id($project->id);

		$this->assertEquals($project->id, $project_again->id);
	}


	/**
	 * @expectedException Model_MemberProjectMismatchException
	 * @expectedExceptionMessage Project does not belong to member
	 */
	public function test_cant_get_project_that_doesnt_belong()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$this->member->add_project($project);
		$member_without_project = Model_Member::init(array(
			'name'=>'Roberto',
			'email'=>'email2@email.com',
			'password'=>'password',
			'password_confirm'=>'password',
		));

		$member_without_project->get_project_by_id($project->id);
	}

	/**
	 * @expectedException Model_MemberProjectNotFoundException
	 * @expectedExceptionMessage Project does not exist
	 */
	public function test_cant_get_project_that_doesnt_exist()
	{
		$this->member->get_project_by_id(1);
	}

	/**
	 * @expectedException Model_MemberProjectDuplicateException
	 * @expectedExceptionMessage Member has project with this name already
	 */
	public function test_cant_have_project_with_same_name()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$this->member->add_project($project);

		$project2 = Model_Project::init(array(
			'name'=>'project'
		));
		$this->member->add_project($project2);
	}
}