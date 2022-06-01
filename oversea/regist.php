<?php  header("Content-type: text/html; charset=utf-8");
	session_start();
	require_once("./function.php");
	isset($_SESSION['id']) ? $id = $_SESSION['id'] : $id = "";
	if ($id=="") {
		$_SESSION["error_status"] = 2;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	} 
	$_SESSION["error_status"] = 0;
	if(isset($_POST["datetime"])){
		$f1=explode("-", $_POST["datetime"])[0];
		$f2=explode("-", $_POST["datetime"])[1];
		$f3=explode("-", $_POST["datetime"])[2];
		$f3=explode("T",$f3)[0];
	}else{
		$f1="";
		$f2="";
		$f3="";
	}
?>
<?php
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	(isset($_POST["登録種別"])) ? $登録種別=$_POST["登録種別"] : $登録種別="";
	(isset($_POST["リセット"])) ? $リセット=$_POST["リセット"] : $リセット="";
	(isset($_POST["tid"])) ? $tid=$_POST["tid"] : $tid="";
	if($tid!=""){
		$dbh = DB_CONNE();
		$sql = "SELECT * FROM `T-Mパスワード` WHERE `id` = '$tid'";
		$stmt = $dbh->query($sql);
		foreach ($stmt as $row) {
			echo "{$tid}のtimeOfTry：".$row['timeOfTry']."→";
		}
		$pass=substr($tid, 2, 4);
		$sql = "UPDATE `T-Mパスワード` SET `pass`='{$pass}', `timeOfTry`='1' WHERE `id` = '$tid'";
		$stmt = $dbh->query($sql);
		$sql = "SELECT * FROM `T-Mパスワード` WHERE `id` = '$tid'";
		$stmt = $dbh->query($sql);
		foreach ($stmt as $row) {
			echo $row['timeOfTry']."<br>pass：".$row['pass'];
		}
		$dbh=null;
		$_POST['リセット']="";
		$_POST['tid']="";
		echo '<p><a href="#" onClick="history.back(); return false;">前のページにもどる</a></p>';
		exit();
	}
	(isset($_POST["検温値1"])) ? $検温値1=$_POST["検温値1"] : $検温値1="";
	(isset($_POST["t"])) ? $t=$_POST["t"] : $t= "";

	$f=$f1.$f2.$f3;
	$ff=$f1."/".$f2."/".$f3;
	$g1=0;
	$g2=0;
	$temp=explode("T",$_POST['datetime'])[1];
	(isset($_POST["datetime"])) ? $g1=explode(":",$temp)[0] : $g1="";
	(isset($_POST["datetime"])) ? $g2=explode(":",$temp)[1] : $g2="";
	$時=intval($g1);
	$g=intval($g2);
	if($時>24 or $g>60 or $検温値1=="" or abs($検温値1)<34 or abs($検温値1)>40){
		$_SESSION["error_msg"] = "入力値が正しくありません。";
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}
	$症状=$_POST["症状"];
	$体調=$_POST["体調"];
	if($検温値1==0 and $登録種別!="削除"){
		$_SESSION["error_msg"] = "時間の表示形式が正しくありません。";
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}
//	if((false)){
//		if($g==0){
//			$_SESSION["error_msg"] = $_SESSION["error_msg"]."検温時刻が入力されていません。<br>";
//		}
//		if($f1.$f2.$f3==""){
//			$_SESSION["error_msg"]=$_SESSION["error_msg"]."正しくない日付です。<br>";
//		}
//		$_SESSION["error_msg"] = $_SESSION["error_msg"].'<br><span class="error_top"><font color="black"><INPUT type="submit" onClick="history.back()" value="修正"></font></span></div>';
//		header("HTTP/1.1 301 Moved Permanently");
//		header("Location: welcome.php");
//		exit();
//	}
try{
	$dbh = DB_CONNE();
	if($登録種別=="更新"){
		$sql = "UPDATE `検温結果` SET `年月日`='$f',`時`=$時,`分`=$g,`検温値1`='$検温値1',`体調`='$体調',`症状`='$症状' WHERE `id` = '$id' and `t`='$t'";
	}elseif($登録種別=="登録"){
		$sql = "INSERT INTO `検温結果` (id,年月日,時,分,検温値1,体調,症状,ua) VALUES ('$id', $f, $時, $g, '$検温値1','$体調','$症状','$user_agent')";
	}elseif($登録種別=="削除"){
		$sql = "DELETE FROM `検温結果` WHERE `id` = '$id' and `t`='$t'";
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
