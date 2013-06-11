<?php

class Controller_Project extends BaseController_ValidMember
{
	public function get_list()
	{
		$projects = $this->member->get_all_projects();
		$objs = $this->convert_objects($projects);
		$this->respond($objs);
	}
	
	public function post_list()
	{
		try{
			$project = Model_Project::init(array(
				'name' => Input::post('name')
			));
			$this->member->add_project($project);
		}catch(Model_MemberException $e){
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

	/*
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
		
		$last_week_start = clone $week_start;
		$last_week_start->modify('-7 days');
		
		$next_week_start = clone $week_start;
		$next_week_start->modify('+7 days');

		$data = array(
			'projects'=>$this->member->get_all_projects(),
			'week_start'=>$week_start,
			'week_end'=>$week_end,
			'last_week_start'=>$last_week_start,
			'next_week_start'=>$next_week_start,
		);
		$this->template->body = View::forge('project/timetotals', $data);
	}
	*/
}