<?php

class Controller_Periodoftime extends BaseController_Loggedin
{
	protected $projects;

	public function before()
	{
		parent::before();
		$this->projects = $this->member->get_projects();
	}

	public function action_view($date_string = '')
	{
		$this->redirect_if_no_projects();

		$date = $this->create_date_from_user_input($date_string);

		$times = $this->projects->get_periodoftimes_by_date($date);

		$this->template->body = View::forge('periodoftime/view', array(
			'todays_date' => new DateTime('today'),
			'day_date' => $date,
			'times' => $times
		));
	}

	private function redirect_if_no_projects()
	{
		$projects = $this->projects->get_all();
		if(count($projects) == 0){
			Session::set_flash('message', "Looks like you haven't added any projects. Please add a project to start");
			Response::redirect('/project/add');
		}
	}

	private function create_date_from_user_input($date_string)
	{
		if($date_string == ''){
			return new DateTime('today');
		}
		return new DateTime($date_string);
	}

	public function action_add($date_string = '')
	{
		$date = $this->create_date_from_user_input($date_string);

		$this->template->body = View::forge('periodoftime/add', array(
			'todays_date' => new DateTime('today'),
			'most_recent_periodoftime' => $this->projects->get_most_recent_periodoftime(),
			'day_date' => $date,
			'projects' => $this->projects->get_active()
		));
	}

	public function action_add_post()
	{
		$project = $this->projects->get_by_id(Input::post('project_id'));
		$minutes = Input::post('minutes');
		$hours = Input::post('hours');
		$date = $this->create_date_from_user_input(Input::post('date'));

		$total_minutes = $minutes + ($hours * 60);

		try{
			$time = Model_PeriodOfTime::init(array(
				'minutes' => $total_minutes,
				'date' => $date
			));
			$project->add_periodoftime($time);

		}catch(Exception_Input $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/periodoftime/add/'.$date->format('Y-m-d'));
		}

		Response::redirect('/periodoftime/view/'.$date->format('Y-m-d'));
	}

	public function action_delete($id)
	{
		$time = $this->projects->get_periodotime_by_id($id);
		$time->delete();
		Response::redirect('/periodoftime/view');
	}
}