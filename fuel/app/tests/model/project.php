<?
/**
 * @group App
 * @group Project
 */
class Tests_Project extends \Fuel\Core\TestCase 
{
	public function setUp()
	{
		Model_Project::find()->delete();
	}

	public function test_create_project()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));
	}

	public function test_get_project()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));

		$project_again = Model_Project::get_by_id($project->get_id());

		$this->assertEquals(
			$project->get_id(),
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
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));

		$project_same_name = Model_Project::init(array(
			'name' => 'Project name'
		));

		$this->assertNotEquals(
			$project->id,
			$project_same_name->id
		);
	}

	public function test_get_totaltime_for_date_range()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$project->add_periodoftime($time);

		$yesterday = new Datetime('-1 days');
		$tomorrow = new Datetime('+1 days');

		$total_time = $project->get_totaltime_for_date_range($yesterday, $tomorrow);

		$this->assertEquals(
			20,
			$total_time
		);
	}

	public function test_get_totaltime_for_outside_date_range()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$project->add_periodoftime($time);

		$_3_days_ago = new Datetime('-3 days');
		$_1_day_ago = new Datetime('-1 days');

		$total_time = $project->get_totaltime_for_date_range($_3_days_ago, $_1_day_ago);

		$this->assertEquals(
			0,
			$total_time
		);
	}

	public function test_delete_calls_remove_function_on_parent()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));

		$mock_member = $this->getMock('Model_Member', array('remove_project'));
		$mock_member->expects($this->once())
                 	->method('remove_project');

		$project->member = $mock_member;
		
		$project->delete();
	}

	public function test_get_totaltime()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$time2 = Model_PeriodOfTime::init(array(
			'minutes' => 30
		));
		$project->add_periodoftime($time);
		$project->add_periodoftime($time2);

		$total_time = $project->get_totaltime();

		$this->assertEquals(
			50,
			$total_time
		);
	}

	public function test_to_object()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
		$time2 = Model_PeriodOfTime::init(array(
			'minutes' => 30
		));
		$project->add_periodoftime($time);
		$project->add_periodoftime($time2);

		$obj = $project->to_object();

		$this->assertEquals($project->id, $obj->id);
		$this->assertEquals($project->name, $obj->name);
		$this->assertEquals($project->get_totaltime(), $obj->totaltime);
	}
}