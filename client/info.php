<?php
//获取面生同学信息表
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
$stu_id = @$_GET['stu_id'];
$query = "select*from interview_info where stu_id=$stu_id";//获取该学生的所有信息
$result = $conn->query($query);
if($result->num_rows){
	for($i = 0; $i < $result->num_rows; $i++){
		$at_view[] = $result->fetch_assoc();
	}
	$conn->close();
}
?> -->
<!DOCTYPE html>
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<script src="js/jquery.min.js"></script> 
	
	<title>TWO</title>
	<link rel="stylesheet" type="text/css" href="two1.css">
</head>
<script src="js/jquery.min.js"></script>
</body>
<p style="display:none;" id="sex_num"><?php echo $at_view[0]['sex']; ?></p>
<script type="text/javascript">
	$(function(){
		var sex=$('#sex_num').html();
		if(sex==1)
		{
           $('#male').attr("checked",true);
		}
		if(sex==2)
		{
		   $('#female').attr("checked",true);
		}
	})
</script>
		<div class="wrapper">
			<div class="container">


				<!-- 标题和头像 -->
				<div class="title">
						<img src="img/title1.png" width="400" height="200">
				</div>
				<div class="photo">
						<img src="img/right.png" width="270" height="270">
				</div>



				<div class="base">
					<form class="form" autocomplete="off">
						<!-- 基本信息 -->
						<table class="tableOne">
							<tr>
								<td>
									<span class="baseName">学号</span>
									<input type="text" maxlength="8" readonly="readonly" value="<?php echo $at_view[0]['stu_num']; ?>" />
								</td>
								<td>
									<span class="baseName">姓名</span>
									<input type="text" name="" readonly="readonly" value="<?php echo $at_view[0]['stu_name']; ?>"/>
								</td>
							</tr>
							<tr>
								<td class="sex">
									<span class="baseName">性别</span>
									<input onclick="return false" type="radio" id="male" name="sex" value="male">
									<label for="male"><span class="sexChoose">男</span></label>
									<input onclick="return false" type="radio" id="female" name="sex" value="female"><label for="female"><span class="sexChoose">女</span></label>
								</td>
								<td>
									<span class="baseName">专业班级</span>
									<input readonly="readonly" type="text" name="" value="<?php echo $at_view[0]['major']; ?>" />
								</td>
							</tr>
								<td>
									<span class="baseName">方向</span>
									<input readonly="readonly" type="text" name="" value="<?php echo $at_view[0]['choice']; ?>" />
								</td>
								<td>
									<span class="baseName">电话</span>
									<input readonly="readonly" type="text" name="" maxlength="11" value="<?php echo $at_view[0]['tel']; ?>" />
								</td>
							<tr>
							</tr>
							<tr>
								<td class="email"  colspan="2">
									<span class="baseName">邮箱</span>
									<input readonly="readonly" type="email" name="" value="<?php echo $at_view[0]['email']; ?>" >
									<span class="expression">↖( ￣︶￣ )↗</span>
								</td>
							</tr>
						</table>

						<!-- 个人简介 -->
						<div class="introduce">
							<div class="introLeft">
								<span class="baseName">个人简介</span>
							</div>
							<div class="introRight">
								<textarea readonly="readonly"><?php echo $at_view[0]['self_introd']; ?></textarea>
							</div>
						</div>

						<!-- 面试评语及得分 -->
						<table class="comments">
							<!-- 一面评语 -->
							<tr>
								<td class="topLeft comName">一面评语</td>
								<td class="topRight comName">成绩</td>
							</tr>
							<tr>
								<td class="botLeft">
									<textarea class="content" id="f_remarks" readonly="readonly"><?php echo $at_view[0]['f_remarks']; ?></textarea>
								</td>
								<td class="botRight">
									<input class="score" readonly="readonly" maxlength="2" type="text" name="" id="f_grade" value="<?php echo $at_view[0]['f_grade']; ?>">
								</td>
							</tr>
							<?php //if($status == 2 || $status == 4){ ?>
							<!-- 二面评语 -->
							<tr>
								<td class="topLeft comName">二面评语</td>
								<td class="topRight comName">成绩</td>
							</tr>
							<tr>
								<td class="botLeft">
									<textarea readonly="readonly" class="content" id="s_remarks"><?php echo $at_view[0]['s_remarks']; ?></textarea>
								</td>
								<td class="botRight">
									<input class="score" readonly="readonly" maxlength="2" type="text" name="" id="s_grade" value="<?php echo $at_view[0]['s_grade']; ?>">
								</td>
							</tr>
							<!-- 三面评语 -->
							<tr>
								<td class="topLeft comName">三面评语</td>
								<td class="topRight comName">成绩</td>
							</tr>
							<tr>
								<td class="botLeft">
									<textarea readonly="readonly" class="content" id="t_remarks"><?php echo $at_view[0]['t_remarks']; ?></textarea>
								</td>
								<td class="botRight">
									<input class="score" readonly="readonly" maxlength="2" type="text" name="" id="t_grade" value="<?php echo $at_view[0]['t_grade']; ?>">
								</td>
							</tr>
						</table>
						<div class="foot">
						<?php if($at_view[0]['status'] == -2){
						?>
								<div class="footSon1" >
									<img src="img/no.png">
								</div>
								<?php
							}
								else if($at_view[0]['status'] == -1){
								 ?>
								 <div class="footSon1" >
									<img src="img/noSign.png">
								</div>
								<?php
							}
								else if($at_view[0]['status'] == 6){
								?>
								<div class="footSon1">
									<img src="img/yes.png">
								</div>
								<?php
								}
								?>
								<div class="footSon2">
									<input type="button" class="quit" id="quit" value="返回">
								</div>
						</div>

		</div>
	<script>
	   $(function(){
		$("#quit").click(function(){
			history.go(-1);
		});
	})
	</script>
</body>
</html>