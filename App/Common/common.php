<?php
	function p ($array) {
		dump($array, 1, '<pre>', 0);
	}
	    /**
	 *递归重组节点信息为多维数组
	 *@param [type]  $node [要处理的节点数组]
	 *@param integer $pid  [父级ID]
	 *@param [type]        [description]
	 */
	function node_merge ($node, $access=null, $pid=0) {
		$arr = array();
		foreach ($node as $v) {
			if(is_array($access)) {
				$v['access'] = in_array($v['id'], $access) ? 1 : 0;	
			}
			
			if($v['pid'] == $pid) {
				$v['child'] = node_merge($node, $access, $v['id']);
				$arr[] = $v;
			}
		}
		
		return $arr;
	}
	
	//根据日期获取年龄
	function getAge ($birthday) {//$birthday格式如：1985-09-23
		$age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;  
		if (date('m', time()) == date('m', strtotime($birthday))){  
			if (date('d', time()) > date('d', strtotime($birthday))){  
			$age++;  
			}  
		}elseif (date('m', time()) > date('m', strtotime($birthday))){  
			$age++;  
		}  
		return $age;
	}
	
	//获取两个时间戳相隔的小时数
	function hours_min($start_time,$end_time){
		if (strtotime($start_time) > strtotime($end_time)) list($start_time, $end_time) = array($end_time, $start_time);
		$sec = $start_time - $end_time;
		$sec = round($sec/60);
		$hours_min = floor($sec/60);
		return $hours_min;
	} 
	
	/**
	* 邮件发送函数
	*/
	function sendMail($to, $subject, $content) {
		Vendor('PHPMailer.class#phpmailer');
		$mail = new PHPMailer(); //实例化
		$mail->IsSMTP(); // 启用SMTP
		$mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以126邮箱为例）
		$mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
		$mail->Username = C('MAIL_USERNAME'); //你的邮箱名
		$mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
		$mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
		$mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
		$mail->AddAddress($to,"name");
		$mail->WordWrap = 50; //设置每行字符长度
		$mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
		$mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
		$mail->Subject =$subject; //邮件主题
		$mail->Body = $content; //邮件内容
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //邮件正文不支持HTML的备用显示
		return $mail->Send() ? true : $mail->ErrorInfo;
	}
	
		/**
     * 友好的时间显示
     *
     * @param int    $sTime 待显示的时间
     */
    function friendlyDate($sTime) {
        if (!$sTime)
            return '';
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime      =   time();
        $dTime      =   $cTime - $sTime;
        $dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
        //$dDay     =   intval($dTime/3600/24);
        $dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));
		if( $dTime < 60 ){
			if($dTime < 10){
				return '刚刚';    //by yangjs
			}else{
				return intval(floor($dTime / 10) * 10)."秒前";
			}
		}elseif( $dTime < 3600 ){
			return intval($dTime/60)."分钟前";
		//今天的数据.年份相同.日期相同.
		}elseif( $dYear==0 && $dDay == 0  ){
			//return intval($dTime/3600)."小时前";
			return '今天';
		}elseif($dYear==0){
			return date("m月d日",$sTime);
		}else{
			return date("Y-m-d",$sTime);
		}

    }
	
	//生成随机数
	function randomkeys($length){
	 $pattern='1234567890ABCDEFGHIJKLOMNOPQRSTUVWXYZ,./<>?;#:@~[]{}-_=+)(*&^%___FCKpd___0pound;"!'; //字符池
	 for($i=0;$i<$length;$i++){
	  $key.=$pattern{mt_rand(0,35)};//生成php随机数
	 }
	 return $key;
	}
	
	/** 
	 * 截取UTF8编码字符串从首字节开始指定宽度(非长度), 适用于字符串长度有限的如新闻标题的等宽度截取 
	 * 中英文混排情况较理想. 全中文与全英文截取后对比显示宽度差异最大,且截取宽度远大越明显. 
	 * @param string $str   UTF-8 encoding 
	 * @param int[option] $width 截取宽度 
	 * @param string[option] $end 被截取后追加的尾字符 
	 * @param float[option] $x3<p> 
	 *  3字节（中文）字符相当于希腊字母宽度的系数coefficient（小数） 
	 *  中文通常固定用宋体,根据ascii字符字体宽度设定,不同浏览器可能会有不同显示效果</p> 
	 */ 
	function u8_title_substr($str, $width = 0, $end = '...', $x3 = 0) {  
		global $CFG; // 全局变量保存 x3 的值  
		if ($width <= 0 || $width >= strlen($str)) {  
			return $str;  
		}  
		$arr = str_split($str);  
		$len = count($arr);  
		$w = 0;  
		$width *= 10;  
	  
		// 不同字节编码字符宽度系数  
		$x1 = 11;   // ASCII  
		$x2 = 16;  
		$x3 = $x3===0 ? ( $CFG['cf3']  > 0 ? $CFG['cf3']*10 : $x3 = 21 ) : $x3*10;  
		$x4 = $x3;  
	  
		// http://zh.wikipedia.org/zh-cn/UTF8  
		for ($i = 0; $i < $len; $i++) {  
			if ($w >= $width) {  
				$e = $end;  
				break;  
			}  
			$c = ord($arr[$i]);  
			if ($c <= 127) {  
				$w += $x1;  
			}  
			elseif ($c >= 192 && $c <= 223) { // 2字节头  
				$w += $x2;  
				$i += 1;  
			}  
			elseif ($c >= 224 && $c <= 239) { // 3字节头  
				$w += $x3;  
				$i += 2;  
			}  
			elseif ($c >= 240 && $c <= 247) { // 4字节头  
				$w += $x4;  
				$i += 3;  
			}  
		}  
	  
		return implode('', array_slice($arr, 0, $i) ). $e;  
	}
	
	//获取第三方集成类型名称
	function think_sdk_name($type) {
		$typeName;
		switch ($type) {
			case 'qq':
			  $typeName = 'QQ';
			  break;  
			case 'sina':
			  $typeName = '新浪微博';
			  break;
			case 'tencent':
			  $typeName = '腾讯微博';
			  break;
			case 't163':
			  $typeName = '网易微博';
			  break;
			case 'renren':
			  $typeName = '人人网';
			  break;
			case 'x360':
			  $typeName = '360';
			  break;
			case 'douban':
			  $typeName = '豆瓣';
			  break;
			case 'taobao':
			  $typeName = '淘宝网';
			  break;
			case 'baidu':
			  $typeName = '百度';
			  break;
			case 'kaixin':
			  $typeName = '开心网';
			  break;
			case 'sohu':
			  $typeName = '搜狐微博';
			  break;
		}
		
		return $typeName;
	}
	
	//根据传入时间计算是星期几
	function wk($date1) {
		$datearr = explode("-",$date1);     //将传来的时间使用“-”分割成数组
		$year = $datearr[0];       //获取年份
		$month = sprintf('%02d',$datearr[1]);  //获取月份
		$day = sprintf('%02d',$datearr[2]);      //获取日期
		$hour = $minute = $second = 0;   //默认时分秒均为0
		$dayofweek = mktime($hour,$minute,$second,$month,$day,$year);    //将时间转换成时间戳
		$shuchu = date("w",$dayofweek);      //获取星期值
		$weekarray=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
		return $weekarray[$shuchu];
	}
	
	//返回某个表中某个字段的最大值，$t为表名，$f为字段名
	function get_tf_max($t,$f) {
		return $maxValue = M($t)->max($f);
	}
	
	/*
		数字编码，格式如0001,00001
		$v:需要编码的数字
		$n:位数，包括数字本身
	*/
	function get_code($v,$n) {
		$m = $n - strlen($v);//$m为需要补多少个0
		$code = $v;
		for($i=0;$i<$m;$i++) {
			$code = '0'.$code;
		}
		return $code;
	}
	
	/**************************************************************
	  *
	 *	使用特定function对数组中所有元素做处理
	 *	@param	string	&$array		要处理的字符串
	 *	@param	string	$function	要执行的函数
	 *	@return boolean	$apply_to_keys_also		是否也应用到key上
	 *	@access public
	 *
	 *************************************************************/
	 function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	 {
	 	static $recursive_counter = 0;
	 	if (++$recursive_counter > 1000) {
	 		die('possible deep recursion attack');
	 	}
	 	foreach ($array as $key => $value) {
	 		if (is_array($value)) {
	 			arrayRecursive($array[$key], $function, $apply_to_keys_also);
	 		} else {
	 			$array[$key] = $function($value);
	 		}
	 
	 		if ($apply_to_keys_also && is_string($key)) {
	 			$new_key = $function($key);
	 			if ($new_key != $key) {
	 				$array[$new_key] = $array[$key];
	 				unset($array[$key]);
	 			}
	 		}
	 	}
	 	$recursive_counter--;
	 }
	 
	 /**************************************************************
	  *
	 *	将数组转换为JSON字符串（兼容中文）
	 *	@param	array	$array		要转换的数组
	 *	@return string		转换得到的json字符串
	 *	@access public
	 *
	 *************************************************************/
	 function JSON($array) {
	 	arrayRecursive($array, 'urlencode', true);
	 	$json = json_encode($array);
	 	return urldecode($json);
	 }

	//最近浏览
	 function goodshistory($goodsResult){
	 	//echo cookie('history');
		if(cookie('history')!==""){
			$json=cookie("history");
			$json = str_replace("\\","",$json);
			$current = json_decode($json,true);
			//print_r($current);
	 	}
	 	$temp_num=count(  $current  );
		if(  $temp_num > 4 ){                        				
			$current=array_reverse($current);
			array_pop($current);                   					
			$current=array_reverse($current);    				
			$temp_num=4;
		}	
		 
		if( cookie('history')=="" ){     					
			$current[0]['id']=$goodsResult['id']; 
			$current[0]['first_cateid']=$goodsResult['first_cateid'];  
			$current[0]['name']=$goodsResult['name'];
			$current[0]['introduce']=$goodsResult['introduce'];    	
			$current[0]['coverimage']=$goodsResult['coverimage'];   
			$current[0]['memberprice']=$goodsResult['memberprice'];
			cookie('history',JSON($current),array('expire'=>3600*10,'path'=>'/'));
		}else{     													
			$temp_s=0; 											 		
			foreach( $current as $key => $value ){
				foreach( $current[$key] as $key2 => $value2 ){
					if( $value2 == $goodsResult['id'] ){ 
						$temp_s=1;
					}
				}
			}
			if(  $temp_s==0  )   									 	
			{
			$current[$temp_num]['id']=$goodsResult['id'];
			$current[$temp_num]['first_cateid']=$goodsResult['first_cateid'];
			$current[$temp_num]['name']=$goodsResult['name'];
			$current[$temp_num]['introduce']=$goodsResult['introduce'];
			$current[$temp_num]['coverimage']=$goodsResult['coverimage'];
			$current[$temp_num]['memberprice']=$goodsResult['memberprice'];
			cookie('history',JSON($current),array('expire'=>3600*10,'path'=>'/'));
			}
		}
	}
	
	//在线交易订单支付处理函数
	//函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
	//返回值：如果订单已经成功支付，返回true，否则返回false；
	function checkorderstatus($ordid){
		$Ord=M('Orderlist');
		$ordstatus=$Ord->where("out_trade_no='%s'",$ordid)->getField('ordstatus');
		if($ordstatus==1){
			return true;
		}else{
			return false;
		}
	}
	
	
	function build_order_no(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
		return $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    }
	
	function build_tv_no(){
		return $orderSn = 'TV' . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    }
	
	function build_member_no(){
		return $orderSn = 'TM' . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    }
	
	function is_mobile_request() {   
	  $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';   
	  $mobile_browser = '0';   
	  if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))   
		$mobile_browser++;   
	  if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))   
		$mobile_browser++;   
	  if(isset($_SERVER['HTTP_X_WAP_PROFILE']))   
		$mobile_browser++;   
	  if(isset($_SERVER['HTTP_PROFILE']))   
		$mobile_browser++;   
	  $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));   
	  $mobile_agents = array(   
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',   
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',   
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',   
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',   
			'newt','noki','oper','palm','pana','pant','phil','play','port','prox',   
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',   
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',   
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',   
			'wapr','webc','winw','winw','xda','xda-'  
			);   
	  if(in_array($mobile_ua, $mobile_agents))   
		$mobile_browser++;   
	  if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)   
		$mobile_browser++;   
	  // Pre-final check to reset everything if the user is on Windows   
	  if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)   
		$mobile_browser=0;   
	  // But WP7 is also Windows, with a slightly different characteristic   
	  if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)   
		$mobile_browser++;   
	  if($mobile_browser>0)   
		return true;   
	  else 
		return false;
	}
	
	//获取文件修改时间
	function getfiletime($file, $DataDir) {
		$a = filemtime($DataDir . $file);
		$time = date("Y-m-d H:i:s", $a);
		return $time;
	}
	
	//获取文件的大小
	function getfilesize($file, $DataDir) {
		$perms = stat($DataDir . $file);
		$size = $perms['size'];
		// 单位自动转换函数
		$kb = 1024;         // Kilobyte
		$mb = 1024 * $kb;   // Megabyte
		$gb = 1024 * $mb;   // Gigabyte
		$tb = 1024 * $gb;   // Terabyte
	
		if ($size < $kb) {
			return $size . " B";
		} else if ($size < $mb) {
			return round($size / $kb, 2) . " KB";
		} else if ($size < $gb) {
			return round($size / $mb, 2) . " MB";
		} else if ($size < $tb) {
			return round($size / $gb, 2) . " GB";
		} else {
			return round($size / $tb, 2) . " TB";
		}
	}
	
	function getJson($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
	}

	function doGet($url){
        //初始化
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        if ( ord( $output[0] ) == 239 && ord( $output[1] ) == 187
            && ord( $output[2] ) == 191 ) {
            //Bom头是固定的，可以检测后去除掉
            $output = substr( $output, 3 );
        }

        //释放curl句柄
        curl_close($ch);

        return $output;
    }

    function doPost($url,$post_data,$header)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 设置请求方式为post
        curl_setopt($ch, CURLOPT_POST, true);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 请求头，可以传数组
        curl_setopt($ch, CURLOPT_HEADER, $header);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        if ( ord( $output[0] ) == 239 && ord( $output[1] ) == 187
            && ord( $output[2] ) == 191 ) {
            //Bom头是固定的，可以检测后去除掉
            $output = substr( $output, 3 );
        }

        curl_close($ch);

        return $output;
	}

	function CheckSubstrs($substrs,$text){ 
		foreach($substrs as $substr) 
		if(false!==strpos($text,$substr)){ 
			return true; 
		} 
		return false; 
	}
	
	function isMobile(){ 
		$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 
		$useragent_commentsblock = preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';    
		
		$mobile_os_list = array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
		$mobile_token_list = array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod'); 
			
		$found_mobile = CheckSubstrs($mobile_os_list,$useragent_commentsblock) || CheckSubstrs($mobile_token_list,$useragent);
			
		if($found_mobile)
		{
		  return true; 
		}
		else
		{
		  return false; 
		} 
	}