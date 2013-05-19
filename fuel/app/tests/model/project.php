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

	/**
	 * @expectedException Model_ProjectException
	 */
	public function test_cant_have_duplicate_tests()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));

		$project_same_name = Model_Project::init(array(
			'name' => 'Project name'
		));
	}

	public function test_get_all()
	{
		$project = Model_Project::init(array(
			'name' => 'Project name'
		));
		$project2 = Model_Project::init(array(
			'name' => 'Project name2'
		));

		$projects = Model_Project::get_all();
		$first = current($projects);
		$second = next($projects);

		$this->assertEquals(2, count($projects));
		$this->assertEquals('Project name', $first->name);
		$this->assertEquals('Project name2', $second->name);
	}
}