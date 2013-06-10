<?php

class BaseController_Loggedin extends BaseController_Template
{
	protected $member;
	private $active_uri;

	public function before()
	{
		parent::before();

		try{
			$this->member = Model_Member::get_logged_in_member();
		}catch(Model_UserLoginException $e){
			Response::redirect('/auth/login');
		}

		$active_uri = Uri::string();

		$this->template->header = View::forge('template/headerloggedin', array(
			'member'=>$this->member,
			'active'=>$active_uri,
		));
	}
}