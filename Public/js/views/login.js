function alert(message)
{
    if(!$("#dialogMessage").hasClass("show"))
    {
        $("#dialogMessage").find("#pText").text(message);
        $("#dialogMessage").addClass("show");

        var timer = setTimeout(function()
        {
            $("#dialogMessage").removeClass("show");

            window.clearTimeout(timer);
        }, 2000);
    }
}

$(function()
{
    var isHandling = false;

    $(document).keyup(function(event)
    {
        if(event.keyCode == 13)
        {
            $("#btnLogin").trigger("click");
        }
    });

    $("#imgVerifyCode,#btnChangeCode").click(function()
    {
        $("#imgVerifyCode").attr("src", "/Login/verifyCode/" + Math.random()); 
    });

    $("#btnLogin").click(function()
    {
        var username = $.trim($("#iptUserName").val()),
            password = $.trim($("#iptPassword").val()),
            verifyCode = $.trim($("#iptVerifyCode").val());

        if(String.isNullOrEmpty(username))
        {
            alert("请输入用户名。");
            return;
        }

        if(String.isNullOrEmpty(password))
        {
            alert("请输入密码。");
            
            return;
        }

        if(String.isNullOrEmpty(verifyCode))
        {
            alert("请输入验证码。");
            
            return;
        }

        if(isHandling)
        {
            return;
        }

        isHandling = true;

        $.ajax
        ({
            type: "post",
            url: "/Login/loginHandle",
            dataType: "json",
            data:
            {
                name: username,
                password: password,
                code: verifyCode
            },
            success: function(response)
            {
                if(response.Code == 1)
                {
                    window.location.href = "/";
                }
                else
                {
                    alert(response.Message);
                }
            },
            complete: function()
            {
                isHandling = false;
            }
        });
    });
});