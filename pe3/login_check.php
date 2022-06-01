<?php
	require_once("../function.php");
	session_start();
	header("Content-type: text/html; charset=utf-8");
	$id = $_POST['id'];
	if(strlen($id)!=6){
		$_SESSION["error_status"] = 3;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}
	$password = $_POST['password'];
	$_SESSION["id"] = $id;
	$name="";
	if(isset($_POST["startup"])){
		$name=$_POST["loginkind"];
	}
	$dbh = DB_CONNE();

	$sql = "SELECT * FROM `T-Mパスワード` WHERE id = $id";
	$stmt=$dbh->query($sql);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$timeOfTry=$result['timeOfTry'];
	$timeOfTry=intval($timeOfTry);
	if($timeOfTry>=4){
		$_SESSION["error_status"]=6;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}	
	

	$sql = "SELECT * FROM `T-Mパスワード` WHERE id = ? and pass = ?";
	$stmt = $dbh->prepare($sql, array('text','text'));
	$rec = $stmt->execute(array($id,$password));
	$rec = $stmt->fetch(PDO::FETCH_ASSOC);
	if($rec){
		try{
			$sql = "UPDATE `T-Mパスワード` SET `timeOfTry`= 1 WHERE `id` = '".$id."'";
			$stmt=$dbh->query($sql);
		}catch ( Exception $ex ){
			$_SESSION["error_status"] =4;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: login.php");
			exit();
		}

		$sql = "SELECT * FROM `T-M営業社員` where ログインID=".$id;
		$stmt = $dbh->prepare($sql);
		$rec = $stmt->execute();
		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
		$名前=$rec['名前'];
		$部署名=$rec['部署名'];
		$_SESSION['id'] = $id;
		$_SESSION['名前'] = $名前;
		$_SESSION['部署名'] = $部署名;
		if($name=="パスワード変更"){
			try{
				$sql = "UPDATE `T-Mパスワード` SET `timeOfTry`= 1, `pass`='".$_POST['passchange']."' WHERE `id` = '".$id."'";
				$stmt=$dbh->query($sql);
				$_SESSION["error_status"] =5;
			}catch ( Exception $ex ){
				$_SESSION["error_status"] =4;
			}
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: login.php");
			exit();
		}
		try{
			$sql = "UPDATE `T-Mパスワード` SET `timeOfTry`= 1 WHERE `id` = '".$id."'";
			$stmt=$dbh->query($sql);
			$_SESSION["error_status"] =5;
		}catch ( Exception $ex ){
			$_SESSION["error_status"] =4;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: login.php");
			exit();
		}

		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}else{
		$_SESSION["error_status"] = 1;
		$sql = "SELECT * FROM `T-Mパスワード` WHERE id = $id";
		$stmt=$dbh->query($sql);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$timeOfTry=$result['timeOfTry'];
		$timeOfTry=intval($timeOfTry+1);
		try{
			$sql = "UPDATE `T-Mパスワード` SET `timeOfTry`= $timeOfTry WHERE `id` = '".$id."'";
			$stmt=$dbh->query($sql);
		}catch ( Exception $ex ){
			$_SESSION["error_status"] =4;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: login.php");
			exit();
		}
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}
	exit(); 
