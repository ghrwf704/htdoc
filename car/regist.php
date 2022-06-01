<?php  header("Content-type: text/html; charset=utf-8");
	session_start();
	require_once("function.php");
	isset($_SESSION['id']) ? $id = $_SESSION['id'] : $id = "";
	if ($id=="") {
		$_SESSION["error_status"] = 2;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	} 
	$_SESSION["error_status"] = 0;
	(isset($_POST["登録種別"])) ? $登録種別=$_POST["登録種別"] : $登録種別="";
	(isset($_POST["日付"])) ? $日付=$_POST["日付"] : $日付="";
	(isset($_POST["車種"])) ? $車種=$_POST["車種"] : $車種="";
	(isset($_POST["車番"])) ? $車番=$_POST["車番"] : $車番="";
	(isset($_POST["体温"])) ? $体温=$_POST["体温"] : $体温="";
	(isset($_POST["開始時間"])) ? $開始時間=$_POST["開始時間"] : $開始時間="";
	(isset($_POST["終了時間"])) ? $終了時間=$_POST["終了時間"] : $終了時間="";
	(isset($_POST["出社時メータ"])) ? $出社時メータ=$_POST["出社時メータ"] : $出社時メータ="";
	(isset($_POST["出社予定時間"])) ? $出社予定時間=$_POST["出社予定時間"] : $出社予定時間="";
	(isset($_POST["行き先"])) ? $行き先=$_POST["行き先"] : $行き先="";
	(isset($_POST["帰社時メータ"])) ? $帰社時メータ=$_POST["帰社時メータ"] : $帰社時メータ="";
	(isset($_POST["備考"])) ? $備考=$_POST["備考"] : $備考="";

$開始 = <<<EOS
本日の業務を開始いたします。
日付　　：{$日付}
運転者名：{$車種}
車番　　：{$車番}
体温　　：{$体温}℃
開始時間：{$開始時間}
メータ　：{$出社時メータ}(km)
行き先　：{$行き先}
備考　　：{$備考}
よろしくお願いいたします。
EOS;

$走行距離=$帰社時メータ-$出社時メータ;

$完了 = <<<EOE
本日の業務が完了いたしましたので以下の通りご報告します。
日付　　：{$日付}
運転者名：{$車種}
車番　　：{$車番}
体温　　：{$体温}℃
開始時間：{$開始時間}
終了時間：{$終了時間}
走行距離：{$走行距離}(km)
行き先　：{$行き先}
出社時間：{$出社予定時間}
備考　　：{$備考}
よろしくお願いいたします。
EOE;
	$id=$_SESSION['id'];
	$_SESSION["error_msg"] = "<div style='text-align:left'>";
	try{
		$dbh = DB_CONNE2();
		if($登録種別=="登録"){
			$sql = "INSERT INTO `自動車運転日報` (`id`, `日付`, `車種`, `車番`,`体温`, `出社時メータ`, `帰社時メータ`,`備考`,`開始時間`,`終了時間`,`行き先`,`出社予定時間`) VALUES ('$id','$日付', '$車種', '$車番','$体温','$出社時メータ','$帰社時メータ','$備考','$開始時間', '$終了時間','$行き先','$出社予定時間')";
			$stmt = $dbh->query($sql);
			$dbh=null;
			if($終了時間==null){
				post_message($開始,'開始flg',$日付);
			}else if($終了時間!="00:00:00"){
				post_message($完了,'完了flg',$日付);
			}
		}
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
