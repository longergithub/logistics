<?php
class CommonAction extends Action
{
    /**
     * 用户session key
     */
    const SESSION_USER_AUTH_KEY = "WHWL_USER_CREDENTIAL";

    public function _empty()
    {
        header("Location: /Public/tpl/404.html");
    }

    /**
     * 成功返回json。
     * @param null $content
     * @param string $msg
     */
    protected function successAjaxReturn($content = null, $msg = "")
    {
        $this->ajaxReturn(toApiResult(ApiStatusCode::Success, $content, $msg), "json");
    }

    /**
     * 失败返回json。
     * @param string $msg
     * @param null $content
     */
    protected function errorAjaxReturn($msg = "", $content = null)
    {
        $this->ajaxReturn(toApiResult(ApiStatusCode::Failure, $content, $msg), "json");
    }

    /**
     * 新增操作日志。
     * @param string $type
     * @param string $name
     * @param string $username
     * @param string $info
     */
    protected function addOperateLog($type, $name, $username, $info)
    {
        M("operate_log")->add(array(
			"type" => $type,
            "operateName" => $name,
            "operateUsername" => $username,
            "operateIp" => get_client_ip(),
            "operateTime" => mktime(),
            "info" => $info
        ));
    }
}
