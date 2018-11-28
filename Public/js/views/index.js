var isSearching = false;

function paging(pageIndex, pageSize)
{
    var startDate = $("#iptStartDate").val(),
        endDate = $("#iptEndDate").val(),
        vehicleId = $("#selVehicle").val();

    $.ajax
    ({
        type: "post",
        url: "/Index/searchVehicleRecordHandle",
        dataType: "json",
        data:
        {
            pageIndex: pageIndex,
            pageSize: pageSize,
            startDate: startDate,
            endDate: endDate,
            vehicleId: vehicleId
        },
        success: function(response)
        {
            var tbody = $("#dataTable").find("tbody");

            if(response.Code == 1)
            {
                var data = response.Content,
                    totalCount = data.TotalCount,
                    totalPage = data.TotalPage,
                    totalRecord = data.TotalRecord,
                    records = data.Records,
                    fixedTr = "",
                    fixedAmount = 0,
                    fixedFactoryName = 0,
                    fixedContent = 0;

                if(totalCount > 0)
                {
                    $("#lblTotalCount").text(totalCount);
                    $("#lblCurrentPage").text(pageIndex);
                    $("#lblTotalPage").text(totalPage);

                    if(totalPage > 1)
                    {
                        $("#btnFirstPage").show();
                        $("#btnPreviousPage").show();
                        $("#btnNextPage").show();
                        $("#btnLastPage").show();

                        if(pageIndex === 1)
                        {
                            $("#btnFirstPage").removeClass("enable");
                            $("#btnPreviousPage").removeClass("enable");
                        }
                        else
                        {
                            $("#btnFirstPage").addClass("enable");
                            $("#btnPreviousPage").addClass("enable");
                        }

                        if(pageIndex === totalPage)
                        {
                            $("#btnNextPage").removeClass("enable");
                            $("#btnLastPage").removeClass("enable");
                        }
                        else
                        {
                            $("#btnNextPage").addClass("enable");
                            $("#btnLastPage").addClass("enable");
                        }
                    }
                    else
                    {
                        $("#btnFirstPage").hide();
                        $("#btnPreviousPage").hide();
                        $("#btnNextPage").hide();
                        $("#btnLastPage").hide();
                    }

                    tbody.empty();

                    for(var i = 0, len = records.length; i < len; i++)
                    {
                        fixedAmount = records[i]["fixedAmount"];
                        fixedFactoryName = records[i]["fixedFactoryName"];
                        fixedContent = records[i]["fixedContent"];

                        tbody.append("<tr onclick=\"onOpenFixedTr(this)\">"+
                        "<td>" + records[i]["createdTime"] + "</td>"+
                        "<td>" + records[i]["quantity"] + "</td>"+
                        "<td>" + records[i]["caseNo"] + "</td>"+
                        "<td>" + records[i]["startLocation"] + "</td>"+
                        "<td>" + records[i]["endLocation"] + "</td>"+
                        "<td>" + records[i]["goodsName"] + "</td>"+
                        "<td>" + records[i]["turnoverAmount"] + "</td>"+
                        "<td>" + records[i]["daySalary"] + "</td>"+
                        "<td>" + records[i]["etcAmount"] + "</td>"+
                        "<td>" + records[i]["roadAmount"] + "</td>"+
                        "<td>" + records[i]["refuelAmount"] + "</td>"+
                        "<td>" + records[i]["washAmount"] + "</td>"+
                        "<td>" + records[i]["tailGasDealAmount"] + "</td>"+
                        "<td>" + records[i]["policePenaltyAmount"] + "</td>"+
                        "<td>" + records[i]["roadPenaltyAmount"] + "</td>"+
                        "<td>" + fixedAmount + "</td>"+
                        "<td>" + records[i]["advanceAmount"] + "</td>"+
                        "<td>" + records[i]["etcRechargeAmount"] + "</td>"+
                        "<td style=\"color: red;\">" + records[i]["totalAmount"] + "</td>"+
                        "<td>" + records[i]["remark"] + "</td>"+
                        "<td><a href=\"javascript:;\" onclick=\"onDelete(event, " + records[i]["id"] + ", " + pageIndex + ", " + pageSize + ")\">作废</a></td></tr>");

                        if(fixedAmount && fixedAmount > 0 && (!String.isNullOrEmpty(fixedFactoryName) || !String.isNullOrEmpty(fixedContent)))
                        {
                            fixedTr = "<tr class=\"fixed-tr\"><td colspan=\"21\"><ul>";

                            !String.isNullOrEmpty(fixedFactoryName) && (fixedTr += "<li><p>维修厂名称：</p><p>" + fixedFactoryName + "</p></li>");
                            !String.isNullOrEmpty(fixedContent) && (fixedTr += "<li><p>维修项目：</p><p>" + fixedContent.replace(/\r\n/g, "<br />").replace(/\n/g, "<br />") + "</p></li>");

                            fixedTr += "</ul></td></tr>";

                            tbody.append(fixedTr);
                        }
                    }
                    
                    tbody.append("<tr class=\"total-tr\">"+
                    "<td>总计</td>"+
                    "<td></td>"+
                    "<td></td>"+
                    "<td></td>"+
                    "<td></td>"+
                    "<td></td>"+
                    "<td>" + totalRecord["turnoverAmount"] + "</td>"+
                    "<td>" + totalRecord["daySalary"] + "</td>"+
                    "<td>" + totalRecord["etcAmount"] + "</td>"+
                    "<td>" + totalRecord["roadAmount"] + "</td>"+
                    "<td>" + totalRecord["refuelAmount"] + "</td>"+
                    "<td>" + totalRecord["washAmount"] + "</td>"+
                    "<td>" + totalRecord["tailGasDealAmount"] + "</td>"+
                    "<td>" + totalRecord["policePenaltyAmount"] + "</td>"+
                    "<td>" + totalRecord["roadPenaltyAmount"] + "</td>"+
                    "<td>" + totalRecord["fixedAmount"] + "</td>"+
                    "<td>" + totalRecord["advanceAmount"] + "</td>"+
                    "<td>" + totalRecord["etcRechargeAmount"] + "</td>"+
                    "<td>" + totalRecord["totalAmount"] + "</td>"+
                    "<td></td><td></td></tr>");

                    $("#divPagingBar").show();
                }
                else
                {
                    tbody.empty().append("<tr><td colspan=\"21\">暂无记录~</td></tr>");

                    $("#divPagingBar").hide();
                }
            }
            else
            {
                tbody.empty().append("<tr><td colspan=\"21\">暂无记录~</td></tr>");

                $("#divPagingBar").hide();
            }
        },
        complete: function()
        {
            isSearching = false;

            $("#btnSearch").find("img").hide();
            $("#btnSearch").find("i").show();
        }
    });
}

