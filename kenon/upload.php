<?php 
	require_once("../function.php");
	exec("rm ./err_ids.csv");
?>
<html>
<head>
<?php 
	$dbh=DB_CONNE();
	$sql="select `id`,`T-M営業社員`.`部署名`,`T-M営業社員`.`名前` from `T-Mパスワード` inner join `T-M営業社員` on `T-Mパスワード`.`id` = `T-M営業社員`.`ログインID`  where 1";
	$stmt = $dbh->query($sql);
	$ida=array();
	$mailaddress=array();
	$name=array();
   	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		try{
			$ida[]=$row['id'];
			$name[]=$row['名前'];
		} catch ( Exception $ex ) {
			break;
		}
	}
	$dbh=null;

function writefile($str){
	file_put_contents("./err_ids.csv", $str,FILE_APPEND);
}
function get8($str){
	$f="";
	for($i=0;$i<strlen($str);$i++){
		if(is_numeric(substr($str,$i,1))){
			if(8==strlen($f)){return $f;}
			$f=$f.substr($str,$i,1);
		}
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>メール通知画面</title>
</head>
<body>
<p>
<?php
$days=array(date('Ymd', strtotime('-1 day')),date('Ymd', strtotime('-2 day')),date('Ymd', strtotime('-3 day')),date('Ymd', strtotime('-4 day')),date('Ymd', strtotime('-5 day')),date('Ymd', strtotime('-6 day')));
if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
	if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
	    chmod("files/" . $_FILES["upfile"]["name"], 0644);
		$file = "files/" . $_FILES["upfile"]["name"];
		$lines = file_get_contents($file);
	}else {
		echo "ファイルをアップロードできません。";
	}
} else {
  echo "ファイルが選択されていません。";
}
echo '<a href="https://moo-mikipulley.ssl-lolipop.jp/kenon/err_ids.csv">csvをDL</a>';
echo "<p>Windows版の Microsoft Excel でCSVファイルを開いた際に文字化けしている場合、以下の方法で解消できる可能性があります。 </p>";
echo "<p>1. CSVファイルを右クリックし、「プログラムから開く」→「メモ帳」を選択します。</p>";
echo "<p>2. メモ帳で開いた際に文字化けしていない事を確認し、「ファイル」→「名前をつけて保存」を選択し、表示されたダイアログボックス上の「文字コード」を「ANSI」に変更の上、保存します。</p>";
echo "<p>3. Excel でCSVファイルを開き、文字化けが解消されていることを確認します。</p>";
writefile("直近の未投入日,社員ID,社員名".PHP_EOL);
for ($i=0;$i<count($ida);$i++) {
	for ($ii=0;$ii<count($days);$ii++) {
		$pattern = "$days[$ii].+$ida[$i]";
		if ( preg_match("/".$pattern."/", $lines ) ) {

		}else{
			writefile($days[$ii].",".$ida[$i].",".$name[$i].PHP_EOL);
			$dbh=DB_CONNE();
			$sql="select `mailaddress` from `メールアドレス` where `id`='$ida[$i]'";
			$stmt = $dbh->query($sql);
		   	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				try{
					writefile($days[$ii].",".$ida[$i].",".$name[$i].PHP_EOL);
					$to = $row['mailaddress'];
					$subject = "【検温システム】検温結果を登録してください";
					$message = "$name[$i]さん、直近の一週間の検温結果を確認し、検温結果を登録してください。";
					$headers = "From: 三木プーリ株式会社　システム課";
					//mb_send_mail($to, $subject, $message, $headers); 
					break;
				} catch ( Exception $ex ) {
					break;
				}
			}
			$dbh=null;
			break;
		}
	}
}
//部下の検温記録を上司に知らせるロジック
$jid=array();//上司ID
$上司=array();//上司コード
$部下=array(); //部下コード　※要素数はcount($上司)と同じになるはず

$sql="Select `id`,`上司コード`,`部下コード` from `上司部下` where 1 order by id";
$dbh=DB_CONNE();
$stmt = $dbh->query($sql);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	try{
		$jid[]=$row['id'];
		$上司[]=$row['上司コード'];
		$部下[]=$row['部下コード'];
	} catch ( Exception $ex ) {
		break;
	}
}
$tmp="";//重複確認用	
$dbh=null;
$msg="過去一週間で検温結果の登録漏れがある社員をお知らせします。". PHP_EOL;
For($i=0;$i<count($部下);$i++){
	if(($i>0) and ($jid[$i]!=$jid[$i-1])){
		$sql="Select `mailaddress` from `メールアドレス` where `id`='$jid[$i]'";
		$dbh=DB_CONNE();
		$stmt = $dbh->query($sql);
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			//mb_send_mail($to, $subject, $message, $headers);
			if(strpos($msg,"未登録") !== false){echo $row['mailaddress']."→".$msg;}
		}
		$dbh=null;
		$msg="過去一週間で検温結果の登録漏れがある社員をお知らせします。". PHP_EOL;
	}else{

	}
	$sql="Select `ログインID`,`名前` from `T-M営業社員` where `部署コード`='$部下[$i]'";
	$dbh=DB_CONNE();
	$stmt = $dbh->query($sql);
	$flg=0;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		try{
			$名前=$row['名前'];
			$ログインID =$row['ログインID'];
			$num=0;
			for ($ii=0;$ii<count($days);$ii++) {
				$pattern = "$days[$ii].+$ログインID";
				if ( preg_match("/".$pattern."/", $lines ) ) {
					//登録済み
				}else{
					//未登録
					$num=$num+1;
					$flg=1;
				}
			}
			//未登録
			if($num!=0){
				$msg=$msg.$名前."($ログインID)：".strval($num).'件が未登録'.PHP_EOL;
			}
		} catch ( Exception $ex ) {
			break;
		}
	}
	$dbh=null;
}
?></p>
</body>
</html>