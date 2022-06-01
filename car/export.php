<?php
require_once("function.php");
require_once ("vendor/autoload.php");
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$c=intval($_GET["month"]);
$Year = date("Y");
$fname="monthlyDrivingReport({$Year}{$c}).xlsx";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
if (file_exists($fname)){ 
	unlink($fname);
}
//タイトルとフィールド名を設定
setOptions('A1','J1',"運　行　実　績　報　告　書（{$c}月分）",$sheet);
$sheet->getStyle('A1')->getFont()->setSize(18);
setOptions('L1','L1',$_SESSION['名前'],$sheet);
setOptions('A2','A3','日付',$sheet);
setOptions('B2','B3','曜日',$sheet);
setOptions('C2','D2','予定',$sheet);
setOptions('C3','C3','始業',$sheet);
setOptions('D3','D3','終業',$sheet);
setOptions('E2','F2','実績',$sheet);
setOptions('E3','E3','始業',$sheet);
setOptions('F3','F3','終業',$sheet);
setOptions('G2','G3','時間外',$sheet);
setOptions('H2','H3','出庫メーター',$sheet);
setOptions('I2','I3','入庫メーター',$sheet);
setOptions('J2','J3','走行(km)',$sheet);
setOptions('K1','K1','氏名',$sheet);
$sheet->getStyle('K1:L1')->getFont()->setUnderline(true);
setOptions('K2','K3','体温',$sheet);
setOptions('L2','L3',"行き先".PHP_EOL."【備考】",$sheet);
//フィールド名が記載される個所の背景色をグレイに設定
$sheet->getStyle("A2:L3")->getFill()->setFillType('solid')->getStartColor()->setARGB('EAE8F2');
$sheet->getStyle('A2:L3')->getBorders()->getBottom()->setBorderStyle("double");

//最も古い日付データを取得
$dbh=DB_CONNE2();
$sql = "SELECT * FROM `自動車運転日報` ORDER BY `日付` ASC";
$stmt = $dbh->query($sql);
foreach ($stmt as $row) {
	$F日付=$row["日付"];
	break;
}
$dbh=null;

//最も新しい日付データを取得
$dbh=DB_CONNE2();
$sql = "SELECT * FROM `自動車運転日報` ORDER BY `日付` DESC";
$stmt = $dbh->query($sql);
foreach ($stmt as $row) {
	$L日付=$row["日付"];
	break;
}
$dbh=null;

