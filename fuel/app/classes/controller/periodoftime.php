<?php

class Controller_Periodoftime extends BaseController_Loggedin
{
	public function action_view()
	{
		$datetime = new DateTime();
		$date = new DateTime($datetime->format('Y-m-d'));

		$times = $this->member->get_all_period_of_time_by_date($date);

		$this->template->body = View::forge('periodoftime/view', array(
			'day_date' => new Datetime(),
			'times' => $times
		));
	}

	public function action_add()
	{
		$this->template->body = View::forge('periodoftime/add', array(
			'projects' => $this->member->get_all_projects()
		));
	}

	public function action_add_post()
	{
		$project = $this->member->get_project_by_id(Input::post('project_id'));
		$minutes = Input::post('minutes');
		try{
			$time = Model_PeriodOfTime::init(array(
				'minutes' => $minutes
			));
			$project->add_periodoftime($time);

		}catch(Model_PeriodOfTimeException $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/periodoftime/add');
		}

		Response::redirect('/periodoftime/view');
	}

	public function action_delete($id)
	{
		$time = $this->member->get_periodotime_by_id($id);
		$time->delete();
		Response::redirect('/periodoftime/view');
	}
}