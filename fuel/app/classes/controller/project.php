<?php

class Controller_Project extends Controller
{
	public function action_view()
	{
		$projects = Model_Project::get_all();

		return View::forge('project/view', array(
			'projects' => $projects
		));
	}

	public function action_add()
	{
		return View::forge('project/add');
	}

	public function action_add_post()
	{
		try{
			$project = Model_Project::init(array(
				'name' => Input::post('name')
			));
		}catch(Model_ProjectException $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/project/add');
		}

		Response::redirect('/project/view');
	}

	public function action_delete($id)
	{
		$project = Model_Project::get_by_id($id);
		$project->delete();
		Response::redirect('/project/view');
	}

}