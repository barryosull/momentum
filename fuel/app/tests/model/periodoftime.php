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
	}

	public function test_create_date_can_be_set()
	{
		$date = new DateTime('2010-01-01');

		$time = Model_PeriodOfTime::init(array(
			'minutes' => 20,
			'date'=> $date
		));

		$this->assertEquals(
			$date->getTimestamp(),
			$time->get_created_at()->getTimestamp()
		);
	}

	/**
	 * @expectedException Model_PeriodOfTimeException
	 */
	public function test_minutes_cant_be_less_than_zero()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => -10
		));
	}

	/**
	 * @expectedException Model_PeriodOfTimeException
	 */
	public function test_minutes_cant_be_zero()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 0
		));
	}

	public function test_get_by_id()
	{
		$time = Model_PeriodOfTime::init(array(
			'minutes' => 2
		));
		$time_again = Model_PeriodOfTime::get_by_id($time->get_id());

		$this->assertEquals(
			$time->get_id(),
			$time_again->get_id()
		);
	}
}