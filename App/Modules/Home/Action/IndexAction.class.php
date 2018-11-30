<?php
class IndexAction extends UserCommonAction
{
    public function index()
    {
		$this->vehicles = M("vehicle")->where(array("isDisabled" => 0))->select();

      	$this->display();
	}
	
	public function addVehicleHandle()
	{
        $plateNo = I("post.plateNo");
        $driver = I("post.driver");
        $remark = I("post.remark");

        if(empty($plateNo))
        {
            $this->errorAjaxReturn("请输入车牌号。");
		}

		$count = M("vehicle")->where(array("plateNo" => $plateNo))->count();
		
		if($count > 0)
		{
            $this->errorAjaxReturn("车牌号已存在。");
		}

		$data = array(
			"plateNo" => $plateNo,
			"driver" => $driver,
			"remark" => $remark,
			"createdTime" => mktime()
		);

		M("vehicle")->add($data);

        // 新增操作日志
        $this->addOperateLog("add-vehicle", $this->credential["name"], $this->credential["username"], "录入车辆成功");

        $this->successAjaxReturn();
	}

	public function updatePasswordHandle()
	{
        $password = I("post.password");

        if(empty($password))
        {
            $this->errorAjaxReturn("请输入密码。");
		}

		M("user")->where(array("id", $this->credential["id"]))->save(array("password" => md5($password)));

        // 新增操作日志
        $this->addOperateLog("update-password", $this->credential["name"], $this->credential["username"], "修改密码成功");

        $this->successAjaxReturn();
	}

	public function logoutHandle()
	{
		session(parent::SESSION_USER_AUTH_KEY, null);
		
        $this->successAjaxReturn();
	}

	public function searchVehicleRecordHandle()
	{
        $pageIndex = I("post.pageIndex", 1, "intval");
		$pageSize = I("post.pageSize", 10, "intval");
		$startDate = I("post.startDate");
		$endDate = I("post.endDate");
		$vehicleId = I("post.vehicleId");

		$vehicle_record_model = M("vehicle_record");

		$where["isDeleted"] = 0;
		$where["createdTime"] = array(array("egt", strtotime($startDate)), array("elt", strtotime($endDate)));

		if(!empty($vehicleId))
		{
			$where["vehicleId"] = $vehicleId;
		}

		$count = $vehicle_record_model->where($where)->count();

        $totalPage = 0;

        $records =  array();

        if($count > 0)
        {
            $totalPage = ceil($count / $pageSize);

            $startCount = ($pageIndex - 1) * $pageSize;

			$limit = $startCount . "," . $pageSize;
			
			$totalRecord = $vehicle_record_model->where($where)->field("sum(turnoverAmount) as `turnoverAmount`, sum(daySalary) as `daySalary`, sum(daySalary) as `daySalary`, sum(etcAmount) as `etcAmount`, sum(roadAmount) as `roadAmount`, sum(refuelAmount) as `refuelAmount`, sum(washAmount) as `washAmount`, sum(tailGasDealAmount) as `tailGasDealAmount`, sum(policePenaltyAmount) as `policePenaltyAmount`, sum(roadPenaltyAmount) as `roadPenaltyAmount`, sum(advanceAmount) as `advanceAmount`, sum(etcRechargeAmount) as `etcRechargeAmount`, sum(fixedAmount) as `fixedAmount`")->find();

			$totalAmount = 0;

			foreach ($totalRecord as $i=>$r)
			{
				$totalAmount += $r;
			}

			$totalRecord["totalAmount"] = $totalAmount;

			$records = $vehicle_record_model->where($where)->order("createdTime desc")->limit($limit)->select();
			
			foreach ($records as $i=>$r)
			{
				$records[$i]["createdTime"] = date("Y年m月d日", $r["createdTime"]);
				$records[$i]["totalAmount"] = $r["etcAmount"] + $r["roadAmount"] + $r["refuelAmount"] + $r["washAmount"] + $r["tailGasDealAmount"] + $r["policePenaltyAmount"] + $r["roadPenaltyAmount"] + $r["fixedAmount"] + $r["advanceAmount"] + $r["etcRechargeAmount"];
			}
        }

		$result = array("TotalCount" => $count, "TotalPage" => $totalPage, "TotalRecord" => $totalRecord, "Records" => $records);
		
		$this->successAjaxReturn($result);
	}

