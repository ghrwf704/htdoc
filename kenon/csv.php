<?php 
	require_once("./function.php");
	header('Content-type: text/html; charset=utf-8');
	mb_internal_encoding('UTF-8');
	exec("rm ./csv/kenon.csv");
	$dbh=DB_CONNE();
$sql=<<<EOF
select 
`年月日`,
`検温値1`,
`体調`,
`症状`,
`T-M営業社員`.`部署名`,
`T-M営業社員`.`名前`,
`時`,
`分`,
`T-M営業社員`.`部署コード`,
`T-M営業社員`.`企業コード`,
`id`,
`No`  
from `検温結果` inner join `T-M営業社員` on `検温結果`.`id` = `T-M営業社員`.`ログインID` where `flg`='0'
EOF;
	//新規で追加されたデータ（flg=0）のみPowerEggにデータ追加
	$stmt = $dbh->query($sql);
	writefile("No,日付,時刻,部署,検温者,検温値,体調,症状\n");
    	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		try{
			writefile( ",".date("Ymd" ,strtotime($row['年月日'])).",".str_pad(strval($row['時']),2,"0",STR_PAD_LEFT).str_pad(strval($row['分']),2,"0",STR_PAD_LEFT).",".$row['企業コード'].":".$row['部署コード'].",".$row['企業コード'].":".$row['部署コード'].":".$row['id'].",".$row['検温値1'].",".$row['体調'].",".$row['症状']."\n");
		} catch ( Exception $ex ) {
			break;
		}
	}
	//すべてのデータにフラグを付ける
	$sql="UPDATE `検温結果` SET `flg`='1' WHERE `flg`='0'"; 
	$stmt = $dbh->query($sql);
	//２日以上前のデータは削除する
	$sql="DELETE FROM `検温結果` WHERE `年月日` < (NOW() - INTERVAL 7 DAY)";
	$stmt = $dbh->query($sql);
	$dbh=null;

function writefile($str){
	$str=mb_convert_encoding($str,"sjis","auto");
	file_put_contents("./csv/kenon.csv", $str,FILE_APPEND);
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