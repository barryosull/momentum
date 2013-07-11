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

	public function test_remove_project()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$this->member->add_project($project);
		
		$this->member->remove_project($project);
		
		$projects = $this->member->get_all_projects();
		$this->assertEquals(0, count($projects));
	}

	public function test_get_all_period_of_time_by_date_doesnt_break_when_project_is_deleted()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$project->add_periodoftime($time);
		$this->member->add_project($project);

		$project->delete();

		$datetime = new DateTime();
		$date = new DateTime($datetime->format('Y-m-d'));

		$times = $this->member->get_all_period_of_time_by_date($date);
		
		$this->assertEquals(0, count($times));
	}

	public function test_get_periodoftime_by_id()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$project->add_periodoftime($time);
		$this->member->add_project($project);
		
		$time_again = $this->member->get_periodotime_by_id($time->id);

		$this->assertEquals($time->id, $time_again->id);
	}

	public function test_get_most_recent_periodoftime()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$project->add_periodoftime($time);
		$this->member->add_project($project);

		$projectb = Model_Project::init(array(
			'name'=>'projectb'
		));
		$timeb = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$projectb->add_periodoftime($timeb);
		$this->member->add_project($projectb);

		$last_entered = $this->member->get_most_recent_periodoftime();

		$this->assertEquals($timeb->id, $last_entered->id);
	}


	public function get_most_recent_periodoftime_returns_null_when_member_has_no_times()
	{
		$project = Model_Project::init(array(
			'name'=>'project'
		));
		$this->member->add_project($project);

		$last_entered = $this->member->get_most_recent_periodoftime();

		$this->assertNull($last_entered);
	}

	/**
	 * @expectedException Model_MemberPeriodoftimeMismatchException
	 * @expectedExceptionMessage PeriodOfTime does not belong to member
	 */
	public function test_cant_get_periodoftime_for_another_period_of_time()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		
		$time_again = $this->member->get_periodotime_by_id($time->id);
	}

	/**
	 * @expectedException Model_MemberPeriodoftimeNotFoundException
	 * @expectedExceptionMessage PeriodOfTime could not be found
	 */
	public function test_cant_get_nonexistant_periodoftime()
	{	
		$time = $this->member->get_periodotime_by_id(123);
	}
}