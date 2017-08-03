<?php
//显示面试相关信息
include_once "function.php";
$conn = mysqliConnect();
header("Content-Type: text/html;charset=utf-8");
//设置数据库字符集
$query = "set names utf8";
$result = $conn->query($query);
//获取面试区信息
$query = "select group_name,stu_name,stu_num,status from interview_info,interviewer_status,interview_flow where interview_info.stu_id=interview_flow.stu_id and interviewer_status.group_id=interview_flow.group_id";

$result = $conn->query($query);

if($result->num_rows){
	for($i = 0; $i < $result->num_rows; $i++){
		$at_view[] = $result->fetch_assoc();
		$at_view[$i]['status'] = interviewStatus($at_view[$i]['status']);
	}
}
else{
	$at_view = array();
}

//获取等待面试的学生姓名学号
$query = "select stu_num,stu_name,status from interview_info where status=0 or status=2 or status=4";
$result = $conn->query($query);
if($result->num_rows){
	for($i = 0; $i < $result->num_rows; $i++){
		$wait_view[] = $result->fetch_assoc();
		$wait_view[$i]['status'] = interviewStatus($wait_view[$i]['status']);
	}
}
else{
	$wait_view = array();
}

$return = array(
	array(
		'msg' => '面试区信息',
		'data' => $at_view
		),
	array(
		'msg' => '等待面试的学生信息',
		'data' => $wait_view
		),
	);
echo json_encode($return);
$conn->close();
?>