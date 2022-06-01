<?php
	require("function.php");
	//ini_set("display_errors", "Off");
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if((""!=$_POST['入室'])){
		if((""!=$_POST['退室'])){
		if((""!=$_POST["年月日"])){
			$入室=$_POST["入室"];
			$退室=$_POST["退室"];
			$作業内容=$_POST["作業内容"];
			$テキスト=$_POST["テキスト"];
			$名前=$_POST["名前"];
			$年月日=$_POST["年月日"];
			$dbh=DB_CONNE2();
			$sql = "INSERT INTO `入退室管理`(`年月日`, `入室時刻`, `退室時刻`, `名前`, `作業内容`, `備考`) VALUES ('{$年月日}', '{$入室}','{$退室}','{$名前}','{$作業内容}', '{$テキスト}')";
			$stmt = $dbh->query($sql);
			$dbh=null;
		}
		}
		}
	}
var_dump($_POST);
	$_POST["入室"]=null;
	$_POST["退室"]=null;
	$h=str_pad(strval(date("H")), 2, 0, STR_PAD_LEFT);
	$i=str_pad(strval(date("i")), 2, 0, STR_PAD_LEFT);
	$hi=$h.":".$i;
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
					<tr><td nowrap>日付</td><td nowrap>名前</td><td nowrap>入室時刻</td><td nowrap>退室時刻</td><td nowrap>作業内容</td><td width="35%">備考</td><td nowrap></td></tr>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input type="radio" name="名前" value="川里誠司:1O20:003109" checked>川里誠司</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td><input type="hidden" value=></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input type="radio" name="名前" value="井上博昭:1O20:001676" checked>井上博昭</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input type="radio" name="名前" value="左治木康夫:1O20:000931" checked>左治木康夫</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input type="radio" name="名前" value="黒岩勇介:1O20:003519" checked>黒岩勇介</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
					<form name="form" action="index.php" method="POST"><tr><td nowrap><input type="date" name="年月日" style="width:100%; box-sizing:border-box" value="<?php echo date("Y")."/".date("m")."/".date("d"); ?>"></td><td nowrap><label><input type="radio" name="名前" value="吉ヶ江慧:1O20:003553" checked>吉ヶ江慧</label></td><td nowrap><input type="time" name="入室" value=""></td><td nowrap><input type="time" name="退室" value=""></td><td nowrap><input type="radio" name="作業内容" value="テープ交換" checked>テープ交換<input type="radio" name="作業内容" value="その他">その他</td><td nowrap><input type="text" name="テキスト" value="" style="width=18px"></td><td nowrap><input type="submit" value="登録"></td></tr></form>
				</tbody>
			</table>
			<?php
				$dbh=DB_CONNE2();
				$sql = "SELECT * FROM `入退室管理` ORDER BY `年月日` DESC";
				// SQLステートメントを実行し、結果を変数に格納さ
				$stmt = $dbh->query($sql);
				echo '<table border="1" width="100%"><tbody border-style:solid;>';
				echo "<tr><td nowrap>日付</td><td nowrap>名前</td><td nowrap>入室時刻</td><td nowrap>退室時刻</td><td nowrap>作業内容</td><td nowrap>備考</td></tr>";
				foreach ($stmt as $row) {
					echo '<tr><td nowrap>'.$row["年月日"].'</td><td nowrap>'.explode(":",$row["名前"])[0].'</td><td nowrap>'.$row["入室時刻"].'</td><td nowrap>'.$row["退室時刻"].'</td><td nowrap>'.$row["作業内容"].'</td><td nowrap>'.$row["備考"].'</td></tr>';
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
		<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
	</body>
</html>
