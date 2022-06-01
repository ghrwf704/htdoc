<?php 
	require_once("../function.php");
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if((""!=$_POST['sql'])){
			$dbh=DB_CONNE();
			$stmt = $dbh->query($_POST["sql"]);
			$dbh=null;
			$_POST["sql"]="";
		}
	}
	session_start();
	if(is_null($_SESSION['名前'])){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}
	header("Content-type: text/html; charset=utf-8");
	require_once("../function.php");
?>
<html>
	<head>
		<title>給油管理システム</title>
	</head>
<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<form name="form" action="login.php" method="POST">
<div id="top"><div id="indivisual"><?php echo $_SESSION['名前']."さん"; ?></div><input type="submit" class="container" value="ログアウト" name="x" onclick="submitAction('welcome.php')" style></div>
</form>
<script>
$(document).ready(function() {
  var pagetop = $('.pagetop');
    $(window).scroll(function () {
       if ($(this).scrollTop() > 100) {
            pagetop.fadeIn();
       } else {
            pagetop.fadeOut();
	            }
       });
       pagetop.click(function () {
           $('body, html').animate({ scrollTop: 0 }, 500);
		$("#temp").remove()
              return true;
   });
});
</script>
<?php
	$id = $_SESSION['id'];
	if((isset($_POST["x"])) and ($_POST["x"]==="ログアウト")){
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
	if (!isset($_SESSION['id'])){
		$_SESSION["error_status"] = 0;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}
	if ($_SESSION["error_status"] == 3) {
		echo "<h2 class='err' style='color:red'>DB接続中にエラーが発しました。</h2>";
	}
	if ($_SESSION["error_status"] == 4) {
		echo "<h2 class='err' style='color:red'>処理が完了しました。</h2>";
	}
	if ($_SESSION["error_status"] == 5) {
		if(isset($_SESSION["error_msg"])){echo "<h4 class='err' style='color:red'>".$_SESSION["error_msg"]."</h4>";}
	}	
	if ($_SESSION["error_status"] == 7) {
		echo "<h2 class='err' style='color:red'>変更する場合は編集ボタンを押してください</h2>";
	}
	if ($_SESSION["error_status"] == 8) {
		echo "<h2 class='err' style='color:red'>検温値を選択してください。</h2>";
	}
	$_SESSION["error_status"]=0;
	echo "</div>";
	$year=date("Y");
	$month=date("m");
	$day=date("d");
	$時=date("H");
	$分=date("i");
?>
	<meta name="viewport" id="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<form name="form1" class="t" action="regist.php" method="POST">
<label class="check_lb">
<strong>補充日</strong> : 
</label>
<label class="check_lb">
<?php
	echo "<input type=\"date\" name=\"補充日\" value=\"{$year}-{$month}-{$day}\">";
?>
</label>

<!--車種、車番、空気圧をプルダウンで連動して選択する（一度選択したらその後空白は選べなくなる）-->
<script>
function modPage(t) {
	displayNone();
	a=t.split("、");
	for(i=0;i<a.length;i++){
		var name=a[i].split("：")[0];
		var status=a[i].split("：")[1];
		var value=a[i].split("：")[2];
		if(status=="value"){
			document.getElementsByName(name)[0].value=value;
		}else{
			if(value=="true"){
				document.getElementsByName(name)[0].checked=true;
			}else if(value=="false"){
				document.getElementsByName(name)[0].checked=false;
			}
		}
	}
}
function changeCarInfo(){
	var no=document.getElementsByName('車種')[0].selectedIndex;
	document.getElementsByName('車番')[0].options[no].selected = true;
	document.getElementsByName('指定空気圧')[0].options[no].selected = true;
}
function displayNone(){
	str=document.querySelector("body > form.t > label:nth-child(37) > table > tbody").innerHTML;
	if ( str.indexOf('バネット') != -1) {
		document.getElementsByName("車種")[0][1].style.display="none";
	}else{
		document.getElementsByName("車種")[0][1].style.display="block";
	}
	if ( str.indexOf('MAZDA3') != -1) {
		document.getElementsByName("車種")[0][2].style.display="none";
	}else{
		document.getElementsByName("車種")[0][2].style.display="block";
	}
	if ( str.indexOf('ヤリス') != -1) {
		document.getElementsByName("車種")[0][3].style.display="none";
	}else{
		document.getElementsByName("車種")[0][3].style.display="block";
	}
}
//日付の初期表示
window.onload = function() {
	//車種名確認
	var date = new Date();
	var year = date.getFullYear();
	var month = date.getMonth()+1;
	var day = date.getDate();
	document.querySelector("body > form.t > input[type=date]:nth-child(2)").value=year + '-' + ('0' + month).slice(-2) + '-' + ('0' + day).slice(-2);
	displayNone();
}

</script>
<div class="cs-common"><strong>車種</strong> : <font color="red">※必須</font>
<select id="車種" name="車種" required onChange="changeCarInfo();">
<option value="" selected></option>
<option value="バネット">バネット</option>
<option value="MAZDA3">MAZDA3</option>
<option value="ヤリス">ヤリス</option>
</select></div>
<!--変更できません--　ヨシガエ>
<div class="cs-common">車番 : 
<!--変更できません　end-->
<div class="cs-common"><strong>車番</strong> : <font color="red">※変更不可</font>
<select name="車番" id="車番" style="pointer-events: none;" tabindex="-1">
<option value="" selected></option>
<option value="相模502ね1079">相模502ね1079</option>
<option value="福山300も1744">福山300も1744</option>
<option value="相模502は8071">相模502は8071</option>
</select></div>
<hr>
<strong>前輪</strong> : 
</label>
<label class="check_lb">
<input type="number" name="前輪" value="">KPA
</label>
<label class="check_lb">
<strong>後輪</strong> : 
</label>
<label class="check_lb">
<input type="number" name="後輪" value="">KPA
</label>

<div class="cs-common"><strong>指定空気圧</strong> : <font color="red">※変更不可</font>
	<select name="指定空気圧" style="pointer-events: none;" tabindex="-1">
	<option value="" selected></option>
	<option value="前 300KPA　後 300KPA">前 300KPA　後 300KPA</option>
	<option value="前 250KPA　後 250KPA">前 250KPA　後 250KPA</option>
	<option value="前 250KPA　後 240KPA">前 250KPA　後 240KPA</option>
	</select>
</div>
<label class="check_lb">
<div>
<strong>洗車日</strong> : 
<label class="check_lb">
<?php
	echo "<input type=\"date\" name=\"洗車日\" value=\"\">";
?>
</label></div>

</label>
<label class="check_lb">
<input class="pagetop" type="submit" name="登録種別" value="登録";>
</label>
</form>

<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
<?php
	$補充日="";
	$洗車日="";
	echo date("Y")."/".date("m")."/".date("d")."：登録済み一覧";
	$dbh=DB_CONNE();
	$sql = "SELECT * FROM `タイヤ空気圧` ORDER BY `補充日` DESC";
	// SQLステートメントを実行し、結果を変数に格納さ
	$stmt = $dbh->query($sql);
	echo '<table border="1" style="width: 100%;"><tbody border-style:solid;>';
	echo '<tr><td></td><td>補充日</td><td>車種</td><td>車番</td><td>前輪圧</td><td>後輪圧</td><td>洗車日</td></tr>';
	if($stmt){
		foreach ($stmt as $row) {
			if($row['補充日']=="0000-00-00"){
				$補充日="no data";
			}else{
				$補充日=$row['補充日'];
			}
			if($row['洗車日']=="0000-00-00"){
				$洗車日="no data";
			}else{
				$洗車日=$row['洗車日'];
			}
			echo '<tr class="pagetop"><td><form method="POST" action="welcome.php"><input name="sql" type="hidden" value="DELETE FROM `タイヤ空気圧` WHERE 車種=\''.$row["車種"].'\' and 車番=\''.$row["車番"].'\' and 洗車日=\''.$row["洗車日"].'\' and 補充日=\''.$row["補充日"].'\'"><input value="削除" class="del" type="submit"></form></td><td>'.$補充日.'</td><td>'.$row["車種"].'</td><td>'.$row["車番"].'</td><td>'.$row["補充後前輪空気圧"].'</td><td>'.$row["補充後後輪空気圧"].'</td><td>'.$洗車日.'</td></tr>';
		}
	}
	echo "</tbody></table>";
?>

<!--	<font color="red">データ一覧は、POWEREGGの「データ共有」→「Webデータベース」→「総務関連」の自動車運転日報(DB)から閲覧できます。</font> -->
		<p style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</label>
</body>
</html>
