<?php  	header("Content-type: text/html; charset=utf-8");
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
	(isset($_POST["顧客名"])) ? $顧客名=$_POST["顧客名"] : $顧客名="";
	(isset($_POST["協力会社名"])) ? $協力会社名=$_POST["協力会社名"] : $協力会社名="";
	(isset($_POST["関連会社名"])) ? $関連会社名=$_POST["関連会社名"] : $関連会社名="";
	(isset($_POST["登録種別"])) ? $登録種別=$_POST["登録種別"] : $登録種別="";
	(isset($_POST["`T-Mインプット分類`"])) ? $a=$_POST["`T-Mインプット分類`"] : $a= "";
	(isset($_POST["`T-M製品区分`"])) ? $b=$_POST["`T-M製品区分`"] : $b= "";
	(isset($_POST["`T-M製品中分類`"])) ? $c=$_POST["`T-M製品中分類`"] : $c= "";
	(isset($_POST["`T-M地域分類`"])) ? $d=$_POST["`T-M地域分類`"] : $d= "";
	(isset($_POST["`T-M活動分類`"])) ? $e=$_POST["`T-M活動分類`"] : $e= "";
	(isset($_POST["`T-M目的分類`"])) ? $h=$_POST["`T-M目的分類`"] : $f= "";
	(isset($_POST["`T-M機能分類`"])) ? $i=$_POST["`T-M機能分類`"] : $i= "";
	(isset($_POST["t"])) ? $t=$_POST["t"] : $t= "";
	(isset($_POST["year"])) ? $f1=$_POST["year"] : $f1="";
	(isset($_POST["month"])) ? $f2=$_POST["month"] : $f2="";
	(isset($_POST["day"])) ? $f3=$_POST["day"] : $f3="";
	$f=$f1.$f2.$f3;
	$ff=$f1."/".$f2."/".$f3;
	$g1=0;
	$g2=0;
	(isset($_POST["hour"])) ? $g1=(60*$_POST["hour"]) : $g1="";
	(isset($_POST["minute"])) ? $g2=$_POST["minute"] : $g2="";
	$g=$g1+$g2;
	$_SESSION["error_msg"] = "<div style='text-align:left'>";
	if(((empty($a)+empty($d)+empty($e)+empty($h)+empty($i))!=0) or ($g==0) or false==(checkdate(intval($f2), intval($f3), intval($f1)))){
		$_SESSION["error_status"] = 5;
		if(empty($d)){
			$_SESSION["error_msg"] = $_SESSION["error_msg"]."地域分類が入力されていません。<br>";
		}
		if(empty($a)){
			$_SESSION["error_msg"] = $_SESSION["error_msg"]."インプット分類が入力されていません。<br>";
		}
		if(empty($h)){
			$_SESSION["error_msg"] = $_SESSION["error_msg"]."目的分類が入力されていません。<br>";
		}
		if(empty($i)){
			$_SESSION["error_msg"] = $_SESSION["error_msg"]."機能分類が入力されていません。<br>";
		}
		if(empty($e)){
			$_SESSION["error_msg"] = $_SESSION["error_msg"]."活動分類が入力されていません。<br>";
		}
		if($g==0){
			$_SESSION["error_msg"] = $_SESSION["error_msg"]."作業時間が入力されていません。<br>";
		}
		if(false==checkdate(intval($f2), intval($f3), intval($f1))){
			$_SESSION["error_msg"]=$_SESSION["error_msg"]."正しくない日付です。<br>";
		}
		$_SESSION["error_msg"] = $_SESSION["error_msg"].'<br><span class="error_top"><font color="black"><INPUT type="submit" onClick="history.back()" value="修正"></font></span></div>';
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}
try{
	$dbh = DB_CONNE();
	if($登録種別=="更新"){
		$sql = "UPDATE `工数` SET `インプット分類`='$a',`製品区分`='$b',`機能分類`='$i',`目的分類`='$h',`製品中分類`='$c',`地域分類`='$d',`活動分類`='$e',`年月日`='$f',`分`=$g,`関連会社名`='$関連会社名',`協力会社名`='$協力会社名',`顧客名`='$顧客名' WHERE id = $id and t='$t'";
	}elseif($登録種別=="追加"){
		$sql = "INSERT INTO `工数` (id,インプット分類,製品区分,製品中分類,地域分類,活動分類,年月日,分,顧客名,協力会社名,関連会社名,機能分類,目的分類) VALUES ('$id','$a','$b','$c','$d','$e','$f',$g,'$顧客名','$協力会社名','$関連会社名','$i','$h')";
	}elseif($登録種別=="削除"){
		$sql = "DELETE FROM `工数` WHERE id = $id and t='$t'";
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
