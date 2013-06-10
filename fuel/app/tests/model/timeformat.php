<?
/**
 * @group App
 * @group TimeFormat
 */
class Tests_TimeFormat extends \Fuel\Core\TestCase 
{
	public function test_mins_to_string()
	{
		$this->assertEquals(
			'30mins',
			Model_TimeFormat::mins_to_string(30)
		);
	}

	public function test_mins_to_string_with_hour()
	{
		$this->assertEquals(
			'1hr 22mins',
			Model_TimeFormat::mins_to_string(82)
		);
	}

	public function test_mins_to_string_for_hour_exactly()
	{
		$this->assertEquals(
			'1hr',
			Model_TimeFormat::mins_to_string(60)
		);
	}

	/**
	 *@expectedException Model_TimeFormatException
	 *@expectedExceptionMessage Negative numbers cannot be used
	 */
	public function test_breaks_when_negative_number()
	{
		Model_TimeFormat::mins_to_string(-12);
	}

	/**
	 *@expectedException Model_TimeFormatException
	 *@expectedExceptionMessage Argument is not a number
	 */
	public function test_breaks_when_number_not_passed()
	{
		Model_TimeFormat::mins_to_string(array());
	}

	public function test_cant_handle_strings()
	{
		$this->assertEquals(
			'22mins',
			Model_TimeFormat::mins_to_string('22')
		);
	}
}