<?php

class BaseController_ValidMember extends BaseController_Rest
{
	protected $member;

	public function before()
	{
		parent::before();

		$hash = Input::get('hash');

		try{
			$this->member = Model_Member::get_by_login_hash($hash);
		}catch(Model_UserLoginException $e){
			$this->error_respond($e->getMessage());
		}
	}
}