//フィールド名出力
$Totalworking=0;
$Totaldistance=0;
$出社時メータ=0;
$帰社時メータ=0;
$preWork=0;
$aftWork=0;
$就業時間=0;
$就業時間H=0;
$就業時間N=0;
$時間外=0;
$出社予定時間="";
$行き先="";
$備考="";
$i=4; //1行目はタイトル、2行目、3行目はフィールド名表示に使用
while(true){
	$dbh=DB_CONNE2();
	$sql = "SELECT * FROM `自動車運転日報` Where `日付`='{$F日付}'";
	$stmt = $dbh->query($sql);
	if(date('n',  strtotime($F日付))==$c){
		$flg=0;
		foreach($stmt as $row){
			//該当日が存在した場合
			$flg=1;
			$開始時間=date("H:i",strtotime($row["開始時間"]));
			$終了時間=date("H:i",strtotime($row["終了時間"]));
			$出社時メータ=intval($row["出社時メータ"]);
	        	$帰社時メータ=intval($row["帰社時メータ"]);
			if(($row["行き先"]!="") and ($row["備考"]!="")){
				setOptions('L'.$i,'L'.$i,$row["行き先"].PHP_EOL."【".$row["備考"]."】",$sheet); 
				$sheet->getRowDimension($i)->setRowHeight(32);		
			}else if(($row["行き先"]=="") and ($row["備考"]!="")){
				setOptions('L'.$i,'L'.$i,"【".$row["備考"]."】",$sheet); 				
			}else if(($row["行き先"]!="") and ($row["備考"]=="")){
				setOptions('L'.$i,'L'.$i,$row["行き先"],$sheet); 				
			}
			$出社予定時間=date("H:i",strtotime($出社予定時間));
			setOptions('C'.$i,'C'.$i,$出社予定時間,$sheet);
			setOptions('D'.$i,'D'.$i,"17:30",$sheet);
			setOptions('E'.$i,'E'.$i,$開始時間,$sheet);
			setOptions('F'.$i,'F'.$i,$終了時間,$sheet);
			$preWork=intval(explode(":",$開始時間)[0])*60+intval(explode(":",$開始時間)[1]);
			$aftWork=intval(explode(":",$終了時間)[0])*60+intval(explode(":",$終了時間)[1]);
			$就業時間=$aftWork-$preWork;
			if(($就業時間-540)>0){
				setOptions('G'.$i,'G'.$i,mToHm($就業時間-540),$sheet); //時間外
				$時間外=$時間外+$就業時間-540;
			}else{
				setOptions('G'.$i,'G'.$i,"",$sheet);
			}
			setOptions('H'.$i,'H'.$i,$出社時メータ,$sheet); //出社時メータ
			setOptions('I'.$i,'I'.$i,$帰社時メータ,$sheet); //帰社時メータ
			setOptions('J'.$i,'J'.$i,$帰社時メータ-$出社時メータ,$sheet); //走行キロ
			setOptions('K'.$i,'K'.$i,$row['体温'],$sheet); //体温
			$sheet -> getStyle('K'.$i) -> getNumberFormat() -> setFormatCode('0.0'); //カンマ付与に対応する
			$Totaldistance=$Totaldistance+intval($帰社時メータ)-intval($出社時メータ);
			$Totalworking=$Totalworking+$就業時間;
			$出社予定時間=$row['出社予定時間'];
		}
		if($flg==0){
			//該当日が存在しない場合
			$出社時メータ=$帰社時メータ;
			$preWork=0;
			$aftWork=0;
			$就業時間=0;
			$就業時間H=0;
			$就業時間N=0;
			$出社予定時間=$出社予定時間;
			$開始時間="00:00";
			$終了時間="00:00";
			setOptions('C'.$i,'C'.$i,"",$sheet);
			setOptions('D'.$i,'D'.$i,"",$sheet);
			setOptions('E'.$i,'E'.$i,"",$sheet);
			setOptions('F'.$i,'F'.$i,"",$sheet);
			setOptions('G'.$i,'G'.$i,"",$sheet); //時間外
			setOptions('H'.$i,'H'.$i,"",$sheet); //出社時メータ
			setOptions('I'.$i,'I'.$i,"",$sheet); //帰社時メータ
			setOptions('J'.$i,'J'.$i,"",$sheet); //走行キロ
			setOptions('K'.$i,'K'.$i,"",$sheet); //体温
			$sheet -> getStyle('K'.$i) -> getNumberFormat() -> setFormatCode('0.0'); //カンマ付与に対応する
			setOptions('L'.$i,'L'.$i,"未登録",$sheet); //備考
		}
		setOptions('A'.$i,'A'.$i,$F日付,$sheet);
		$week = array( "日", "月", "火", "水", "木", "金", "土" );
		$datetime = new DateTime($F日付);
		$曜日=$week[$datetime->format("w")];
		setOptions('B'.$i,'B'.$i,$曜日,$sheet);
		if($曜日=="日" or $曜日=="土"){
			$sheet->getStyle("A{$i}:L{$i}")->getFill()->setFillType('solid')->getStartColor()->setARGB('F9D1D8');
			$sheet->getStyle("A{$i}:L{$i}")->getFont()->getColor()->setARGB('FFFF0000');
		}
		$i=$i+1;
	}
	$dbh=null;
	$F日付=date('Y-m-d', strtotime('+1 day', strtotime($F日付)));
	if($F日付==date('Y-m-d', strtotime('+1 day', strtotime($L日付)))){
		break;
	}
}
setOptions('E'.$i,'E'.$i,"終業時間計",$sheet);
setOptions('F'.$i,'F'.$i,mToHm($Totalworking),$sheet);
setOptions('G'.$i,'G'.$i,mToHm($時間外),$sheet);
setOptions('J'.$i,'J'.$i,$Totaldistance,$sheet);
$borders = $sheet->getStyle("A".($i-1).":L".($i-1))->getBorders();
$borders->getBottom()->setBorderStyle('medium');  
$borders = $sheet->getStyle("A1:L1")->getBorders();
$borders->getBottom()->setBorderStyle('medium');  
$borders->getTop()->setBorderStyle('medium');
$borders = $sheet->getStyle("C3:F3")->getBorders();
$borders->getBottom()->setBorderStyle('medium');  
$borders->getTop()->setBorderStyle('medium');
$borders = $sheet->getStyle("E".$i.":G".$i)->getBorders();
$borders->getBottom()->setBorderStyle('medium');  
$borders->getTop()->setBorderStyle('medium');
$borders = $sheet->getStyle("J".$i)->getBorders();
$borders->getBottom()->setBorderStyle('medium');  
$borders->getTop()->setBorderStyle('medium');
$sheet->getStyle('L2:L40')->getAlignment()->setWrapText(true);
$sheet->setTitle("{$c}月分運行実績報告書");
$writer = new Xlsx($spreadsheet);
$writer->save($fname);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename={$fname}");
header('Cache-Control: max-age=0');

// Excel2007形式で出力
$writer->save('php://output'); 

//分→時分に変換
function mToHm($nn){
	return strval(intval(($nn) / 60)).":".strval(($nn) % 60);
}
//セルの書式設定
function setOptions($range1,$range2,$str,$sheet){
	if($range1!=$range2){
		$sheet->mergeCells("{$range1}:{$range2}");
	}
	$sheet->setCellValue($range1, $str);
	$sheet->getStyle("{$range1}:{$range2}")->getAlignment()->setHorizontal('center');
	$sheet->getStyle("{$range1}:{$range2}")->getAlignment()->setVertical('center');
	$borders = $sheet->getStyle("{$range1}:{$range2}")->getBorders();
	$borders->getTop()->setBorderStyle('dotted');  	
	$borders->getRight()->setBorderStyle('medium');  
	$borders->getBottom()->setBorderStyle('dotted');  
	$borders->getLeft()->setBorderStyle('medium'); 
	$sheet -> getStyle($range1) -> getNumberFormat() -> setFormatCode('#,##0'); //カンマ付与に対応する
	$sheet->getColumnDimension(substr($range1,0,1))->setAutoSize(true);
	$dim = $sheet -> getColumnDimension(substr($range1,0,1)); //対象行のオブジ
	$dim -> setAutoSize(true); //リサイズを有効にする　（３）
	$sheet -> calculateColumnWidths(); //行幅を数える　（４）
	$dim -> setAutoSize(false); //リサイズの初期化（５）…Windowsの場合は不要かも
	$col_width = $dim -> getWidth(); //列幅を取得　（６）
	$dim -> setWidth($col_width * 1.7); //列幅を指定。取得した値に1.7を掛けると理想的な幅となる。 （７）	
}