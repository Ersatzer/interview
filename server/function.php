<?php


	//创建mysql连接(面向过程方法)
	function mysqlConnect(){ 
		@$con = mysql_connect("localhost","root","199698");
		if (!$con){
		  die('Could not connect: ' . mysql_error());
		}

		mysql_select_db("vote", $con);
		mysql_query("SET NAMES utf8"); 
	}

	//创建mysql连接(面向对象方法)
	function mysqliConnect(){ 
		$conn = new mysqli("localhost","root","199698","interview");
		if (!$conn){
		  die('Could not connect: ' . mysql_error());
		}

		return $conn;
	}

	//判断学生面试状态
	function interviewStatus($status){ 
		switch($status){
			case '-2':{
				$inter_status = '面试未通过';
				return $inter_status;
			}break;
			case '-1':{
				$inter_status = '未签到';
				return $inter_status;
			}break;
			case '0':{
				$inter_status = '等待一面';
				return $inter_status;
			}break;
			case '1':{
				$inter_status = '第一次面试中';
				return $inter_status;
			}break;
			case '2':{
				$inter_status = '等待二面';
				return $inter_status;
			}break;
			case '3':{
				$inter_status = '第二次面试中';
				return $inter_status;
			}break;
			case '4':{
				$inter_status = '等待三面';
				return $inter_status;
			}break;
			case '5':{
				$inter_status = '第三次面试中';
				return $inter_status;
			}break;
			case '6':{
				$inter_status = '面试通过';
				return $inter_status;
			}break;

		}
	}
?>