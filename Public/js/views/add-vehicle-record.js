function isValidInteger(value, name, isRequired = false)
{
    if(isRequired && String.isNullOrEmpty(value))
    {
        alert("请输入" + name + "。");

        return false;
    }

    if(!String.isNullOrEmpty(value))
    {
        if(!/^[1-9]{1}\d*$/.test(value) || parseInt(value) == 0)
        {
            alert("请输入正确的" + name + "。");

            return false;
        }
    }

    return true;
}

function isValidNumber(value, name, isRequired = false)
{
    if(isRequired && String.isNullOrEmpty(value))
    {
        alert("请输入" + name + "。");

        return false;
    }

    if(!String.isNullOrEmpty(value))
    {
        if(!/^(([1-9][0-9]*)|([0]\.\d{1,2}|[1-9][0-9]*\.\d{1,2}))$/.test(value) || parseFloat(value) == 0)
        {
            alert("请输入正确的" + name + "。");

            return false;
        }
    }

    return true;
}

$(function()
{
    var isHandling = false;

    $("#cbIsFixed").change(function()
    {
        if($(this).is(":checked"))
        {
            $("#liFixedAmount").css("display", "flex");
            $("#liFixedFactoryName").css("display", "flex");
            $("#litxaFixedContent").css("display", "flex");
        }
        else
        {
            $("#liFixedAmount").hide();
            $("#liFixedFactoryName").hide();
            $("#litxaFixedContent").hide();
        }
    });

    $("#btnAdd").click(function()
    {
        var vehicleId = $("#selVehicle").val(),
            quantity = $.trim($("#iptQuantity").val()),
            caseNo = $.trim($("#iptCaseNo").val()),
            startLocation = $.trim($("#iptStartLocation").val()),
            endLocation = $.trim($("#iptEndLocation").val()),
            goodsName = $.trim($("#iptGoodsName").val()),
            turnoverAmount = $.trim($("#iptTurnoverAmount").val()),
            daySalary = $.trim($("#iptDaySalary").val()),
            etcAmount = $.trim($("#iptETCAmount").val()),
            roadAmount = $.trim($("#iptRoadAmount").val()),
            refuelAmount = $.trim($("#iptRefuelAmount").val()),
            washAmount = $.trim($("#iptWashAmount").val()),
            tailGasDealAmount = $.trim($("#iptTailGasDealAmount").val()),
            policePenaltyAmount = $.trim($("#iptPolicePenaltyAmount").val()),
            roadPenaltyAmount = $.trim($("#iptRoadPenaltyAmount").val()),
            advanceAmount = $.trim($("#iptAdvanceAmount").val()),
            etcRechargeAmount = $.trim($("#iptEtcRechargeAmount").val()),
            fixedAmount = $("#cbIsFixed").is(":checked") ? $.trim($("#iptFixedAmount").val()) : null,
            fixedFactoryName = $("#cbIsFixed").is(":checked") ? $.trim($.trim($("#iptFixedFactoryName").val())) : null,
            fixedContent = $("#cbIsFixed").is(":checked") ? $("#txaFixedContent").val() : null,
            remark = $("#txaRemark").val();

        if(!vehicleId)
        {
            alert("请选择车号。");

            return;
        }

        var result = true;

        result = isValidInteger(quantity, "数量", true);

        if(!result)
        {
            return;
        }

        if(String.isNullOrEmpty(caseNo))
        {
            alert("请输入箱号。");

            return;
        }

        if(String.isNullOrEmpty(startLocation))
        {
            alert("请输入起点。");

            return;
        }

        if(String.isNullOrEmpty(endLocation))
        {
            alert("请输入终点。");

            return;
        }

        if(String.isNullOrEmpty(goodsName))
        {
            alert("请输入货物名称。");

            return;
        }

        result = isValidNumber(turnoverAmount, "营业额");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(daySalary, "计件工资");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(etcAmount, "过路费(ETC)");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(roadAmount, "过路费(现金)");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(refuelAmount, "加油金额");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(washAmount, "洗车加水金额");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(tailGasDealAmount, "尿素金额");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(policePenaltyAmount, "交警罚款");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(roadPenaltyAmount, "路政罚款");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(advanceAmount, "预支金额");
        
        if(!result)
        {
            return;
        }

        result = isValidNumber(etcRechargeAmount, "ETC充值金额");
        
        if(!result)
        {
            return;
        }

        if($("#cbIsFixed").is(":checked"))
        {
            result = isValidNumber(fixedAmount, "维修金额", true);
            
            if(!result)
            {
                return;
            }

            if(String.isNullOrEmpty(fixedFactoryName))
            {
                alert("请输入维修厂名称。");
    
                return;
            }
        }

        if(isHandling)
        {
            return;
        }

        isHandling = true;

        $.ajax
        ({
            type: "post",
            url: "/Index/addVehicleRecordHandle",
            dataType: "json",
            data:
            {
                vehicleId: vehicleId,
                quantity: quantity,
                caseNo: caseNo,
                startLocation: startLocation,
                endLocation: endLocation,
                goodsName: goodsName,
                turnoverAmount: turnoverAmount,
                daySalary: daySalary,
                etcAmount: etcAmount,
                roadAmount: roadAmount,
                refuelAmount: refuelAmount,
                washAmount: washAmount,
                tailGasDealAmount: tailGasDealAmount,
                policePenaltyAmount: policePenaltyAmount,
                roadPenaltyAmount: roadPenaltyAmount,
                advanceAmount: advanceAmount,
                etcRechargeAmount: etcRechargeAmount,
                fixedAmount: fixedAmount,
                fixedFactoryName: fixedFactoryName,
                fixedContent: fixedContent,
                remark: remark
            },
            success: function(response)
            {
                if(response.Code == 1)
                {
                    alert("录入成功！");

                    setTimeout(() =>
                    {
                        window.location.href = "/Index";
                    }, 2000);
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