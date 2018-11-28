<?php
class UserCommonAction extends CommonAction
{
	protected function _initialize()
	{
        $this->credential = $_SESSION[parent::SESSION_USER_AUTH_KEY];

        if(!isset($this->credential))
        {
            $this->redirect("/Login");
            exit;
        }

		$this->name = $_SESSION[parent::SESSION_USER_AUTH_KEY]["name"];
    }
}
