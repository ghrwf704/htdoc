<?php  header("Content-type: text/html; charset=utf-8");
	session_start();
	require_once("../function.php");
	isset($_SESSION['id']) ? $id = $_SESSION['id'] : $id = "";
	if ($id=="") {
		$_SESSION["error_status"] = 2;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	} 
	$_SESSION["error_status"] = 0;
	(isset($_POST["登録種別"])) ? $登録種別=$_POST["登録種別"] : $登録種別="";
	(isset($_POST["補充日"])) ? $補充日=$_POST["補充日"] : $補充日="";
	(isset($_POST["車種"])) ? $車種=$_POST["車種"] : $車種="";
	(isset($_POST["車番"])) ? $車番=$_POST["車番"] : $車番="";
	(isset($_POST["前輪"])) ? $前輪=$_POST["前輪"] : $前輪="";
	(isset($_POST["後輪"])) ? $後輪=$_POST["後輪"] : $後輪="";
	(isset($_POST["給油時メータ"])) ? $給油時メータ=$_POST["給油時メータ"] : $給油時メータ="";
	(isset($_POST["給油量"])) ? $給油量=$_POST["給油量"] : $給油量="";
	(isset($_POST["洗車日"])) ? $洗車日=$_POST["洗車日"] : $洗車日="";
	$id=$_SESSION['id'];
	$_SESSION["error_msg"] = "<div style='text-align:left'>";
	try{
		$dbh = DB_CONNE();
		if($登録種別=="登録"){
			$t=date("Y-m-d H:i:s");
			$sql = "INSERT INTO `タイヤ空気圧` (`id`, `洗車日`, `補充日`, `車種`, `車番`, `補充後前輪空気圧`, `補充後後輪空気圧`, `給油時メータ`, `給油量`) VALUES ('$id', '$洗車日', '$補充日', '$車種', '$車番', '$前輪', '$後輪', '$給油時メータ', '$給油量')";
		}
		$stmt = $dbh->query($sql);
		$dbh=null;
		$_SESSION["error_status"] = 4;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}catch( Exception $ex ){
		$_SESSION["error_status"] = 3;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}
