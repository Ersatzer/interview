<?php
//获取面试结果
include_once "../server/function.php";
$conn = mysqliConnect();
session_start();//开启session
if(!isset($_SESSION['group_id']))
	{
		echo "<script>alert('您还未登录，请先登录');window.location.href='check.html';</script>";
	}
header("Content-Type: text/html;charset=utf-8");
//设置数据库字符集
$query = "set names utf8";
$result = $conn->query($query);
$status = @$_GET['status'];
if($status == -1){
	$code = "未签到同学";
}
else if($status == -2){
	$code = "未通过同学";
}
else if($status == 6){
	$code = "已通过同学";
}
$query = "select stu_id,stu_name,f_grade,s_grade,t_grade from interview_info where status=$status";
$result = $conn->query($query);
		for($i = 0; $i < $result->num_rows; $i++){
			$list[] = $result->fetch_assoc();
		}
		$conn->close();
?>
<html>
<head>
	<title><?php echo $code; ?></title>
	<script src="js/jquery.min.js"></script>
	<script>
	   $(function(){
		$("#quit").click(function(){
			history.go(-1);
		});
	})
	</script>
	<style type="text/css">
		body{
			background: url(img/bg_one.png);
		}
		#title{
			width: 300px;
			height: 100px;
			margin: 0 auto;
			margin-bottom: 50px;
			font-family: "楷体";
			font-weight: 300;
			font-size: 40px;
			color: #68838B;
			line-height: 100px;
		}
		#quit{
			float:right;
			margin-top: 10px;
			margin-right: 10px;
			border:1px solid white;
			outline: none;
			cursor: pointer;
			width: 80px;
			height: 40px;
			color: white;
			font-size: 32px;
			font-weight: 300;
			font-family: "楷体";
			line-height: 40px;
			background-color:rgba(223, 241, 234, 0);
		}
	</style>
</head>
<body align="center">
<button id="quit">返回</button>
	<p id="title">
			<?php echo interviewStatus($status)."同学"; ?>
	</p>
	

<?php
for($t = 0; $t < $i; $t++){

echo "	[<a href='info.php?stu_id=".$list[$t]['stu_id']."'>".$list[$t]['stu_name'].""."</a>---".$list[$t]['f_grade']."---".$list[$t]['s_grade']."---".$list[$t]['t_grade']."]、";
if($t == 6){
	echo "<br/>";
	echo "<br/>";
}
} ?>

</body>
</html>