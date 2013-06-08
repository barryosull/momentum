<?php

class BaseController_Loggedin extends BaseController_Template
{
	protected $member;

	public function before()
	{
		parent::before();

		try{
			$this->member = Model_Member::get_logged_in_member();
		}catch(Model_UserLoginException $e){
			Response::redirect('/auth/login');
		}

		$this->template->header = View::forge('template/headerloggedin');
	}
}