function onOpenFixedTr(sender)
{
    $(sender).next(".fixed-tr").length > 0 && $(sender).next(".fixed-tr").slideToggle();
}

function onDelete(e, id, pageIndex, pageSize)
{
    e.stopPropagation();

    $.ajax
    ({
        type: "post",
        url: "/Index/deleteVehicleRecordHandle",
        dataType: "json",
        data:
        {
            id: id
        },
        success: function(response)
        {
            if(response.Code == 1)
            {
                alert("作废成功！");

                paging(pageIndex, pageSize);
            }
            else
            {
                alert(response.Message);
            }
        }
    });
}

$(function()
{
    var pageIndex = 1,
        pageSize = 10,
        defaultDate = new Date(),
        defaultDateYear = defaultDate.getFullYear(),
        defaultDateMonth = defaultDate.getMonth(),
        defaultDateDate = defaultDate.getDate(),
        defaultStartDate = defaultDateYear + "-" + (defaultDateMonth + 1) + "-" + defaultDateDate,
        defaultEndDate = defaultDateYear + "-" + (defaultDateMonth + 2) + "-" + defaultDateDate;

    $("#iptStartDate").val(defaultStartDate);
    $("#iptEndDate").val(defaultEndDate);

    paging(pageIndex, pageSize);

    $("#btnSearch").click(function()
    {
        var startDate = $("#iptStartDate").val(),
            endDate = $("#iptEndDate").val();

        if(String.isNullOrEmpty(startDate))
        {
            alert("请输入开始日期。");

            return;
        }


        if(String.isNullOrEmpty(endDate))
        {
            alert("请输入结束日期。");

            return;
        }

        if(isSearching)
        {
            return;
        }

        isSearching = true;
        
        $(this).find("img").show();
        $(this).find("i").hide();

        paging(pageIndex, pageSize);
    });

    $("#btnExport").click(function()
    {
        var startDate = $("#iptStartDate").val(),
            endDate = $("#iptEndDate").val(),
            vehicleId = $("#selVehicle").val();

        if(String.isNullOrEmpty(startDate))
        {
            alert("请输入开始日期。");

            return;
        }


        if(String.isNullOrEmpty(endDate))
        {
            alert("请输入结束日期。");

            return;
        }

        if(isSearching)
        {
            return;
        }

        isSearching = true;
        
        $(this).find("img").show();
        $(this).find("i").hide();

        $.ajax
        ({
            type: "post",
            url: "/Index/getVehicleRecordHandle",
            dataType: "json",
            data:
            {
                startDate: startDate,
                endDate: endDate,
                vehicleId: vehicleId
            },
            success: function(response)
            {
                var tbody = $("#exportTable").find("tbody");

                if(response.Code == 1)
                {
                    var data = response.Content,
                        totalRecord = data.TotalRecord,
                        records = data.Records,
                        fixedAmount = 0,
                        fixedFactoryName = 0,
                        fixedContent = 0;

                    if(records.length > 0)
                    {
                        tbody.empty();

                        for(var i = 0, len = records.length; i < len; i++)
                        {
                            fixedAmount = records[i]["fixedAmount"];
                            fixedFactoryName = records[i]["fixedFactoryName"];
                            fixedContent = records[i]["fixedContent"];

                            tbody.append("<tr>"+
                            "<td>" + records[i]["createdTime"] + "</td>"+
                            "<td>" + records[i]["quantity"] + "</td>"+
                            "<td>" + records[i]["caseNo"] + "</td>"+
                            "<td>" + records[i]["startLocation"] + "</td>"+
                            "<td>" + records[i]["endLocation"] + "</td>"+
                            "<td>" + records[i]["goodsName"] + "</td>"+
                            "<td>" + records[i]["turnoverAmount"] + "</td>"+
                            "<td>" + records[i]["daySalary"] + "</td>"+
                            "<td>" + records[i]["etcAmount"] + "</td>"+
                            "<td>" + records[i]["roadAmount"] + "</td>"+
                            "<td>" + records[i]["refuelAmount"] + "</td>"+
                            "<td>" + records[i]["washAmount"] + "</td>"+
                            "<td>" + records[i]["tailGasDealAmount"] + "</td>"+
                            "<td>" + records[i]["policePenaltyAmount"] + "</td>"+
                            "<td>" + records[i]["roadPenaltyAmount"] + "</td>"+
                            "<td>" + fixedAmount + "</td>"+
                            "<td>" + fixedFactoryName + "</td>"+
                            "<td>" + fixedContent + "</td>"+
                            "<td>" + records[i]["advanceAmount"] + "</td>"+
                            "<td>" + records[i]["etcRechargeAmount"] + "</td>"+
                            "<td style=\"color: red;\">" + records[i]["totalAmount"] + "</td>"+
                            "<td>" + records[i]["remark"] + "</td></tr>");
                        }
                        
                        tbody.append("<tr>"+
                        "<td>总计</td>"+
                        "<td></td>"+
                        "<td></td>"+
                        "<td></td>"+
                        "<td></td>"+
                        "<td></td>"+
                        "<td>" + totalRecord["turnoverAmount"] + "</td>"+
                        "<td>" + totalRecord["daySalary"] + "</td>"+
                        "<td>" + totalRecord["etcAmount"] + "</td>"+
                        "<td>" + totalRecord["roadAmount"] + "</td>"+
                        "<td>" + totalRecord["refuelAmount"] + "</td>"+
                        "<td>" + totalRecord["washAmount"] + "</td>"+
                        "<td>" + totalRecord["tailGasDealAmount"] + "</td>"+
                        "<td>" + totalRecord["policePenaltyAmount"] + "</td>"+
                        "<td>" + totalRecord["roadPenaltyAmount"] + "</td>"+
                        "<td>" + totalRecord["fixedAmount"] + "</td>"+
                        "<td></td>"+
                        "<td></td>"+
                        "<td>" + totalRecord["advanceAmount"] + "</td>"+
                        "<td>" + totalRecord["etcRechargeAmount"] + "</td>"+
                        "<td>" + totalRecord["totalAmount"] + "</td>"+
                        "<td></td></tr>");

                        var time = Date.now().valueOf();

                        $("#exportTable").tableExport
                        ({
                            fileName: "万汇物流行车日记表-" + time,
                            type: "xlsx"
                        });
                    }
                    else
                    {
                        alert("暂无记录可导出！");
                    }
                }
            },
            complete: function()
            {
                isSearching = false;

                $("#btnExport").find("img").hide();
                $("#btnExport").find("i").show();
            }
        });
    });

    $("#btnFirstPage").click(function()
    {
        pageIndex = 1;

        paging(pageIndex, pageSize);
    });

    $("#btnPreviousPage").click(function()
    {
        if(pageIndex === 1)
        {
            return;
        }

        pageIndex--;

        paging(pageIndex, pageSize);
    });

    $("#btnNextPage").click(function()
    {
        if(pageIndex == $("#lblTotalPage").text())
        {
            return;
        }

        pageIndex++;

        paging(pageIndex, pageSize);
    });

    $("#btnLastPage").click(function()
    {
        pageIndex = $("#lblTotalPage").text();

        paging(pageIndex, pageSize);
    });
});