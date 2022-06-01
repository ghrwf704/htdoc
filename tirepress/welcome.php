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
<script>
function changeCarInfo(j){
	var no=document.getElementsByName('車種')[j].selectedIndex;
	document.getElementsByName('車番')[j].options[no].selected = true;
	document.getElementsByName('指定空気圧')[j].options[no].selected = true;
	val=document.getElementsByName('指定空気圧')[j].value
	前輪=val.split("　")[0];
	後輪=val.split("　")[1];
	document.getElementsByName('指定空気圧前輪')[0].value = "指定空気圧(前輪):"+前輪;
	document.getElementsByName('指定空気圧後輪')[0].value = "指定空気圧(後輪):"+後輪;
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
<meta name="viewport" id="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<form name="form1" class="t" action="regist.php" method="POST">
<?php
	echo "<div class=\"common\ name=\"日付\"><strong>日付 : </strong>";
	$year=date("Y");
	$month=date("m");
	$day=date("d");
	echo "<input type=\"date\" name=\"補充日\" required value=\"{$year}-{$month}-{$day}\" style=\"width:100%\"></div>";
?>
	<div class="cs-common"><strong>車種</strong> : <font color="red">※必須</font>
		<select id="車種" name="車種" required onChange="changeCarInfo(0);">
		<option value="" selected></option>
		<option value="バネット">バネット</option>
		<option value="MAZDA3">MAZDA3</option>
		<option value="ヤリス">ヤリス</option>
		</select>
	</div>
	<div class="cs-common"><strong>車番</strong> : <font color="red">※変更不可</font>
		<select name="車番" id="車番" style="pointer-events: none;" tabindex="-1">
		<option value="" selected></option>
		<option value="相模502ね1079">相模502ね1079</option>
		<option value="福山300も1744">福山300も1744</option>
		<option value="相模502は8071">相模502は8071</option>
		</select>
	</div>
	<hr>
	チェックすると入力項目が表示されます。
	<hr>
	<label class="check_lb">
		<input type="checkbox" name="給油" value="給油">給油
		<p><strong>給油時ＯＤＯメータ</strong> : 
			</label>
			<label class="check_lb">
			<input type="number" name="給油時メータ" value="">km
			</label>
			<label class="check_lb">
			<strong>給油量（小数点第2位まで入力）</strong> : 
			</label>
			<label class="check_lb">
			<input type="number" name="給油量" value="" step="0.01">ℓ
			</label><hr>
		</p>
	</label>
	<label class="check_lb">
		<input type="checkbox" name="洗車" value="洗車">洗車
		<p><strong>洗車日 : </strong>
		<input type="date" name="洗車日" value="" style=\"width:100%\"></div>
	</label></p>
	<hr>
	<label class="check_lb">		
		<input type="checkbox" name="空気圧" value="空気圧">空気圧（チェック・補充をしたら入力）
		<p><strong>前輪 : </strong>　<!--<input type="" name="車の名前" value="" disabled>-->
		<label class="check_lb">
		<input type="number" name="前輪" value="">KPA　<input type="" name="指定空気圧前輪" value="" disabled>
		</label>
		<label class="check_lb">
		<strong>後輪 :</strong> 
		</label>
		<label class="check_lb">
		<input type="number" name="後輪" value="">KPA　<input type="" name="指定空気圧後輪" value="" disabled>
		</label></label></p>

	<div style="display:none" class="cs-common"><strong>指定空気圧</strong> : <font color="red">※変更不可</font>
		<select name="指定空気圧" style="pointer-events: none;" tabindex="-1">
		<option value="" selected></option>
		<option value="300KPA　300KPA">300KPA　300KPA</option>
		<option value="250KPA　250KPA">250KPA　250KPA</option>
		<option value="250KPA　240KPA">250KPA　240KPA</option>
		</select>
	</div>
	<hr>
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
	echo '<tr><td></td><td>日付</td><td>車種</td><td>車番</td><td>給油時メータ</td><td>給油量</td><td>洗車日</td><td>前輪圧</td><td>後輪圧</td></tr>';
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
			if($row['給油時メータ']==0){
				$給油時メータ="　-";
			}else{
				$給油時メータ=$row['給油時メータ']."km";
			}
			if($row['給油量']==0){
				$給油量="　-";
			}else{
				$給油量=$row['給油量']."ℓ";
			}
			if($row['補充後前輪空気圧']==0){
				$補充後前輪空気圧="　-";
			}else{
				$補充後前輪空気圧=$row['補充後前輪空気圧']."KPA";
			}
			if($row['補充後後輪空気圧']==0){
				$補充後後輪空気圧="　-";
			}else{
				$補充後後輪空気圧=$row['補充後後輪空気圧']."KPA";
			}
			echo '<tr class="pagetop"><td><form method="POST" action="welcome.php"><input name="sql" type="hidden" value="DELETE FROM `タイヤ空気圧` WHERE 車種=\''.$row["車種"].'\' and 車番=\''.$row["車番"].'\' and 洗車日=\''.$row["洗車日"].'\' and 補充日=\''.$row["補充日"].'\'"><input value="削除" class="del" type="submit"></form></td><td>'.$補充日.'</td><td>'.$row["車種"].'</td><td>'.$row["車番"].'</td><td>'.$給油時メータ.'</td><td>'.$給油量.'</td><td>'.$洗車日.'</td><td>'.$補充後前輪空気圧.'</td><td>'.$補充後後輪空気圧.'</td></tr>';
		}
	}
	echo "</tbody></table>";
?>
<p style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</label>
</body>
</html>
