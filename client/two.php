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
$status = @$_GET['status'];
$group_id = $_SESSION['group_id']; 
//$status = 2;
//$group_id = 3;
$query = "select stu_id from interview_flow where group_id=$group_id";
$result = $conn->query($query);
$id = $result->fetch_assoc();
//防止页面刷新导致正在面试的同学无法进行面试的情况
if($id['stu_id'] != 0){
	$query = "select status from interview_info where stu_id=".$id['stu_id']."";
	$result = $conn->query($query);
	$status_1 = $result->fetch_assoc();
	if($status_1['status'] == ($status+1)){
		$change = 1;
		$query = "select*from interview_info where stu_id=".$id['stu_id']."";
		$result = $conn->query($query);
		for($i = 0; $i < $result->num_rows; $i++){
			$at_view[] = $result->fetch_assoc();
			$at_view[$i]['status'] = interviewStatus($at_view[$i]['status']);
		}
		$conn->close();
	}
	else{
		echo "<script>alert('您有一项面试正在进行中，不能开始另一项面试');window.location.href='one.php';</script>";
	}
}
else{
	$query = "lock tables interview_info write";//加写锁
	$result = $conn->query($query);
	$query = "select*from interview_info where status=$status limit 1";//获取等待面试的第一位学生
	$result = $conn->query($query);
	if($result->num_rows){
		$change = 1;
		for($i = 0; $i < $result->num_rows; $i++){
			$at_view[] = $result->fetch_assoc();
			$at_view[$i]['status'] = interviewStatus($at_view[$i]['status'] + 1);
		}
		$query = "unlock tables";//清除写锁
		$result = $conn->query($query);
		$query = "update interview_info set status=status+1 where stu_id='".$at_view['0']['stu_id']."'";//更新学生面试状态
		$result = $conn->query($query);
		$query = "update interview_flow set stu_id='".$at_view['0']['stu_id']."' where group_id=$group_id";//更新面试流水表
		$result = $conn->query($query);
		$conn->close();
	}
	else{
		$change = 0;
		$query = "unlock tables";
		$result = $conn->query($query);
		$conn->close();
		echo "<script>alert('暂无等待该项面试的同学');window.location.href='one.php';</script>";
	}
}
if($change == 1){
?>
<!DOCTYPE html>
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	
	<title>TWO</title>
	<link rel="stylesheet" type="text/css" href="css/two.css">
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
									<input onclick="return false" type="radio" id="male" name="sex" value="male" >
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
									<textarea class="content" id="f_remarks"><?php echo $at_view[0]['f_remarks']; ?></textarea>
								</td>
								<td class="botRight">
									<input class="score" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="2" type="text" name="" id="f_grade" value="<?php echo $at_view[0]['f_grade']; ?>">
								</td>
							</tr>
							<!-- 二面评语 -->
							<tr>
								<td class="topLeft comName">二面评语</td>
								<td class="topRight comName">成绩</td>
							</tr>
							<tr>
								<td class="botLeft">
									<textarea class="content" id="s_remarks"><?php echo $at_view[0]['s_remarks']; ?></textarea>
								</td>
								<td class="botRight">
									<input class="score" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="2" type="text" name="" id="s_grade" value="<?php echo $at_view[0]['s_grade']; ?>">
								</td>
							</tr>
							<!-- 三面评语 -->
							<tr>
								<td class="topLeft comName">三面评语</td>
								<td class="topRight comName">成绩</td>
							</tr>
							<tr>
								<td class="botLeft">
									<textarea class="content" id="t_remarks"><?php echo $at_view[0]['t_remarks']; ?></textarea>
								</td>
								<td class="botRight">
									<input class="score" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="2" type="text" name="" id="t_grade" value="<?php echo $at_view[0]['t_grade']; ?>">
								</td>
							</tr>
						</table>
						<!-- 页脚按钮 -->
						<div class="foot">
							<!-- 通过、否决按钮 -->
							<div class="footLeft" id="choose">
								<input class="decision" id="pass" type="radio" name="decision" value="0">
								<label for="pass"> <span class="deName">通过</span><br /></label>
								<input class="decision" id="npass" type="radio" name="decision" value="-1">
								<label for="npass"><span class="deName">否决</span><br /></label>
							</div>
							<!-- 结束、下一个按钮 -->
							<div class="footRight">
								<div class="rightSon1">
									<input type="button" class="end" name="" id="next">
								</div>
								<div class="rightSon2">
									<input type="button" class="end" name="" id="finish3">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- 背景浮动方块特效 -->
			<ul class="bg-bubbles">
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#next").click(function(){
				<?php if($status == 0){ ?>
				var remarks=$('#f_remarks').val();
		        var grade=$('#f_grade').val();
		        <?php
		    	}
		    	else if($status == 2){ ?>
		    	var remarks=$('#s_remarks').val();
		        var grade=$('#s_grade').val();
		        <?php
		    	}
		    	else if($status == 4){ ?>
		    	var remarks=$('#t_remarks').val();
		        var grade=$('#t_grade').val();
		        <?php
		    	}	
		        ?>
		        var view_result=$('#choose input:radio:checked').val();
		         if(view_result)
		         {
		         	   $.ajax({
			          url:'../server/save_info.php',
			          type:'POST',
			          data:{
			            'remarks':remarks,
			            'grade':grade,
			            'view_result':view_result,
			            'status':<?php echo $status; ?>,
			            'stu_id':<?php echo $at_view[0]['stu_id']; ?>
			          },
			          success:function(data){ 
			            var obj=eval("("+data+")");
			            alert(obj.msg);
			            window.location.href="two.php?status="+<?php echo $status; ?>
			          }
			        })
		         }
		         else
		         {
		         	alert("请选择通过或不通过！");
		         }
		       
	        })
	        $("#finish3").click(function(){
				<?php if($status == 0){ ?>
				var remarks=$('#f_remarks').val();
		        var grade=$('#f_grade').val();
		        <?php
		    	}
		    	else if($status == 2){ ?>
		    	var remarks=$('#s_remarks').val();
		        var grade=$('#s_grade').val();
		        <?php
		    	}
		    	else if($status == 4){ ?>
		    	var remarks=$('#t_remarks').val();
		        var grade=$('#t_grade').val();
		        <?php
		    	}	
		        ?>
		        var view_result=$('#choose input:radio:checked').val();
		        if(view_result)
		        {
		        	 $.ajax({
			          url:'../server/save_info.php',
			          type:'POST',
			          data:{
			            'remarks':remarks,
			            'grade':grade,
			            'view_result':view_result,
			            'status':<?php echo $status; ?>,
			            'stu_id':<?php echo $at_view[0]['stu_id']; ?>
			          },
			          success:function(data){ 
			            var obj=eval("("+data+")");
			            alert(obj.msg);
			            window.location.href="one.php"
			          }
			        })
		        }
		        else
		         {
		         	alert("请选择通过或不通过！");
		         }
		        
	        })
		})
 	</script>
</body>
</html>
<?php } ?>