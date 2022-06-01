<?php 
	session_start();
	if(is_null($_SESSION['名前'])){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}
	header("Content-type: text/html; charset=utf-8");
	require_once("function.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>海外日次売上・受注・注残取込システム<br>Overseas daily sales / orders / backlog capture system<br>海外日常销售/订单/积压捕获系统</title>
	</head>
<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<form name="form" action="login.php" method="POST">

<div id="top"><input type="submit" class="container" value="Logout" name="x" onclick="submitAction('welcome.php')" style><div id="indivisual"><?php echo "Base name：".$_SESSION['名前']; ?></div></div></form>
<?php
	$kubun = array('select value'=>'','0'=>'（全て）','1'=>'（受注）', '2'=>'（注残）', '3'=>'（売上）');
	if($_SESSION['名前']=='MPHK'){
		$区分=array('1','2','3');
	}else if($_SESSION['名前']=='MPTJ'){
		$区分=array('0');
	}else if($_SESSION['名前']=='MPKR'){
		$区分=array('0');
	}else if($_SESSION['名前']=='MPIN'){
		$区分=array('0');
	}else if($_SESSION['名前']=='MPSH'){
		$区分=array('0');
	}else if($_SESSION['名前']=='MPTW'){
		$区分=array('1','2','3');
	}else if($_SESSION['名前']=='MPEU'){
		$区分=array('1','2','3');
	}
?>
いずれかを選択してください。(MPKR、MPSH、MPIN、MPTJは「0（全て）」を選択すること)
<BR>Please select one. (For MPKR、MPSH、MPIN and MPTJ,  please select 「０（全て）」)
<br>请选择一项。 （MPKR、MPSH、MPIN 和 MPTJ 选择「0（全て）」）
<br>
<br>
<form action="upload.php" method="post" enctype="multipart/form-data">
<p>select value</p>
区分：<select name="区分" id="select_box">
<?php
//	echo "<option value='select value'>select value</option>";
	for($i=0;$i<count($区分);$i++) {;
		echo "<option value='{$区分[$i]}'>{$区分[$i]}{$kubun[$区分[$i]]}</option>";
	}
?>
</select><br>
<p id="table"><br></p>
<input type="text" id="テーブル" name="table" value="" style="display:none">
  <input type="file" name="upfile" / required>
  <input type="submit" id="up" value="upload"/>
</form>
<?php
	$id = $_SESSION['id'];
	if((isset($_POST["x"])) and ($_POST["x"]==="Logout")){
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000);
			session_destroy();
			unset($_SESSION["id"]);
			unset($_SESSION["error_status"]);
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: login.php");
			exit();
		}
	}
	echo "<br><div id=\"temp\">";
	echo "</div>";
?>
<strong>※Translated into English and Chinese</strong><br>
"受注" ⇒ "Sales order"、"受注"<br>
"注残" ⇒ "Outstanding order"、"注残"<br>
"売上" ⇒ "Sales amount"、"销售额"<br>
”ファイルを選択” ⇒ ”Select file”、”选择文件 选择文件”<br>

	<meta name="viewport" id="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
</body>
		<?php echo '<p style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</p>'; ?>

</html>
