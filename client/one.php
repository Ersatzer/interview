<!DOCTYPE html>
<?php
	session_start();
	if(!isset($_SESSION['group_id']))
	{
		echo "<script>window.location.href='check.html';</script>";
	}
?> 
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>进入面试状态</title>
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
		$("#quit").click(function(){
			window.location.href="../server/logout.php";
		});
		$("#asd").click(function(){
			window.location.href="results.html";
		});
	})
	</script>
	<style>
		* {
			margin: 0;
			padding: 0;
		}
		body{
			background: url(img/bg_one.png);
		}
		#content{
			width: 500px;
			height: 500px;
			position: absolute;
			left: 0;right: 0;top: 0;bottom: 0;
			margin:auto;
			background:url(img/bg_one1.png) no-repeat center;
		}
		.footRight{
			float: left;
			width: 550px;
			height: 160px;
			margin-top: 10px;
		}
		#obtn1{
			margin-top: 15px;
			
		}
		#obtn2{
			margin-top: 45px;
		}
		#obtn3{
			margin-top: 45px;
		}
		.button{
			float: left;
			width: 300px;
			height: 100px;
			margin-left: 100px;
			border:none;
			outline: none;
			cursor: pointer;

			color: white;
			font-size: 32px;
			font-weight: 300;
			font-family: "楷体";
			line-height: 40px;
			background-color:rgba(223, 241, 234, 0);
		}
		#quit{
			float: right;
			width: 60px;
			height: 30px;
			border:1px solid white;
			outline: none;
			cursor: pointer;
			margin-top: 10px;
			margin-left: 0px;
			margin-right: 10px;
			color: white;
			font-size: 23px;
			font-weight: 300;
			font-family: "楷体";
			line-height: 30px;
			background-color:rgba(223, 241, 234, 0);
		}
		#asd{
			float: right;
			width: 110px;
			height: 30px;
			border:1px solid white;
			outline: none;
			cursor: pointer;
			margin-top: 10px;
			margin-right: 10px;
			margin-left: 20px;
			color: white;
			font-size: 23px;
			font-weight: 300;
			font-family: "楷体";
			line-height: 30px;
			background-color:rgba(223, 241, 234, 0);
		}
	</style>
</head>
<body>
	<div id="content">
			<button id="obtn1" class="button">开始进行一面</button>
			<button id="obtn2" class="button">开始进行二面</button>
			<button id="obtn3" class="button">开始进行三面</button>
	</div>
	<button id="quit" class="button">退出</button>
	<button id="asd" class="button">信息统计</button>
</body>
</html>