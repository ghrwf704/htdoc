<?php
	require_once("./function.php");
	session_start();
	header("Content-type: text/html; charset=utf-8");
	$id = $_POST["id"];
		//IDチェック
	$dbh=DB_CONNE();
	$sql = "SELECT COUNT(*) AS CNT FROM IPASS WHERE ID = ?";
	$stmt = $dbh->prepare($sql,array('text'));
	$rs = $stmt->execute(array($id));
	while (1) {
		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($rec==false) {
			break;
		} elseif ($rec["CNT"] != 0) {
			$_SESSION["error_status"] = 2;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: register.php");
			exit();
		} 
	}
	$sql = "SELECT COUNT(*) AS CNT FROM IPASS WHERE MAILADDRESS = ?";
	$stmt = $dbh->prepare($sql,array('text'));
	$rs = $stmt->execute(array($mail));
	while (1) {
		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($rec==false) {
			break;
		} elseif ($rec["CNT"] != 0) {
			$_SESSION["error_status"] = 4;
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: register.php");
			exit();
		} 
	}
	$dbh = null;
	if ($count != 0) {
		$_SESSION["error_status"] = 2;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: register.php");
		exit();
	} 

	//エラー情報リセット
	$_SESSION["error_status"] = 0;
?>
<!DOCTYPE html>
<head>
<LINK href="style.css" rel="stylesheet" type="text/css">
<meta name="viewport" content=width=device-width, initial-scale=1.0>
<script type="text/javascript" src="autoresize.js"></script>
</head>
<html lang="ja">
<div id="centerMiddle"><body>
  <h2>確認画面</h2>
  <h2>登録しますか？</h2>
  <form action="./register_submit.php" method="post">
    <table align="center"  border="1">
      <tr>
        <td>ID</td>
        <td><?php  echo htmlspecialchars($id, ENT_QUOTES, "UTF-8"); ?></td>
      </tr>
     <tr>
        <td>メールアドレス</td>
        <td><?php  echo htmlspecialchars($mail, ENT_QUOTES, "UTF-8"); ?></td>
      </tr>
    </table>
    <input type="hidden" name="id" value="<?php  echo htmlspecialchars($id  , ENT_QUOTES, "UTF-8"); ?>">
    <input type="hidden" name="mail" value="<?php  echo htmlspecialchars($mail  , ENT_QUOTES, "UTF-8"); ?>">
    <input type="hidden" name="password" value="<?php  echo htmlspecialchars($password  , ENT_QUOTES, "UTF-8"); ?>">
    <input type="hidden" name="token" value="<?php  echo htmlspecialchars($_SESSION['token']  , ENT_QUOTES, "UTF-8"); ?>">
    <input type="submit" value="登録">
    <input type="button" value="戻る" onclick="history.back();">
  </form>
</body></div>
</html>