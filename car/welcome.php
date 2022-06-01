<?php 
	require_once("function.php");
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if((""!=$_POST['sql'])){
			$dbh=DB_CONNE2();
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
?>
<html>
	<head>
		<title>自動車運転日報Webシステム</title>
		<link rel="shortcut icon" href="../favicon.ico">
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
	//7日間連続勤務で赤字の警告を表示
	$dbh=DB_CONNE2();
	for($i=1;$i<30;$i++){
		$temp=date('Y-m-d', strtotime("-{$i} day"));
		$sql = "SELECT * FROM `自動車運転日報` Where `日付`='{$temp}';";
		$stmt = $dbh->query($sql);
		if(0==($stmt -> rowCount())){
			break;
		}
	}
	$today = date("Y-m-d H:i:s");
	if($i>=6){//重複メールを避けるためにflggを設定した。
		//flgが０なら1にして送信という風に修正。
		//開始、終了時のメールも同様に機能追加すること
$message = <<<EO
{$today}
{$_SESSION['名前']}さんの連続勤務日数が今日で{$i}日目です。
オーバーワークに注意してください。
EO;
		// LINEでメッセージ送信
		post_message($message,"超過労働flg",date("Y-m-d"));
		echo "<h2 class='err' style='color:red'>オーバーワークに注意してください。（連続勤務日数が本日で{$i}日目です。）</h2>";
	}else{
		echo "<h6>連続勤務日数：{$i}日目です。</h6>";
	}

	if ($_SESSION["error_status"] == 3) {

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
	if (!isset($_GET['month'])){
		$_GET['month']=date('n');
	}
	
?>
<script>
function modValue(a,b,c,d,e,f,g,h,i,j,k){
	document.getElementsByName('車種')[0].value=a;
	document.getElementsByName('車番')[0].value=b;
	document.getElementsByName('日付')[0].value=c;
	document.getElementsByName('出社時メータ')[0].value=d;
	document.getElementsByName('帰社時メータ')[0].value=e;
	document.getElementsByName('開始時間')[0].value=f;
	document.getElementsByName('終了時間')[0].value=g;
	document.getElementsByName('行き先')[0].value=h;
	document.getElementsByName('備考')[0].value=i;
	document.getElementsByName('出社予定時間')[0].value=j;
	document.getElementsByName('体温')[0].value=k;
	scrollTo(0, 0);
}
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
function dpn(){
	location.href="https://moo-mikipulley.ssl-lolipop.jp/car/welcome.php?month="+Number(document.querySelector("body > div:nth-child(10) > select").value);
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
var params = (new URL(document.location)).searchParams;
var month = params.get('month');
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<form name="form1" class="t" action="regist.php" method="POST">
<?php
	echo "<div class=\"common\ name=\"日付\"><strong>日付 : </strong>";
	$year=date("Y");
	$month=date("m");
	$day=date("d");
	echo "<input type=\"date\" name=\"日付\" required value=\"{$year}-{$month}-{$day}\" style=\"width:100%\"></div>";
?>
	<div class="cs-common"><strong>運転者名</strong> : <font color="red">※必須</font>
		<select id="車種" name="車種" required onChange="changeCarInfo(0);">
		<option value="" selected></option>
		<option value="赤木 秀史">赤木 秀史</option>
		<option value="手塚 浩明">手塚 浩明</option>
		</select>
	</div>
	<div class="cs-common"><strong>車番</strong> : <font color="red">※変更不可</font>
		<select name="車番" id="車番" style="pointer-events: none;" tabindex="-1">
		<option value="" selected></option>
		<option value="BMW 川崎336せ801">BMW 川崎336せ801</option>
		<option value="ジャガー 品川351は 1028">ジャガー 品川351 は 1028</option>
		</select>
<div class="cs-common" required>体温(度): <font color='red'>*必須　（34度～40度)</font>
<select name="体温">
<option value="34">34.0</option>
<option value="34.1">34.1</option>
<option value="34.2">34.2</option>
<option value="34.4">34.3</option>
<option value="34.4">34.4</option>
<option value="34.5">34.5</option>
<option value="34.6">34.6</option>
<option value="34.7">34.7</option>
<option value="34.8">34.8</option>
<option value="34.9">34.9</option>
<option value="35">35.0</option>
<option value="35.1">35.1</option>
<option value="35.2">35.2</option>
<option value="35.3">35.3</option>
<option value="35.4">35.4</option>
<option value="35.5">35.5</option>
<option value="35.6">35.6</option>
<option value="35.7">35.7</option>
<option value="35.8">35.8</option>
<option value="35.9">35.9</option>
<option value="36">36.0</option>
<option value="36.1">36.1</option>
<option value="" selected></option>
<option value="36.2">36.2</option>
<option value="36.3">36.3</option>
<option value="36.4">36.4</option>
<option value="36.5">36.5</option>
<option value="36.6">36.6</option>
<option value="36.7">36.7</option>
<option value="36.8">36.8</option>
<option value="36.9">36.9</option>
<option value="37">37.0</option>
<option value="37.1">37.1</option>
<option value="37.2">37.2</option>
<option value="37.3">37.3</option>
<option value="37.4">37.4</option>
<option value="37.5">37.5</option>
<option value="37.6">37.6</option>
<option value="37.7">37.7</option>
<option value="37.8">37.8</option>
<option value="37.9">37.9</option>
<option value="38">38.0</option>
<option value="38.1">38.1</option>
<option value="38.2">38.2</option>
<option value="38.3">38.3</option>
<option value="38.4">38.4</option>
<option value="38.5">38.5</option>
<option value="38.6">38.6</option>
<option value="38.7">38.7</option>
<option value="38.8">38.8</option>
<option value="38.9">38.9</option>
<option value="39">39.0</option>
<option value="39.1">39.1</option>
<option value="39.2">39.2</option>
<option value="39.3">39.3</option>
<option value="39.4">39.4</option>
<option value="39.5">39.5</option>
<option value="39.6">39.6</option>
<option value="39.7">39.7</option>
<option value="39.8">39.8</option>
<option value="39.9">39.9</option>
<option value="40">40.0</option>
</select></div>
<?php
	if(""==$_GET['month']){
		$_GET['month']=intval(date('n'));
	}
	$削除ボタン下の文字列="";
	$メータ="";
	$削除ボタン下の文字列="以下のプルダウンで情報取得する月を選択してください。";
	$削除ボタン下の文字列=$削除ボタン下の文字列."<a href=export.php?month=".$_GET['month'].">{$_GET['month']}月分集計結果ＤＬ</a><br>";
	$削除ボタン下の文字列=$削除ボタン下の文字列.'<div>';
	$削除ボタン下の文字列=$削除ボタン下の文字列.'<select name="絞り込み" onchange="dpn();";>';
	for($i=1;$i<13;$i++){
		if($i==$_GET['month']){
			$削除ボタン下の文字列=$削除ボタン下の文字列.'<option value='.str_pad($_GET['month'], 2, '0', STR_PAD_LEFT).' selected>'.intval($_GET['month']).'月</option>';
			$c=$i;
		}else{
			$削除ボタン下の文字列=$削除ボタン下の文字列.'<option value='.str_pad($i, 2, '0', STR_PAD_LEFT).'>'.$i.'月</option>';
		}
	}
	$削除ボタン下の文字列=$削除ボタン下の文字列.'</select></div>';
	$dbh=DB_CONNE2();
	$sql = "SELECT * FROM `自動車運転日報` ORDER BY `日付` DESC";
	// SQLステートメントを実行し、結果を変数に格納さ
	$stmt = $dbh->query($sql);
	$color="white";
	if($stmt){
		foreach ($stmt as $row) {
			if(date('n',strtotime($row["日付"]))==$c){
				$削除ボタン下の文字列=$削除ボタン下の文字列.'<table border="1" width="100%" style="table-layout:fixed;"><tbody border-style:solid;>';
				if($row['日付']=="0000-00-00"){
					$日付="no data";
				}else{
					$日付=$row['日付'];
				}
				if($row['出社時メータ']==0){
					$出社時メータ=" -";
				}else{
					$出社時メータ=$row['出社時メータ'];
				}
				if($row['帰社時メータ']==0){
					$帰社時メータ=" -";
				}else{
					$帰社時メータ=$row['帰社時メータ'];
				}
				$走行距離=($帰社時メータ-$出社時メータ);
				if($row['開始時間']==0){
					$開始時間=" -";
				}else{
					$開始時間=$row['開始時間'];
				}
				if($row['終了時間']==0){
					$終了時間=" -";
				}else{
					$終了時間=$row['終了時間'];
				}
				if($メータ==""){
					$メータ=$帰社時メータ;
				}
				$削除ボタン下の文字列=$削除ボタン下の文字列.'<tr style="display:\"\"" class="pagetop"><td style="background-color:'.$color.';"><form method="POST" action="welcome.php"><input name="sql" type="hidden" value="DELETE FROM `自動車運転日報` WHERE 車種=\''.$row["車種"].'\' and 車番=\''.$row["車番"].'\' and 出社時メータ=\''.$row["出社時メータ"].'\' and 帰社時メータ=\''.$row["帰社時メータ"].'\' and 体温=\''.$row["体温"].'\' and 開始時間=\''.$row["開始時間"].'\' and 終了時間=\''.$row["終了時間"].'\' and 出社予定時間=\''.$row["出社予定時間"].'\' and 備考=\''.$row["備考"].'\' and 行き先=\''.$row["行き先"].'\' and 日付=\''.$row["日付"].'\'"><input value="削除" class="del" type="submit"></form>';
				$削除ボタン下の文字列=$削除ボタン下の文字列."<button"; 
				$削除ボタン下の文字列=$削除ボタン下の文字列.' type="button"';
				$削除ボタン下の文字列=$削除ボタン下の文字列." onclick=\"modValue('{$row["車種"]}','{$row["車番"]}','{$row["日付"]}','{$row["出社時メータ"]}','{$row["帰社時メータ"]}','{$row["開始時間"]}','{$row["終了時間"]}','{$row["行き先"]}','{$row["備考"]}','{$row["出社予定時間"]}','{$row["体温"]}')\">";
				$削除ボタン下の文字列=$削除ボタン下の文字列." 編集";
				$削除ボタン下の文字列=$削除ボタン下の文字列."</button>";
				$削除ボタン下の文字列=$削除ボタン下の文字列.'</td><td style="background-color:'.$color.';"></td></tr><tr><td style="background-color:'.$color.';">日付</td><td style="background-color:'.$color.';">'.$row["日付"].'</td></tr><tr><td style="background-color:'.$color.';">運転者</td><td style="background-color:'.$color.';">'.$row["車種"].'</td style="background-color:'.$color.';"></tr><tr><td style="background-color:'.$color.';">車番</td><td style="background-color:'.$color.';">'.$row["車番"].'</td></tr><tr><td style="background-color:'.$color.';">体温</td><td style="background-color:'.$color.';">'.$row["体温"]."度".'</td></tr><tr><td style="background-color:'.$color.';">行き先</td><td style="background-color:'.$color.';">'.$row["行き先"].'</td></tr><tr><td style="background-color:'.$color.';">開始時間</td><td style="background-color:'.$color.';">'.date('H:i' ,strtotime($row["開始時間"])).'</td></tr><tr><td style="background-color:'.$color.';">終了時間</td><td style="background-color:'.$color.';">'.date('H:i' ,strtotime($row["終了時間"])).'</td></tr><tr><td style="background-color:'.$color.';">出社時メータ</td><td style="background-color:'.$color.';">'.$出社時メータ.'km</td></tr><tr><td style="background-color:'.$color.';">帰社時メータ</td><td style="background-color:'.$color.';">'.$帰社時メータ.'km</td></tr><tr><td style="background-color:'.$color.';">走行距離</td><td style="background-color:'.$color.';">'.$走行距離.'km</td></tr><tr><td style="background-color:'.$color.';">備考</td><td style="background-color:'.$color.';">'.$row["備考"].'</td></tr><tr><td style="background-color:'.$color.';">出社予定時間</td><td style="background-color:'.$color.';">'.date('H:i' ,strtotime($row["出社予定時間"])).'</td></tr>';
				$削除ボタン下の文字列=$削除ボタン下の文字列."</tbody></table>";
				if($color=="white"){
					$color="lightgray";
				}else{
					$color="white";
				}
			}
		}
	}
?>
	<div class="cs-common">行き先：<input type="text" name="行き先" value=""></div>
	<div class="cs-common">開始時間：<input type="time" name="開始時間" value=""> 出社時メータ：<input type="number" name="出社時メータ" value=<?php echo $メータ; ?> style="width:20%;"> km</div>
	<div class="cs-common">終了時間：<input type="time" name="終了時間" value=""> 帰社時メータ：<input type="number" name="帰社時メータ" value="0" style="width:20%;"> km</div>
	<div class="cs-common">出社予定時間：<input type="time" name="出社予定時間" value=""></div>
	<div class="cs-common">備考：<input type="text" name="備考" value="" placeholder=""></div>
	</div>
	<input class="pagetop" type="submit" name="登録種別" value="登録";>
</form>

<?php echo $削除ボタン下の文字列; ?>
<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
<div style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</div>
</body>
</html>