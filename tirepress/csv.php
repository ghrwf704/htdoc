<?php
	require_once("../function.php");
	header('Content-type: text/html; charset=utf-8');
	mb_internal_encoding('UTF-8');
	exec("rm ./csv/tirepress.csv");
	$dbh=DB_CONNE();
$sql=<<<EOF
SELECT `補充日`, `id` as `担当者`, `補充後前輪空気圧` as `前輪空気圧`, `補充後後輪空気圧` as `後輪空気圧`, `車種`, `給油量`,`給油時メータ`, `車番`, `洗車日`, `T-M営業社員`.`部署コード`, `T-M営業社員`.`企業コード` 
 FROM `タイヤ空気圧` inner join `T-M営業社員` on `タイヤ空気圧`.`id` = `T-M営業社員`.`ログインID` WHERE 1
EOF;

	$stmt = $dbh->query($sql);
	writefile("補充日,担当者,前輪空気圧,後輪空気圧,車種,給油時メータ,給油量,車番,洗車日,No\n");
    	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		try{
			if($row['洗車日']!="0000-00-00"){
				$p=date("Ymd" ,strtotime($row['洗車日']));
			}else{
				$p="";
			}
			writefile( date("Ymd" ,strtotime($row['補充日'])).",".$row['企業コード'].":".$row['部署コード'].":".$row['担当者'].",".$row['前輪空気圧'].",".$row['後輪空気圧'].",".$row['車種'].",".$row['給油時メータ'].",".$row['給油量'].",".$row['車番'].",".$p.",".PHP_EOL);
		} catch ( Exception $ex ) {
			break;
		}
	}
	$sql="DELETE FROM `タイヤ空気圧` where 1";
	$stmt = $dbh->query($sql);
	$dbh=null;

function writefile($str){
	$str=mb_convert_encoding($str,"sjis","auto");
	file_put_contents("./csv/tirepress.csv", $str,FILE_APPEND);
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