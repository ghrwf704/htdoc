<?php 
	require_once("../function.php");
	header('Content-type: text/html; charset=utf-8');
	mb_internal_encoding('UTF-8');
	exec("rm ./csv/nyutai.csv");
	$dbh=DB_CONNE();
$sql=<<<EOF
select 
`年月日`,
`入室時刻`,
`退室時刻`,
`名前`,
`作業内容`,
`備考`,
`t` as 'No' 
from `入退室管理` where 1
EOF;

	$stmt = $dbh->query($sql);
	writefile("No,日付,入室時刻,退室時刻,作業内容,備考,名前\n");
    	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		try{
			$名前=str_replace(explode(":",$row["名前"])[0],"90001",$row["名前"]);
			writefile( ",".date("Ymd" ,strtotime($row['年月日'])).",".$row['入室時刻'].",".$row['退室時刻'].",".$row['作業内容'].",".$row['備考'].",".$名前."\n");
		} catch ( Exception $ex ) {
			break;
		}
	}
	$sql="DELETE FROM `入退室管理` where 1";
	$stmt = $dbh->query($sql);
	$dbh=null;

function writefile($str){
	$str=mb_convert_encoding($str,"sjis","auto");
	file_put_contents("./csv/nyutai.csv", $str,FILE_APPEND);
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