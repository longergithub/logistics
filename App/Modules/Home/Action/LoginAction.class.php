<?php
class LoginAction extends CommonAction
{
    /**
     * 登录验证码key
     */
    const VERIFY_CODE_KEY = "login-verify-code";

    public function index()
    {        
		$this->display();
    }
	
    public function verifyCode()
    {
        import("ORG.Util.Image");
        
        Image::buildImageVerify(4, 1, "png", 54, 26, self::VERIFY_CODE_KEY);
    }
    
    public function loginHandle()
    {
        $name = I("post.name");
        $password = I("post.password");
        $password_md5 = I("post.password", "", "md5");
        $code = I("post.code", "", "strtolower");

        if(empty($name))
        {
            $this->errorAjaxReturn("请输入用户名。");
        }

        if(empty($password))
        {
            $this->errorAjaxReturn("请输入密码。");
        }

        if(empty($code))
        {
            $this->errorAjaxReturn("请输入验证码。");
        }

        $session_code = session(self::VERIFY_CODE_KEY);

        if(empty($session_code))
        {
            $this->errorAjaxReturn("验证码已失效，请刷新重新输入。");
        }

        if(md5($code) != $session_code)
        {
            $this->errorAjaxReturn("验证码错误。");
        }

        $user_model = M("user");

        $where = array("username" => $name, "password" => $password_md5);

        $user = $user_model->where($where)->find();
        
        if(empty($user))
        {
            $this->errorAjaxReturn("用户名或密码错误。");
        }

        if($user["isLock"] == 1)
        {
            $this->errorAjaxReturn("用户被锁定，请和管理员联系。");
        }
        
		$data = array(
			"lastLoginTime" => mktime(),
			"lastLoginIP" => get_client_ip(),
            "loginCount" => array("exp", "loginCount + 1")
        );

        $uid = $user["id"];
        $uname = $user["name"];
        $uusername = $user["username"];
        
        // 更新用户信息
        $user_model->where(array("id" => $uid))->save($data);

        $credential = array(
            "id" => $uid,
            "name" => $uname,
            "username" => $uusername
        );
        
        // 缓存当前登录用户
        session(parent::SESSION_USER_AUTH_KEY, $credential);

        // 新增操作日志
        $this->addOperateLog("login", $uname, $uusername, "登录成功");
        
        // 清空验证码
        session(self::VERIFY_CODE_KEY, NULL);

        $this->successAjaxReturn();
    }
}
