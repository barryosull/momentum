<?php

class Controller_Start extends Controller
{
	public function action_index()
	{
		return $this->load_all_views_as_templates();
	}

	private function load_all_views_as_templates()
	{
		$views = array(
			'auth/login',
			'auth/register',
			'header/loggedin',
			'header/login',
			'periodoftime/add',
			'periodoftime/view',
			'project/add',
			'project/timetotals',	
			'project/view',	
		);

		$data = array(
			'views'=>$views
		);

		$view = View::forge('template', $data);

		return Response::forge($view);
	}
}