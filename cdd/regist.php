<?php  header("Content-type: text/html; charset=utf-8");
	session_start();
	require_once("../function.php");
	isset($_SESSION['id']) ? $id = $_SESSION['id'] : $id = "";
	if ($id=="") {
		$_SESSION["error_status"] = 2;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	} 
	$_SESSION["error_status"] = 0;
	$変更用="";
	(isset($_POST["登録種別"])) ? $登録種別=$_POST["登録種別"] : $登録種別="";
	(isset($_POST["日付"])) ? $日付=$_POST["日付"] : $日付="";
	$変更用="日付：value：".$日付."、";
	(isset($_POST["車種"])) ? $車種=$_POST["車種"] : $車種="";
	$変更用=$変更用."車種：value：".$車種."、";
	(isset($_POST["車番"])) ? $車番=$_POST["車番"] : $車番="";
	$変更用=$変更用."車番：value：".$車番."、";
	
	if(isset($_POST["灯火"])){
		$灯火類=$_POST["灯火"];
		$変更用=$変更用."灯火：check：true、";
	}else{
		$灯火類="";
		$変更用=$変更用."灯火：check：false、";
	}
	if(isset($_POST["灯火１"])){
		$灯火類=$灯火類.PHP_EOL.$_POST["灯火１"];
		$変更用=$変更用."灯火１：check：true、";
	}else{
		$変更用=$変更用."灯火１：check：false、";
	}
	if(isset($_POST["灯火２"])){
		$灯火類=$灯火類.PHP_EOL.$_POST["灯火２"];
		$変更用=$変更用."灯火２：check：true、";
	}else{
		$変更用=$変更用."灯火２：check：false、";
	}
	if(isset($_POST["タイヤ"])){
		$タイヤ=$_POST["タイヤ"];
		$変更用=$変更用."タイヤ：check：true、";
	}else{
		$タイヤ="";
		$変更用=$変更用."タイヤ：check：false、";
	}
	if(isset($_POST["タイヤ１"])){
		$タイヤ=$タイヤ.PHP_EOL.$_POST["タイヤ１"];
		$変更用=$変更用."タイヤ１：check：true、";
	}else{
		$変更用=$変更用."タイヤ１：check：false、";
	}
	$temp="タイヤ２";
	if(isset($_POST[$temp])){
		$タイヤ=$タイヤ.PHP_EOL.$_POST[$temp];
		$変更用=$変更用.$temp."：check：true、";
	}else{
		$変更用=$変更用.$temp."：check：false、";
	}
	$temp="ボディー";
	$ボディー="";
	if(isset($_POST[$temp])){
		$ボディー=$_POST[$temp];
		$変更用=$変更用.$temp."：check：true、";
	}else{
		$変更用=$変更用.$temp."：check：false、";
	}
	$temp="ボディー１";
	if(isset($_POST[$temp])){
		$ボディー=$ボディー.PHP_EOL.$_POST[$temp];
		$変更用=$変更用.$temp."：check：true、";
	}else{
		$変更用=$変更用.$temp."：check：false、";
	}
	$temp="警告灯";
	$警告灯="";
	if(isset($_POST[$temp])){
		$警告灯=$_POST[$temp];
		$変更用=$変更用.$temp."：check：true、";
	}else{
		$変更用=$変更用.$temp."：check：false、";
	}
	$temp="警告灯１";
	if(isset($_POST[$temp])){
		$警告灯=$警告灯.PHP_EOL.$_POST[$temp];
		$変更用=$変更用.$temp."：check：true、";
	}else{
		$変更用=$変更用.$temp."：check：false、";
	}
	if(isset($_POST["出社時メータ"])){
		$出社時メータ=$_POST["出社時メータ"];
		$変更用=$変更用.'出社時メータ：value：'.$_POST["出社時メータ"].'、';
	}else{
		$変更用=$変更用.'出社時メータ：value：'.$_POST["出社時メータ"].'、';
	}
	if(isset($_POST["帰社時メータ"])){
		$帰社時メータ=$_POST["帰社時メータ"];
		$変更用=$変更用.'帰社時メータ：value：'.$_POST['帰社時メータ'].'、';
	}else{
		$変更用=$変更用.'帰社時メータ：value：'.$_POST["帰社時メータ"].'、';
	}
	if(isset($_POST["行き先"])){
		$行き先=$_POST["行き先"];
		$変更用=$変更用.'行き先：value：'.$_POST["行き先"].'、';
	}else{
		$変更用=$変更用.'行き先：value：'.$_POST["行き先"].'、';
	}
	if(isset($_POST["始業点検チェック"])){
		$始業点検チェック=$_POST["始業点検チェック"];
		$変更用=$変更用.'始業点検チェック：check：true、';
	}else{
		$始業点検チェック="";
		$変更用=$変更用.'始業点検チェック：check：false、';
	}
	if(isset($_POST["除菌チェック"])){
		$除菌チェック=$_POST["除菌チェック"];
		$変更用=$変更用.'除菌チェック：check：true、';
	}else{
		$除菌チェック="";
		$変更用=$変更用.'除菌チェック：check：false、';
	}
	if(isset($_POST["月次点検日"])){
		$月次点検日=$_POST["月次点検日"];
		$変更用=$変更用.'月次点検日：value：'.$月次点検日."、";
	}else{
		$月次点検日="";
		$変更用=$変更用.'月次点検日：value：、';
	}
	if(isset($_POST["タイヤ空気圧補充"])){
		$タイヤ空気圧補充=$_POST["タイヤ空気圧補充"];
		$変更用=$変更用.'タイヤ空気圧補充：check：true、';
	}else{
		$タイヤ空気圧補充="";
		$変更用=$変更用.'タイヤ空気圧補充：check：false、';
	}
	if(isset($_POST["洗車"])){
		$洗車=$_POST["洗車"];
		$変更用=$変更用.'洗車：check：true、';
	}else{
		$洗車="";
		$変更用=$変更用.'洗車：check：false、';
	}
	if(isset($_POST["洗車実施日"])){
		$洗車実施日=$_POST["洗車実施日"];
		$変更用=$変更用.'洗車実施日：value：'.$洗車実施日."、";
	}else{
		$洗車実施日="";
		$変更用=$変更用.'洗車実施日：value：、';
	}
	if(isset($_POST["実施前"])){
		$実施前=$_POST["実施前"];
		$変更用=$変更用.'実施前：value：'.$実施前."、";
	}else{
		$実施前="";
		$変更用=$変更用.'実施前：value：、';
	}
	if(isset($_POST["実施後"])){
		$実施後=$_POST["実施後"];
		$変更用=$変更用.'実施後：value：'.$実施後."、";
	}else{
		$実施後="";
		$変更用=$変更用.'実施後：value：、';
	}
	if(isset($_POST["指定空気圧"])){
		$指定空気圧=$_POST["指定空気圧"];
		$変更用=$変更用.'指定空気圧：value：'.$指定空気圧."、";
	}else{
		$指定空気圧="";
		$変更用=$変更用.'指定空気圧：value：、';
	}
	$_SESSION["error_msg"] = "<div style='text-align:left'>";