	public function getVehicleRecordHandle()
	{
		$startDate = I("post.startDate");
		$endDate = I("post.endDate");
		$vehicleId = I("post.vehicleId");

		$vehicle_record_model = M("vehicle_record");

		$where["isDeleted"] = 0;
		$where["createdTime"] = array(array("egt", strtotime($startDate)), array("elt", strtotime($endDate)));

		if(!empty($vehicleId))
		{
			$where["vehicleId"] = $vehicleId;
		}

		$count = $vehicle_record_model->where($where)->count();

        $totalPage = 0;

        $records =  array();

        if($count > 0)
        {
			$totalRecord = $vehicle_record_model->where($where)->field("sum(turnoverAmount) as `turnoverAmount`, sum(daySalary) as `daySalary`, sum(daySalary) as `daySalary`, sum(etcAmount) as `etcAmount`, sum(roadAmount) as `roadAmount`, sum(refuelAmount) as `refuelAmount`, sum(washAmount) as `washAmount`, sum(tailGasDealAmount) as `tailGasDealAmount`, sum(policePenaltyAmount) as `policePenaltyAmount`, sum(roadPenaltyAmount) as `roadPenaltyAmount`, sum(advanceAmount) as `advanceAmount`, sum(etcRechargeAmount) as `etcRechargeAmount`, sum(fixedAmount) as `fixedAmount`")->find();

			$totalAmount = 0;

			foreach ($totalRecord as $i=>$r)
			{
				$totalAmount += $r;
			}

			$totalRecord["totalAmount"] = $totalAmount;

			$records = $vehicle_record_model->where($where)->order("createdTime desc")->select();
			
			foreach ($records as $i=>$r)
			{
				$records[$i]["createdTime"] = date("Y年m月d日", $r["createdTime"]);
				$records[$i]["totalAmount"] = $r["etcAmount"] + $r["roadAmount"] + $r["refuelAmount"] + $r["washAmount"] + $r["tailGasDealAmount"] + $r["policePenaltyAmount"] + $r["roadPenaltyAmount"] + $r["fixedAmount"] + $r["advanceAmount"] + $r["etcRechargeAmount"];
			}
        }

		$result = array("TotalRecord" => $totalRecord, "Records" => $records);
		
		$this->successAjaxReturn($result);
	}

	public function addVehicleRecord()
	{
		$this->vehicles = M("vehicle")->where(array("isDisabled" => 0))->select();

		$this->display();
	}

	public function addVehicleRecordHandle()
	{
        $vehicleId = I("post.vehicleId");
        $quantity = I("post.quantity", 0, "intval");
        $caseNo = I("post.caseNo");
        $startLocation = I("post.startLocation");
        $endLocation = I("post.endLocation");
        $goodsName = I("post.goodsName");
        $turnoverAmount = I("post.turnoverAmount");
        $daySalary = I("post.daySalary");
        $etcAmount = I("post.etcAmount");
        $roadAmount = I("post.roadAmount");
        $refuelAmount = I("post.refuelAmount");
        $washAmount = I("post.washAmount");
        $tailGasDealAmount = I("post.tailGasDealAmount");
        $policePenaltyAmount = I("post.policePenaltyAmount");
        $roadPenaltyAmount = I("post.roadPenaltyAmount");
        $advanceAmount = I("post.advanceAmount");
        $etcRechargeAmount = I("post.etcRechargeAmount");
        $fixedAmount = I("post.fixedAmount");
        $fixedFactoryName = I("post.fixedFactoryName");
        $fixedContent = I("post.fixedContent");
		$remark = I("post.remark");

		$data = array(
			"vehicleId" => $vehicleId,
			"quantity" => $quantity,
			"caseNo" => $caseNo,
			"startLocation" => $startLocation,
			"endLocation" => $endLocation,
			"goodsName" => $goodsName,
			"turnoverAmount" => $turnoverAmount,
			"daySalary" => $daySalary,
			"etcAmount" => $etcAmount,
			"roadAmount" => $roadAmount,
			"refuelAmount" => $refuelAmount,
			"washAmount" => $washAmount,
			"tailGasDealAmount" => $tailGasDealAmount,
			"policePenaltyAmount" => $policePenaltyAmount,
			"roadPenaltyAmount" => $roadPenaltyAmount,
			"advanceAmount" => $advanceAmount,
			"etcRechargeAmount" => $etcRechargeAmount,
			"fixedAmount" => $fixedAmount,
			"fixedFactoryName" => $fixedFactoryName,
			"fixedContent" => $fixedContent,
			"remark" => $remark,
			"creatorId" => $this->credential["id"],
			"createdTime" => mktime()
		);

		M("vehicle_record")->add($data);

        // 新增操作日志
        $this->addOperateLog("add-vehicle", $this->credential["name"], $this->credential["username"], "录入行车日记成功");

        $this->successAjaxReturn();
	}

	public function deleteVehicleRecordHandle()
	{
		$id = I("id");

		M("vehicle_record")->where(array("id" => $id))->save(array("isDeleted" => 1));

        // 新增操作日志
		$this->addOperateLog("delete-vehicle-record", $this->credential["name"], $this->credential["username"], "作废行车日记成功");
		
        $this->successAjaxReturn();
	}
}
