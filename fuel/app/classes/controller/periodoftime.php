<?php

class Controller_Periodoftime extends Controller
{
	public function action_view()
	{
		$datetime = new DateTime();
		$date = new DateTime($datetime->format('Y-m-d'));

		$times = Model_PeriodOfTime::get_all_by_date($date);

		return View::forge('periodoftime/view', array(
			'day_date' => new Datetime(),
			'times' => $times
		));
	}

	public function action_add()
	{
		return View::forge('periodoftime/add', array(
			'projects' => Model_Project::get_all()
		));
	}

	public function action_add_post()
	{
		$project = Model_Project::get_by_id(Input::post('project_id'));
		$minutes = Input::post('minutes');
		try{
			$time = Model_PeriodOfTime::init(array(
				'project' => $project,
				'minutes' => $minutes
			));
		}catch(Model_PeriodOfTimeException $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/periodoftime/add');
		}

		Response::redirect('/periodoftime/view');
	}

	public function action_delete($id)
	{
		$project = Model_Project::get_by_id($id);
		$project->delete();
		Response::redirect('/project/view');
	}
}