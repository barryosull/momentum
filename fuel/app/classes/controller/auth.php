<?php

class Controller_Auth extends BaseController_Rest
{
	public function post_login()
	{
		try{
			$hash = Model_Member::get_login_hash_for_login_details(array(
				'email'=>Input::post('email'),
				'password'=>Input::post('password')
			));
			$user = Model_Member::get_by_login_hash($hash)->user;
			$this->respond($user->to_object());
		}catch(Model_MemberException $e){
			$this->error_respond($e->getMessage());
		}
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

	public function get_logout()
	{
		Model_Member::logout();
		Response::redirect('/project/add');
	}
}