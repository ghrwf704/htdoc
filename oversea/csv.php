<?php 
	require_once("function.php");
	header('Content-type: text/html; charset=utf-8');
	mb_internal_encoding('UTF-8');
	$dbh=DB_CONNE2();
	$sql="show tables;";
	//存在するテーブルで名前に「_」が含まれるものみ抽出
	$stmt = $dbh->query($sql);
	$table="";
	$table_arr=array();
    	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$table=$row['Tables_in_LAA1086961-overseas'];
		if(strpos($table,'MPEU') === false){
			if(strpos($table,'_') !== false){
				array_map('unlink', glob("csv/{$table}.csv"));
				$table_arr[]=$table;
			}
		}
	}
	$ymd=date('Ymd'); //20210909の形式で現在の年月日を取得
	$dbh=null;
	$dbh=DB_CONNE2();
	//$table_arrに格納している全テーブルで実施
	for($i=0;$i<count($table_arr);$i++){
		//全ての行を取得（WHERE 1）
		writefile("",$table_arr[$i],1,$ymd); //空のCSVを作成
		$sql="select * from {$table_arr[$i]} where 1;";
		$stmt = $dbh->query($sql);
   	 	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			for($ii=1;$ii<count($row)+1;$ii++){
				$col="field".$ii;
				//フィールドごとに型を判別
				$sql1="SHOW COLUMNS FROM `{$table_arr[$i]}` where `Field`='{$col}';";
				$stmt1 = $dbh->query($sql1);
				$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
				$type=$row1['Type'];
				$val=$row[$col];
				//フィールドが数値型もしくは小数点型で、値が空（値が入っていない）なら「０」を出力する
				if((strpos($type,'int') !== false) and (trim($val)=="")){
					$val="0";
				//小数点型
				}elseif((strpos($type,'double') !== false) and (trim($val)=="")){
					$val="0";
				}elseif(strpos($type,'timestamp') !== false){
					$val = str_replace(",","",mb_substr($val, 0, 10, "UTF8"));
				}
				if($ii==count($row)){
					//最後のフィールドは改行追加
					writefile($val."\n",$table_arr[$i],0,$ymd);
				}else{	
					//それ以外はflgに「1」を設定してCSV出力時に改行を削除
					writefile($val.",",$table_arr[$i],1,$ymd);
				}
			}
		}
		$sql="DELETE FROM `{$table_arr[$i]}` WHERE 1;";
		$stmt = $dbh->query($sql);
	}
	$dbh=null;
	//add 
	for($i=1;$i<4;$i++){
		$a = fopen("{$i}.csv", "a");
		fclose($a);
		$str = file_get_contents("{$i}.csv");
		//検索文字列に一致したすべての文字列を置換する
		$str = str_replace("Co.,","Co.",$str);
		$str = str_replace("Upwing Energy, Inc.","Upwing Energy Inc.",$str);
		$str = str_replace("\"","",$str);
		$str = str_replace(array("\r\n", "\r", "\n"), "\n", $str);
		$arr = explode("\n", $str);
		//文字列をファイルに書き込む
		$f=fopen("csv/MPEU_{$i}.csv","w");
		$f_=fopen("csv_back/MPEU_{$i}_{$ymd}.csv","w");
		for($j=0;$j<count($arr);$j++){
			if(trim($arr[$j])!=""){
				$ydmhis=date("Y-m-d",strtotime("-8 hour"));
				fwrite($f, $arr[$j].",MPEU,{$i},{$ydmhis}\n");
				fwrite($f_, $arr[$j].",MPEU,{$i},{$ydmhis}\n");
			}
		}
		fclose($f);
		fclose($f_);	
		array_map('unlink', glob("{$i}.csv"));
	}	
	//addend
	//CSV出力　書き込む値、テーブル名、末尾のデータなら１それ以外は１
	function writefile($str,$table,$flg,$ymd){
		$str=mb_convert_encoding($str,"UTF-8","auto");
		if($flg==1){
			$str=str_replace(PHP_EOL, '', $str);
		}
		//書き込み時追加モード
		file_put_contents("./csv/".$table.".csv", $str,FILE_APPEND);
		file_put_contents("./csv_back/".$ymd."_".$table.".csv", $str,FILE_APPEND);
	}
?>