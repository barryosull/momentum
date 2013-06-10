<?php

class Controller_Auth extends BaseController_Template
{
	public function action_index()
	{
		$this->action_login();
	}

	public function action_login()
	{
		$this->template->body = View::forge('auth/login');
	}

	public function action_login_post()
	{
		try{
			Model_Member::login(array(
				'email'=>Input::post('email'),
				'password'=>Input::post('password')
			));
		}catch(Model_MemberException $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/auth/login');
		}

		Response::redirect('/periodoftime/view');
	}

	public function action_register()
	{
		$this->template->body = View::forge('auth/register');
	}

	public function action_register_post()
	{
		try{
			$member = Model_Member::init(array(
				'name'=>Input::post('name'),
				'email'=>Input::post('email'),
				'password'=>Input::post('password'),
				'password_confirm'=>Input::post('password_confirm')
			));
		}catch(Model_MemberException $e){
			Session::set_flash('error', $e->getMessage());
			Response::redirect('/auth/register');
		}

		Model_Member::login(array(
			'email'=>Input::post('email'),
			'password'=>Input::post('password')
		));

		Response::redirect('/project/add');
	}

	public function action_logout()
	{
		Model_Member::logout();
		Response::redirect('/project/add');
	}
}