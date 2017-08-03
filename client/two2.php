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
		echo "<script>alert('您有一项面试正在进行中，不能开始另一项面试');window.close();</script>";
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
		echo "<script>alert('暂无等待该项面试的同学');window.close();</script>";
	}
}
if($change == 1){
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>two</title>
	<link rel="stylesheet" href="rel.css" type="text/css">
	<script src="js/jquery.min.js"></script>
</head>
<body>
	<div id="content">
		<h1>面试进行中。。。。。</h1>
       <div id="cont3">
			  <div class="display">
			  	<ul id="three">
			  		<li>学号：<span><?php echo $at_view[0]['stu_num']; ?></span></li>
			  		<li>姓名：<span><?php echo $at_view[0]['stu_name']; ?></span></li>
			  		<li>性别：<span><?php echo $at_view[0]['sex']; ?></span></li>
			  		<li>专业班级：<span><?php echo $at_view[0]['major']; ?></span></li>		
			  		<li>选择方向：<span><?php echo $at_view[0]['choice']; ?></span></li>
			  		<li>电话：<span><?php echo $at_view[0]['tel']; ?></span></li>
			  		<li>邮箱：<span><?php echo $at_view[0]['email']; ?></span></li>
			  	</ul>
			  </div>
			  <div class="intro">
			  	<p>个人简介:</p>
			  	<textarea name="comment" id="" cols="50" rows="8"><?php echo $at_view[0]['self_introd']; ?></textarea>			  	
			  </div>
			  <p class="p">一面评价:</p>
			 	<p style="text-align: center;">		   
			    <textarea name="comment" id="f_remarks" cols="50" rows="8"><?php echo $at_view[0]['f_remarks']; ?></textarea>
			    <span>得分：<input type="text" id="f_grade" value="<?php echo $at_view[0]['f_grade']; ?>" class="In"></span>
			    </p>
			    <?php if($status == 2 || $status == 4){ ?>
				    <p class="p">二面评价：</p>
				    <p style="text-align: center;">	   
				    <textarea name="comment" id="s_remarks" cols="50" rows="8"><?php echo $at_view[0]['s_remarks']; ?></textarea>
				    <span>得分：<input type="text" id="s_grade" value="<?php echo $at_view[0]['s_grade']; ?>" class="In"></span>
				    </p>
				<?php }
				if($status == 4){
				?>
				    <p class="p">三面评价：</p>
				    <p style="text-align: center;">   
				    <textarea name="comment" id="t_remarks" cols="50" rows="8"><?php echo $at_view[0]['t_remarks']; ?></textarea>
				    <span>得分：<input type="text" id="t_grade" value="<?php echo $at_view[0]['t_grade']; ?>" class="In"></span>
				    </p>
				<?php } ?>		   
			  <p class="pass" id="choose">
			  <input type="radio" value="0" name="check">通过
              <input type="radio" value="-1" name="check">不通过
			  </p>
			    <div class="pass padd">
			    	<input type="button" value="结束" class="radius" id="finish3">
			    	<input type="button" value="下一个" class="radius" id="next">
			    </div>
		</div>
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
		            if(obj.data==404)
		            {
		            	window.close();
		            }
		            window.location.href="two.php?status="+<?php echo $status; ?>
		          }
		        })
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
		            if(obj.data==404)
		            {
		            	window.close();
		            }
		            window.location.href="one.php"
		          }
		        })
	        })
		})
 	</script>
</body>
</html>
<?php } ?>