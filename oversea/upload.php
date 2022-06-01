<?php
	session_start();
	require("function.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={$_SESSION['文字コードR']}" />
		<title>アップロード完了</title>
	</head>
	<body>
<?php
	$拠点名=strtoupper($_SESSION['id']);
	$区分=substr($_POST["区分"], -1, 1);
	$table=$拠点名."_".$区分;
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
		if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "files/" . $_FILES["upfile"]["name"])) {
		    chmod("files/" . $_FILES["upfile"]["name"], 0644);
			$f = fopen("./files/".$_FILES["upfile"]["name"], "r");
			$cont=0;
			while($line = fgetcsv($f)){
				$cont=$cont+1;
				if(($cont>=$_SESSION['開始行']) and ($line[$_SESSION['終了判定列']]!="")){
					$_sql="INSERT INTO `{$table}` (";
					$sql="";
					$start=0;
					//↓MPTW限定//
					if($拠点名=='MPTW'){
						if(($区分=="1") or ($区分=="3")){
							$start=2;
							$_sql=$_sql."`field1`,`field2`,"; //最初の2列に、`拠点名`,`区分 `を挿入
							$sql=$sql."'".writefile(addslashes($拠点名))."','".writefile(addslashes($区分))."',";
						}else if($区分=="2"){
							$start=3; //最初の3列に、`拠点名`,`区分 `,`データを入力した年月日`を挿入
							$_sql=$_sql."`field1`,`field2`,`field3`,";
							$sql=$sql."'".writefile(addslashes($拠点名))."','".writefile(addslashes($区分))."','".writefile(addslashes(date("Y/m/d")))."',";
						}
					}
					//↑MPTW限定//
					//【for文】field1～あるだけSQLに反映させる(全拠点共通)
					for($i=1;$i<=intval(count($line));$i++){
						$_sql=$_sql."`field".strval($i+$start)."`,";
						$sql=$sql."'".writefile(addslashes($line[$i-1]))."',";
					}
					//↓MPEU限定//
					if($拠点名=='MPEU'){
						$_sql=$_sql."`field17`,`field18`,";
						$sql=$sql."'".writefile(addslashes($拠点名))."','".writefile(addslashes($区分))."',";
					}
					//↑MPEU限定//
					//↓(全拠点共通)
					$sql=$_sql.") VALUES (".$sql.");";
					$sql=str_replace(",)",")",$sql);
					try{
						//echo $sql;
						//exit();
						$dbh = DB_CONNE2();
						$stmt = $dbh->query($sql);
						$dbh=null;
					} catch (Exception $e) {
						//明らかなエラーなので処理を止めてエラー箇所とエラーになった構文を出力
						echo "An error occurred on the ".strval($cont)." line.<br> Please save this screen and contact the system department.<br>";
						echo $sql;
						exit();
					}
				}
			}
			fclose($f);
    			echo $_FILES["upfile"]["name"] . " Connects successfully。<br>";				
		}else {
	    		echo " Unable to upload file.";
  		}
	}else {
		echo " No file selected.";
	}
?>
<button type="button" onclick="history.back()">Back</button>
</body>
</html>

<?php
function writefile($str){
	$str=mb_convert_encoding($str,$_SESSION['文字コードR'],$_SESSION['文字コードW']);	
	$str=trim($str);
//echo $str."<br>";
	$search = ["\r\n", "\r", "\n",","];
	$replace = [PHP_EOL, PHP_EOL, PHP_EOL,""];
	$str=str_replace($search, $replace, $str);
$str=str_replace(PHP_EOL, '', $str);
	return $str;
}
?>