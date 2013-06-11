<?php

class Controller_Periodoftime extends BaseController_ValidMember
{
	public function get_list()
	{
		$datetime = new DateTime();
		$date = new DateTime($datetime->format('Y-m-d'));

		$times = $this->member->get_all_period_of_time_by_date($date);
		$objs = $this->convert_objects($times);
		$this->respond($times);
	}

	public function post_list()
	{
		$project = $this->member->get_project_by_id(Input::post('project_id'));
		$minutes = Input::post('minutes');
		try{
			$time = Model_PeriodOfTime::init(array(
				'minutes' => $minutes
			));
			$project->add_periodoftime($time);

		}catch(Model_PeriodOfTimeException $e){
			return $this->error_respond($e->getMessage());
		}
		$this->respond();
	}

	public function action_delete($id)
	{
		$time = $this->member->get_periodotime_by_id($id);
		$time_data_before_deletion = $time->to_object();
		$time->delete();
		$this->respond($time_data_before_deletion);
	}
}