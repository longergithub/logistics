<?php
import("@.Common.ApiStatusCode");

function toApiResult($code, $data = null, $message = "")
{
    if(empty($message))
    {
        if($code == ApiStatusCode::Success)
        {
            $message = "成功。";
        }
        else if($code == ApiStatusCode::Failure)
        {
            $message = "失败。";
        }
        else if($code == ApiStatusCode::SessionLost)
        {
            $message = "会话丢失，请重新登录。";
        }
    }

    $serverTime = date("Y-m-d H:i:s", mktime());

    $result = array("Code" => $code, "ServerTime" => $serverTime);

    if(!empty($message))
    {
        $result["Message"] = $message;
    }

    if(!empty($data) || is_bool($data))
    {
        $result["Content"] = $data;
    }

    return $result;
}