//echo $登録種別."<br>";
//echo $日付."<br>";
//echo $車種."<br>";
//echo $車番."<br>";
//echo $指定空気圧."<br>";
//echo $灯火類."<br>";
//echo $タイヤ."<br>";
//echo $ボディー."<br>";
//echo $警告灯."<br>";
//echo $出社時メータ."<br>";
//echo $帰社時メータ."<br>";
//echo $行き先."<br>";
//echo $始業点検チェック."<br>";
//echo $除菌チェック."<br>";
//echo $月次点検日."<br>";
//echo $タイヤ空気圧補充."<br>";
//echo $実施前."<br>";
//echo $実施後."<br>";
//echo $洗車実施日."<br>";
//echo $洗車."<br>";
//echo $t."<br>";
//exit();
	if(isset($_POST["t"])){
		$t=$_POST["t"];
		$変更用=$変更用.'t：value：'.$t."、";
	}else{
		$t=date("Y-m-d H:i:s");
		$変更用=$変更用.'t：value：、';
	}

	try{
		$dbh = DB_CONNE();
		if($登録種別=="変更"){
			$sql = "UPDATE `自動車運転日報` SET `id`='$id',`日付`='$日付',`車種`='$車種',`車番`='$車番',`始業点検`='$始業点検チェック',`除菌`='$除菌チェック',`灯火類`='$灯火類',`タイヤ`='$タイヤ',`ボディー`='$ボディー',`警告灯`='$警告灯',`出社時メータ`='$出社時メータ',`帰社時メータ`='$帰社時メータ',`行き先`='$行き先',`月次点検実施日`='$月次点検日',`タイヤ空気圧補充`='$タイヤ空気圧補充',`実施前`='$実施前',`実施後`='$実施後',`指定空気圧`='$指定空気圧',`洗車`='$洗車', `洗車実施日`='$洗車実施日',`変更用`='$変更用' WHERE t='$t'";
			$stmt = $dbh->query($sql);
			$sql = "DELETE FROM `車種別直近メータ` WHERE `車種` = '{$車種}'";
			$stmt = $dbh->query($sql);
			$sql = "INSERT INTO `車種別直近メータ`( `車種`, `最新のメータ`) VALUES ('{$車種}', '{$帰社時メータ}')";
			$stmt = $dbh->query($sql);
		}elseif($登録種別=="登録"){
			$t=date("Y-m-d H:i:s");
			$sql = "INSERT INTO `自動車運転日報`(`id`, `日付`, `車種`, `車番`, `始業点検`, `除菌`, `灯火類`, `タイヤ`, `ボディー`, `警告灯`, `出社時メータ`, `帰社時メータ`, `行き先`, `月次点検実施日`, `タイヤ空気圧補充`, `実施前`, `実施後`, `指定空気圧`, `洗車`, `t`, `洗車実施日`, `変更用`) VALUES ('$id', '$日付', '$車種', '$車番', '$始業点検チェック', '$除菌チェック', '$灯火類', '$タイヤ', '$ボディー', '$警告灯', '$出社時メータ', '$帰社時メータ', '$行き先', '$月次点検日', '$タイヤ空気圧補充', '$実施前', '$実施後', '$指定空気圧', '$洗車', '$t', '$洗車実施日', '$変更用')";
			$stmt = $dbh->query($sql);
			$sql = "DELETE FROM `車種別直近メータ` WHERE `車種` = '{$車種}'";
			$stmt = $dbh->query($sql);
			$sql = "INSERT INTO `車種別直近メータ`( `車種`, `最新のメータ`) VALUES ('{$車種}', '{$帰社時メータ}')";
			$stmt = $dbh->query($sql);
		}elseif($登録種別=="削除"){
			$sql = "DELETE FROM `自動車運転日報` WHERE `t` = '{$t}'";
			$stmt = $dbh->query($sql);
		}
		$dbh=null;
		$_SESSION["error_status"] = 4;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}catch( Exception $ex ){
		$_SESSION["error_status"] = 3;
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: welcome.php");
		exit();
	}
