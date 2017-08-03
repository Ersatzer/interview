<!DOCTYPE html>
<?php
	session_start();
	if(!isset($_SESSION['group_id']))
	{
		echo "<script>alert('您还未登录，请先登录');window.location.href='check.html';</script>";
	}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>进入面试状态</title>
	<link rel="stylesheet" href="rel.css" type="text/css">
	<script src="js/jquery.min.js"></script>
	<script>
	   $(function(){
		$("#obtn1").click(function(){
			window.location.href="two.php?status=0";
		}); 
		$("#obtn2").click(function(){
			window.location.href="two.php?status=2";
		}); 
		$("#obtn3").click(function(){
			window.location.href="two.php?status=4";
		});  
	})
	</script>
</head>
<body style="background: #ccc;">
     <h1>由此进入面试....</h1>
	<div id="center">
		<div class="he">
			<button id="obtn1">开始进行一面</button>
			<button id="obtn2">开始进行二面</button>
			<button id="obtn3">开始进行三面</button>
		</div>
	</div>
</body>
</html>