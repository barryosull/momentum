<?
/**
 * @group App
 * @group Project
 */
class Tests_Project extends \Fuel\Core\TestCase 
{
	private $project;

	public function setUp()
	{
		Model_Project::find()->delete();

		$this->project = Model_Project::init(array(
			'name' => 'Project name'
		));
	}

	public function test_get_project()
	{
		$project_again = Model_Project::get_by_id($this->project->get_id());

		$this->assertEquals(
			$this->project->get_id(),
			$project_again->get_id()
		);
	}

	/**
	 * @expectedException Model_ProjectException
	 */
	public function test_project_name_cant_be_blank()
	{
		$project = Model_Project::init(array(
			'name' => ''
		));
	}

	public function test_projects_can_have_same_name()
	{
		$project_same_name = Model_Project::init(array(
			'name' => 'Project name'
		));

		$this->assertNotEquals(
			$this->project->id,
			$project_same_name->id
		);
	}

	public function test_get_totaltime_for_date_range()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$this->project->add_periodoftime($time);

		$yesterday = new Datetime('-1 days');
		$tomorrow = new Datetime('+1 days');

		$total_time = $this->project->get_totaltime_for_date_range($yesterday, $tomorrow);

		$this->assertEquals(
			20,
			$total_time
		);
	}

	public function test_get_totaltime_for_outside_date_range()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$this->project->add_periodoftime($time);

		$_3_days_ago = new Datetime('-3 days');
		$_1_day_ago = new Datetime('-1 days');

		$total_time = $this->project->get_totaltime_for_date_range($_3_days_ago, $_1_day_ago);

		$this->assertEquals(
			0,
			$total_time
		);
	}

	public function test_delete_calls_remove_function_on_parent()
	{
		$mock_member = $this->getMock('Model_Member', array('remove_project'));
		$mock_member->expects($this->once())
                 	->method('remove_project');

		$this->project->member = $mock_member;
		
		$this->project->delete();
	}

	public function test_get_totaltime()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$time2 = Model_PeriodOfTime::init(array(
			'minutes' => 30
		));
		$this->project->add_periodoftime($time);
		$this->project->add_periodoftime($time2);

		$total_time = $this->project->get_totaltime();

		$this->assertEquals(
			50,
			$total_time
		);
	}

	public function test_project_is_initially_active()
	{
		$this->assertTrue($this->project->is_active());
	}

	public function test_project_can_be_set_to_inactive()
	{
		$this->project->deactivate();
		$this->assertFalse($this->project->is_active());
	}

	public function test_project_can_be_reactivated()
	{
		$this->project->deactivate();
		$this->project->activate();
		$this->assertTrue($this->project->is_active());
	}
}