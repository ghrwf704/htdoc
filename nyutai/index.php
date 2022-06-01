<?php
	require("../function.php");
	if("121.1.251.131"!=$_SERVER['HTTP_X_FORWARDED_FOR'] and "203.180.185.22"!=$_SERVER['HTTP_X_FORWARDED_FOR']){
		echo "外部からは閲覧できません。";
		exit();
	}
	$now=date("Y-m-d H:i:s");
	ini_set("display_errors", "Off");
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if((""!=$_POST['sql'])){
			$dbh=DB_CONNE();
			$stmt = $dbh->query($_POST["sql"]);
			$dbh=null;
			$_POST["sql"]="";
		}
		if((""!=$_POST['入室'])){
		if((""!=$_POST['退室'])){
		if((""!=$_POST["年月日"])){
			$_POST["入室"]=str_replace(":","",$_POST["入室"]);
			$_POST["退室"]=str_replace(":","",$_POST["退室"]);
			$入室=$_POST["入室"];
			$退室=$_POST["退室"];
			$作業内容=$_POST["作業内容"];
			$テキスト=$_POST["テキスト"];
			$名前=$_POST["名前"];
			$年月日=$_POST["年月日"];
			$dbh=DB_CONNE();
			$sql = "INSERT INTO `入退室管理`(`年月日`, `入室時刻`, `退室時刻`, `名前`, `作業内容`, `備考`, `t`) VALUES ('{$年月日}', '{$入室}','{$退室}','{$名前}','{$作業内容}', '{$テキスト}', '{$now}')";
			$stmt = $dbh->query($sql);
			$dbh=null;
		}
		}
		}
	}
	$_POST["入室"]=null;
	$_POST["退室"]=null;
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>サーバ室入退管理システム</title>
	</head>
	<body>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<h2>サーバ室入退管理システム</h2>
			<table width="100%">
				<tbody>
					<tr><td nowrap>日付(*)</td><td nowrap>名前</td><td nowrap>入室(*)</td><td nowrap>退室(*)</td><td nowrap></td><td width="35%">備考</td><td nowrap></td></tr>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input style="display:none;" type="radio" name="名前" value="川里誠司:1O20:003109" checked>川里誠司</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td><input type="hidden" value=></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input style="display:none;" type="radio" name="名前" value="井上博昭:1O20:001676" checked>井上博昭</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input style="display:none;" type="radio" name="名前" value="左治木康夫:1O20:000931" checked>左治木康夫</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input style="display:none;" type="radio" name="名前" value="黒岩勇介:1O20:003519" checked>黒岩勇介</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input style="display:none;" type="radio" name="名前" value="池辺信一:1O20:002845" checked>池辺信一</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input style="display:none;" type="radio" name="名前" value="吉ヶ江慧:1O20:003553" checked>吉ヶ江慧</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input style="display:none;" type="radio" name="名前" value="伊藤義雄:1O20:000542" checked>伊藤義雄</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
				</tbody>
			</table>
			<?php
				$dbh=DB_CONNE();
				$sql = "SELECT * FROM `入退室管理` ORDER BY `年月日` DESC";
				// SQLステートメントを実行し、結果を変数に格納さ
				$stmt = $dbh->query($sql);
				echo '<table border="1" width="100%"><tbody border-style:solid;>';
				echo "<tr><td nowrap>日付</td><td nowrap>名前</td><td nowrap>入室時刻</td><td nowrap>退室時刻</td><td nowrap>作業内容</td><td nowrap>備考</td></tr>";
				foreach ($stmt as $row) {
					$開始=substr($row["入室時刻"], 0, 2).":".substr($row["入室時刻"], 2, 2);
					$終了=substr($row["退室時刻"], 0, 2).":".substr($row["退室時刻"], 2, 2);
					echo '<tr><td nowrap><form method="POST" action="index.php"><input name="sql" type="hidden" value="DELETE FROM `入退室管理` WHERE 年月日=\''.$row["年月日"].'\' and 名前=\''.$row["名前"].'\' and 入室時刻=\''.$row["入室時刻"].'\' and 退室時刻=\''.$row["退室時刻"].'\' and 作業内容=\''.$row["作業内容"].'\' and 備考=\''.$row["備考"].'\'"><input value="削除" class="del" type="submit"></form>　'.$row["年月日"].'</td><td nowrap>'.explode(":",$row["名前"])[0].'</td><td nowrap>'.$開始.'</td><td nowrap>'.$終了.'</td><td nowrap>'.$row["作業内容"].'</td><td nowrap>'.$row["備考"].'</td></tr>';
				}
				$dbh=null;
				echo "</table></tbody>";
			?>
		<script>
		var toDoubleDigits = function(num) {
			num += "";
			if (num.length === 1) {
				num = "0" + num;
			}
			return num;
		}
		</script>
		<?php echo '<p style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</p>'; ?>
		<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
	</body>
</html>
