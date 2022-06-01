<?php
function DB_CONNE2(){
	$dsn = 'mysql:dbname=LAA1086961-miki2;host=mysql138.phy.lolipop.lan';
	$user = 'LAA1086961';
	$dbpassword = 'M1k1pulley';
	$dbh = new PDO($dsn,$user,$dbpassword);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->query('SET NAMES utf8');
	return $dbh;
}

function post_message($message,$flgname,$today){
	// LINEAPIの接続情報
	define('LINE_API_URL'  ,'https://notify-api.line.me/api/notify');
	define('LINE_API_TOKEN','wpujylDUn4gS1VtQO3ZZ85jG7oc88psA549gyuCl6S5');
	$id=11111;
	$dbh=DB_CONNE2();
	$stmt = $dbh->query("SELECT * FROM `通知管理` WHERE `id`='{$id}' and `flg`='{$flgname}' and `日付`='{$today}';");
	if(0!=($stmt -> rowCount())){
		//送信済みなら関数を抜ける
		$dbh=null;
		return true;
	}else{
		//送信していない場合は通地管理にデータを追加してＬｉｎｅを送信
		$stmt = $dbh->query("INSERT INTO `通知管理`(`id`, `日付` , `flg`) VALUES ('$id','$today','$flgname');");
		$dbh=null;
		// URL エンコードされたクエリ文字列を生成する
		$data = http_build_query( [ 'message' => $message ]);
		$options = [
			'http'=> [
			'method'=>'POST',
			'header'=>'Authorization: Bearer ' . LINE_API_TOKEN . "\r\n"
			. "Content-Type: application/x-www-form-urlencoded\r\n" ,
			'content' => $data,
			]
		];
		// ストリームコンテキストを作成する
		$context = stream_context_create($options);
		// POST送信 
		$resultJson = file_get_contents(LINE_API_URL, false, $context);
		// レスポンス確認 
		$resultArray = json_decode($resultJson, true);
		if($resultArray['status'] != 200)  {
			echo $resultArray['message'] . "\r\n";   
			return false;
		}
		return true;
	}
}
?>