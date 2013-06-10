<?php

class Controller_Project extends BaseController_Loggedin
{
	public function action_view()
	{
		$projects = $this->member->get_all_projects();
		$this->template->body = View::forge('project/view', array(
			'projects' => $projects
		));
	}

	public function action_add()
	{
		$this->template->body = View::forge('project/add');
	}

	public function action_add_post()
	{
		try{
			$project = Model_Project::init(array(
				'name' => Input::post('name')
			));
			$this->member->add_project($project);
		}catch(Model_MemberException $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/project/add');
		}

		Response::redirect('/project/view');
	}

	public function action_delete($id)
	{
		$project = $this->member->get_project_by_id($id);
		$project->delete();
		Response::redirect('/project/view');
	}

	public function action_timetotals($start_date = '')
	{
		if($start_date != ''){
			$week_start = new DateTime($start_date);
			$week_end = clone $week_start;
			$week_end->modify('+7 days');
		}else{
			$week_start = new DateTime('Monday last week');
			$week_end = new DateTime('Monday this week');
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
}