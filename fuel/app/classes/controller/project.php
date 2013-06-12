<?php

class Controller_Project extends BaseController_ValidMember
{
	public function get_list()
	{
		$projects = $this->member->get_all_projects();
		$objs = $this->convert_models_to_data_objects($projects);
		$this->respond($objs);
	}
	
	public function post_list()
	{
		try{
			$project = Model_Project::init(array(
				'name' => Input::post('name')
			));
			$this->member->add_project($project);
		}catch(Model_ProjectException $e){
			return $this->error_respond($e->getMessage());
		}
		$this->respond();
	}
	
	public function action_delete($id)
	{
		$project = $this->member->get_project_by_id($id);
		$project_data_before_deletion = $project->to_object();
		$project->delete();
		$this->respond($project_data_before_deletion);
	}

	public function action_timetotals($start_date = '')
	{
		if($start_date != ''){
			$week_start = new DateTime($start_date);
			$week_end = clone $week_start;
			$week_end->modify('+7 days');
		}else{
			$week_start = new DateTime('Sunday last week');
			$week_end = new DateTime('Sunday this week');
		}

		$week_prev = clone $week_start;
		$week_prev->modify('-1 weeks');

		$week_next = clone $week_start;
		$week_next->modify('+1 weeks');
		
		$result = (object)array();
		$result->week_start = $week_start->format('d/m/Y');
		$result->week_end = $week_end->format('d/m/Y');
		$result->week_prev = $week_prev->format('Y-m-d');
		$result->week_next = $week_next->format('Y-m-d');
		$result->projects = array();

		$projects = $this->member->get_all_projects();
		
		foreach($projects as $project){
			$obj = $project->to_object();
			$obj->timetotal_for_range = $project->get_totaltime_for_date_range($week_start, $week_end);
			$result->projects[] = $obj;
		}

		$this->respond($result);
	}
}