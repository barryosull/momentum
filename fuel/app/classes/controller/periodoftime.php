<?php

class Controller_Periodoftime extends BaseController_Loggedin
{
	public function action_view($date_string = '')
	{
		$this->redirect_if_no_projects();

		$date = $this->create_date_from_user_input($date_string);

		$times = $this->member->get_all_period_of_time_by_date($date);

		$this->template->body = View::forge('periodoftime/view', array(
			'todays_date' => new DateTime('today'),
			'day_date' => $date,
			'times' => $times
		));
	}

	private function redirect_if_no_projects()
	{
		$projects = $this->member->get_all_projects();
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
			'day_date' => $date,
			'projects' => $this->member->get_all_projects()
		));
	}

	public function action_add_post()
	{
		$project = $this->member->get_project_by_id(Input::post('project_id'));
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

		}catch(Model_PeriodOfTimeException $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/periodoftime/add/'.$date->format('Y-m-d'));
		}

		Response::redirect('/periodoftime/view/'.$date->format('Y-m-d'));
	}

	public function action_delete($id)
	{
		$time = $this->member->get_periodotime_by_id($id);
		$time->delete();
		Response::redirect('/periodoftime/view');
	}
}