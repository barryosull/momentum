<?
/**
 * @group App
 * @group ProjectCollection
 */
class Tests_ProjectCollection extends \Fuel\Core\TestCase 
{
    protected $collection;
    protected $projecta;
    protected $projectb;
    protected $time;

    public function setUp()
    {
        Model_Project::find()->delete();
        Model_PeriodOfTime::find()->delete();

        $this->projecta = Model_Project::init(array(
            'name' => 'Project a'
        ));
        $this->time = Model_PeriodOfTime::init(array(
            'minutes' => 20
        ));
        $this->projecta->add_periodoftime($this->time);

        $this->projectb = Model_Project::init(array(
            'name' => 'Project b'
        ));
        $this->projectb->deactivate();

        $parent_member_id = 2;
        $this->collection = new Model_ProjectCollection($parent_member_id);
    }

    /**
     * @expectedException Model_ProjectCollectionException
     * @expectedExceptionMessage Member already has this project
     */
    public function test_member_cannot_have_multiple_versions_of_same_project()
    {
        $this->collection->add($this->projecta);
        $this->collection->add($this->projecta);
    }
    
    public function test_get_all_projects_is_initially_empty()
    {
        $projects = $this->collection->get_all();

        $this->assertEquals(0, count($projects));
    }

    public function test_get_all_projects_returns_array_of_projects()
    {
        $this->collection->add($this->projecta);

        $projects = $this->collection->get_all();
        
        $project_again = current($projects);
        $this->assertEquals($this->projecta->id, $project_again->id);
    }

    public function test_get_all_projects_returns_all()
    {
        $this->collection->add($this->projecta);
        $this->collection->add($this->projectb);

        $projects = $this->collection->get_all();

        $this->assertEquals(2, count($projects));
    }

    public function test_get_all_projects_returns_alphabetical()
    {
        $this->collection->add($this->projectb);
        $this->collection->add($this->projecta);

        $projects = $this->collection->get_all();
        $first = current($projects);
        $second = next($projects);

        $this->assertEquals('Project a', $first->name);
        $this->assertEquals('Project b', $second->name);
    }
    
    public function test_get_active_projects_only_returns_active_projects()
    {
        $this->collection->add($this->projectb);
        $this->collection->add($this->projecta);

        $projects = $this->collection->get_active();
        $first = current($projects);

        $this->assertEquals('Project a', $first->name);
        $this->assertEquals(1, count($projects));
    }

    public function test_get_all_periodoftime_by_date_returns_period_of_time()
    {
        $this->collection->add($this->projecta);

        $datetime = new DateTime();
        $date = new DateTime($datetime->format('Y-m-d'));

        $times = $this->collection->get_periodoftimes_by_date($date);
        
        $time_again = current($times);
        $this->assertEquals($this->time->id, $time_again->id);
    }

    public function test_get_all_periodoftime_by_date_only_works_for_date()
    {
        $this->collection->add($this->projecta);

        $this->time->created_at = time() - (86400 * 3);
        $this->time->save();
    
        $date = new DateTime('today');

        $times = $this->collection->get_periodoftimes_by_date($date);

        $this->assertEquals(0, count($times));
    }

    public function test_get_project_by_id()
    {        
        $this->collection->add($this->projecta);

        $project_again = $this->collection->get_by_id($this->projecta->id);

        $this->assertEquals($this->projecta->id, $project_again->id);
    }

    /**
     * @expectedException Model_ProjectCollectionException
     * @expectedExceptionMessage Project does not exist in collection
     */
    public function test_cant_get_project_that_doesnt_belong()
    {
        $this->collection->get_by_id($this->projecta->id);
    }

    public function test_get_all_period_of_time_by_date_doesnt_break_when_project_is_deleted()
    {
        $this->collection->add($this->projecta);

        $this->projecta->delete();

        $datetime = new DateTime();
        $date = new DateTime($datetime->format('Y-m-d'));

        $times = $this->collection->get_periodoftimes_by_date($date);
        
        $this->assertEquals(0, count($times));
    }

    public function test_get_most_recent_periodoftime()
    {
        $this->collection->add($this->projecta);
        $this->collection->add($this->projectb);

        $timeb = Model_PeriodOfTime::init(array(
            'minutes' => 20
        ));
        $this->projecta->add_periodoftime($timeb);

        $last_entered = $this->collection->get_most_recent_periodoftime();

        $this->assertEquals($timeb->id, $last_entered->id);
    }


    public function get_most_recent_periodoftime_returns_null_when_member_has_no_times()
    {
        $last_entered = $this->collection->get_most_recent_periodoftime();

        $this->assertNull($last_entered);
    }

    public function test_get_periodoftime_by_id()
    {
        $this->collection->add($this->projecta);
        
        $time_again = $this->collection->get_periodotime_by_id($this->time->id);

        $this->assertEquals($this->time->id, $time_again->id);
    }

    /**
     * @expectedException Model_ProjectCollectionException
     * @expectedExceptionMessage PeriodOfTime does not exist in collection
     */
    public function test_cant_get_nonexistant_periodoftime()
    {   
        $time = $this->collection->get_periodotime_by_id(123);
    }
}
