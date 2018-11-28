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

    $("#btnShowAddVehicle").click(function()
    {
        $("#dialogAddVehicle").addClass("show");
    });

    $(".close-add-vehicle-dialog").click(function()
    {
        $("#dialogAddVehicle").removeClass("show");
    });

    $("#btnAddVehicle").click(function()
    {
        var plateNo = $.trim($("#iptPlateNo").val()),
            driver = $.trim($("#iptDriver").val()),
            remark = $("#txaRemark").val();

        if(String.isNullOrEmpty(plateNo))
        {
            alert("请输入车牌号。");

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
            url: "/Index/addVehicleHandle",
            dataType: "json",
            data:
            {
                plateNo: plateNo,
                driver: driver,
                remark: remark
            },
            success: function(response)
            {
                if(response.Code == 1)
                {
                    alert("录入成功！");

                    $("#iptPlateNo").val("");
                    $("#iptDriver").val("");
                    $("#txaRemark").val("");
                    
                    $("#dialogAddVehicle").removeClass("show");
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

    $("#btnShowUpdatePassword").click(function()
    {
        $("#dialogUpdatePassword").addClass("show");
    });

    $(".close-update-password-dialog").click(function()
    {
        $("#dialogUpdatePassword").removeClass("show");
    });

    $("#btnUpdatePassword").click(function()
    {
        var password = $.trim($("#iptPassword").val()),
            confirmPassword = $.trim($("#iptConfirmPassword").val());

        if(String.isNullOrEmpty(password))
        {
            alert("请输入密码。");

            return;
        }

        if(password != confirmPassword)
        {
            alert("两次手机号输入不相同。");

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
            url: "/Index/updatePasswordHandle",
            dataType: "json",
            data:
            {
                password: password
            },
            success: function(response)
            {
                if(response.Code == 1)
                {
                    alert("修改成功！");

                    $("#iptPassword").val("");
                    
                    $("#dialogUpdatePassword").removeClass("show");
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

    $("#btnShowLogout").click(function()
    {
        $("#dialogLogoutConfirm").addClass("show");
    });

    $(".close-logout-confirm-dialog").click(function()
    {
        $("#dialogLogoutConfirm").removeClass("show");
    });

    $("#btnLogout").click(function()
    {
        if(isHandling)
        {
            return;
        }

        isHandling = true;

        $.ajax
        ({
            url: "/Index/logoutHandle",
            dataType: "json",
            success: function()
            {
                window.location.href = "/Login";
            },
            complete: function()
            {
                isHandling = false;
            }
        });
    });
});