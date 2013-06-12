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

	public function post_register()
	{
		try{
			$member = Model_Member::init(array(
				'name'=>Input::post('name'),
				'email'=>Input::post('email'),
				'password'=>Input::post('password'),
				'password_confirm'=>Input::post('password_confirm')
			));
			$this->post_login();

		}catch(Model_MemberException $e){
			$this->error_respond($e->getMessage());
		}
	}
}