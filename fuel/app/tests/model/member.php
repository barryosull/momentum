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
}