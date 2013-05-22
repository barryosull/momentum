<?
/**
 * @group App
 * @group PeriodOfTime
 */
class Tests_PeriodOfTime extends \Fuel\Core\TestCase 
{
	protected $project;

	public function setUp()
	{
		Model_Project::find()->delete();
		Model_PeriodOfTime::find()->delete();

		$this->project = Model_Project::init(array(
			'name' => 'Project name'
		));
	}

	public function test_create_periodoftime()
	{
		$time = Model_PeriodOfTime::init(array(
			'project' => $this->project,
			'minutes' => 20
		));

		$time_again = Model_PeriodOfTime::get_by_id($time->get_id());

		$this->assertEquals(
			$time->get_id(),
			$time_again->get_id()
		);
		$this->assertEquals(
			$time->minutes,
			$time_again->minutes
		);
		$this->assertEquals(
			$time->minutes,
			$time_again->minutes
		);
	}

	/**
	 * @expectedException Model_PeriodOfTimeException
	 */
	public function test_create_fails_when_no_project()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20
		));
	}

	/**
	 * @expectedException Model_PeriodOfTimeException
	 */
	public function test_project_must_be_correct_type()
	{
		$bad_project = (object)array();
		$bad_project->id = 1;

		$time = Model_PeriodOfTime::init(array(
			'project' => $bad_project,
			'minutes' => 20
		));
	}

	/**
	 * @expectedException Model_PeriodOfTimeException
	 */
	public function test_minutes_cant_be_less_than_zero()
	{
		$time = Model_PeriodOfTime::init(array(
			'project' => $this->project,
			'minutes' => -10
		));
	}

	/**
	 * @expectedException Model_PeriodOfTimeException
	 */
	public function test_minutes_cant_be_zero()
	{
		$time = Model_PeriodOfTime::init(array(
			'project' => $this->project,
			'minutes' => 0
		));
	}

	public function test_get_all_for_date()
	{
		$date = new DateTime();

		$times = Model_PeriodOfTime::get_all_for_date($date);
		$this->assertEquals(0, count($times));
		
		$time = Model_PeriodOfTime::init(array(
			'project' => $this->project,
			'minutes' => 10
		));
		$times = Model_PeriodOfTime::get_all_for_date($date);
		$this->assertEquals(1, count($times));

		$time = Model_PeriodOfTime::init(array(
			'project' => $this->project,
			'minutes' => 10
		));
		$times = Model_PeriodOfTime::get_all_for_date($date);
		$this->assertEquals(2, count($times));
	}
}