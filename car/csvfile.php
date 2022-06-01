<?php
require_once("function.php");
// ライブラリ読込
require_once("PHPExcel-1.8/Classes/PHPExcel.php");

// 出力情報の設定
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=monthlyData.csv");
header("Content-Transfer-Encoding: binary");
mb_internal_encoding('UTF-8');

// 変数の初期化
$member = array();
$csv = null;

// 出力したいデータ
$member = array();
$c=$_GET["month"];
$Totalworking=0;
$Totaldistance=0;
$dbh=DB_CONNE2();
$sql = "SELECT * FROM `自動車運転日報` ORDER BY `日付` ASC";
$stmt = $dbh->query($sql);
if($stmt){
	foreach ($stmt as $row) {
		if(date('n',  strtotime($row["日付"]))==$c){
			$temp=intval($row["帰社時メータ"])-intval($row["出社時メータ"]);
			$member[]=array(
			'日付' => $row["日付"],
			'車種' => $row["車種"],
			'体温' => $row["体温"],
			'車番' => $row["車番"],
			'行き先' => $row["行き先"],
			'開始時間' => $row["開始時間"],
			'出社時メータ' => $row["出社時メータ"],
			'終了時間' => $row["終了時間"],
			'帰社時メータ' => $row["帰社時メータ"],
			'走行距離' => $temp,
			'備考' => $row["備考"],
			'出社予定時間' => $row["出社予定時間"],);
			$Totalworking=$Totalworking+($row["終了時間"]-$row["開始時間"]);
			$Totaldistance=$Totaldistance+$temp;
		}
	}
}
// 1行目のラベルを作成
$csv = '"日付","車種","車番","体温","行き先","開始時間","出社時メータ","終了時間","帰社時メータ","走行距離","備考","出社予定時間"' . "\n";
// 出力データ生成
foreach( $member as $value ) {
	$csv = $csv .'"' . $value['日付'] . '","' . $value['車種'] . '", ' . $value['車番'] . ',"' . $value['体温'] . '","' . $value['行き先'] . '","' . $value['開始時間'] . '","' . $value['出社時メータ'] . '","' . $value['終了時間'] . '","' . $value['帰社時メータ'] . '","' . $value['走行距離'] .'","' . $value['備考'] .'","' . $value['出社予定時間'] . '"'."\n";
	
}
$csv = $csv."勤務時間計(時間：分),{$Totalworking},走行距離計(km),{$Totaldistance}";

// CSVファイル出力
echo mb_convert_encoding($csv,"sjis","auto");


 
// PHPExcelオブジェクト作成
$objBook = new PHPExcel();
 
// シート設定
$objSheet = $objBook->getSheet(0);
 
// [A1]セルに文字列設定
$objSheet->getCell('A1')->setValue('ABCDEFG');
 
// [A2]セルに数値設定
$objSheet->getCell('A2')->setValue('123.56');
 
// [A3]セルにBOOLEAN設定
$objSheet->getCell('A3')->setValue(TRUE);
 
// [A4]セルに書式設定
$objSheet->getCell('A4')->setValue('=IF(A3, CONCATENATE(A1, " ", A2), CONCATENATE(A2, " ", A1))');
 
// Excel2007形式で保存する
$objWriter = PHPExcel_IOFactory::createWriter($objBook, "Excel2007");
$objWriter->save('test1-2.xlsx');
exit();

return;

