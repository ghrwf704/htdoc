<?php 
	require_once("../function.php");
	header('Content-type: text/html; charset=utf-8');
	mb_internal_encoding('UTF-8');
	exec("rm ./csv/cdd.csv");
	$dbh=DB_CONNE();
$sql=<<<EOF
SELECT `id`, `日付`, `車種`, `車番`, `t`, `始業点検`, `除菌`, `灯火類`, `タイヤ`, `ボディー`, `警告灯`, `出社時メータ`, `帰社時メータ`, `行き先`, `月次点検実施日`, `タイヤ空気圧補充`, `実施前`, `実施後`, `指定空気圧`, `洗車`, `変更用`, `洗車実施日`, `T-M営業社員`.`部署コード`,`T-M営業社員`.`企業コード`
 FROM `自動車運転日報` inner join `T-M営業社員` on `自動車運転日報`.`id` = `T-M営業社員`.`ログインID`
EOF;

	$stmt = $dbh->query($sql);
	writefile("\"日付\",\"車種\",\"車番\",\"出社時メータ\",\"帰社時メータ\",\"行き先\",\"始業点検チェック\",\"除菌チェック\",\"運転者名\",\"灯火類\",\"タイヤ\",\"ボディー\",\"警告灯\",\"No\"\n");
    	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		try{
			writefile("\"".date("Ymd" ,strtotime($row['日付']))."\",\"".$row['車種']."\",\"".$row['車番']."\",\"".$row['出社時メータ']."\",\"".$row['帰社時メータ']."\",\"".$row['行き先']."\",\"".$row['始業点検']."\",\"".$row['除菌']."\",\"".$row['企業コード'].":".$row['部署コード'].":".$row['id']."\",\"".$row['灯火類']."\",\"".$row['タイヤ']."\",\"".$row['ボディー']."\",\"".$row['警告灯']."\"".",\"\"".PHP_EOL);
		} catch ( Exception $ex ) {
			break;
		}
	}
	$sql="DELETE FROM `自動車運転日報` where `帰社時メータ` >=1";
	$stmt = $dbh->query($sql);
	$dbh=null;

function writefile($str){
	$str=mb_convert_encoding($str,"sjis","auto");
	file_put_contents("./csv/cdd.csv", $str,FILE_APPEND);
	file_put_contents("./csv_bak/".date("Ymd").".csv", $str,FILE_APPEND);
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