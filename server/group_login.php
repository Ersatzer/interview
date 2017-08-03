<?php
//验证面试组登陆
include_once "function.php";
$conn = mysqliConnect();
header("Content-Type: text/html;charset=utf-8");
//设置数据库字符集
$query = "set names utf8";
$result = $conn->query($query);
session_start();//开启session
$passphrase = $_POST['passphrase'];
//$passphrase = '2018';
$query = "select group_id from interviewer_status where passphrase='$passphrase'";
$result = $conn->query($query);
if($result->num_rows){
	$group_id = $result->fetch_assoc();
	$_SESSION['group_id'] = $group_id['group_id'];//将面试组id保存在session中
	$return =  array('code' => -1,
		'code' => 0,
		'msg' => '登陆成功！', 
		);
}
else{
	$return =  array(
		'code' => -1,
		'msg' => '验证码有误，请重新输入！', 
		);
}
echo json_encode($return);
$conn->close();
?>