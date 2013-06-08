<?php

class BaseController_Template extends Controller_Template
{
	public function before()
	{
		parent::before();
		$this->prepare_template();
	}

	private function prepare_template()
    {
        $this->template->header = View::forge('template/header');
        $this->template->footer = View::forge('template/footer');
    }
}