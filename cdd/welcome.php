<?php 
	session_start();
	if(is_null($_SESSION['名前'])){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}
	header("Content-type: text/html; charset=utf-8");
	require_once("../function.php");
	error_reporting(0);
?>
<html>
	<head>
		<title>自動車運転日報ＷＥＢシステム</title>
	</head>
	<body>
		<script src="https://cdnjs.com/libraries/URI.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>指定空気圧
			<form name="form" action="login.php" method="POST">
				<div id="top"><div id="indivisual"><?php echo $_SESSION['名前']."さん"; ?></div><input type="submit" class="container" value="ログアウト" name="x" onclick="submitAction('welcome.php')" style></div>
			</form>
		<script>
			$(document).ready(function() {
			  var pagetop = $('.pagetop');
			    $(window).scroll(function () {
			       if ($(this).scrollTop() > 100) {
			            pagetop.fadeIn();
			       } else {
			            pagetop.fadeOut();
				            }
			       });
			       pagetop.click(function () {
			           $('body, html').animate({ scrollTop: 0 }, 500);
					$("#temp").remove()
			              return true;
			   });
			});
function modPage(arr){
	arr1=arr.split("、");
	for(i=0;i<arr1.length;i++){
		a=arr1[i].split("：")[0];
		b=arr1[i].split("：")[1];
		c=arr1[i].split("：")[2];
		if(b=="value"){
			document.getElementsByName(a)[0].value=c;
		}else{
			document.getElementsByName(a)[0].checked=c;
		}
	}
}
		</script>
			<?php
				$id = $_SESSION['id'];
				if((isset($_POST["x"])) and ($_POST["x"]==="ログアウト")){
					if (ini_get("session.use_cookies")) {
						$params = session_get_cookie_params();
						setcookie(session_name(), '', time() - 42000);
						session_destroy();
						unset($_SESSION["id"]);
						unset($_SESSION["error_status"]);
						header("HTTP/1.1 301 Moved Permanently");
						header("Location: login.php");
						exit();
					}
				}
				echo "<div id=\"temp\">";
				if (!isset($_SESSION['id'])){
					$_SESSION["error_status"] = 0;
					header("HTTP/1.1 301 Moved Permanently");
					header("Location: login.php");
					exit();
				}
				if ($_SESSION["error_status"] == 3) {
					echo "<h2 class='err' style='color:red'>DB接続中にエラーが発しました。</h2>";
				}
				if ($_SESSION["error_status"] == 4) {
					echo "<h2 class='err' style='color:red'>処理が完了しました。</h2>";
				}
				if ($_SESSION["error_status"] == 5) {
					if(isset($_SESSION["error_msg"])){echo "<h4 class='err' style='color:red'>".$_SESSION["error_msg"]."</h4>";}
				}	
				if ($_SESSION["error_status"] == 7) {
					echo "<h2 class='err' style='color:red'>変更する場合は編集ボタンを押してください</h2>";
				}
				if ($_SESSION["error_status"] == 8) {
					echo "<h2 class='err' style='color:red'>検温値を選択してください。</h2>";
				}
				$_SESSION["error_status"]=0;
				echo "</div>";
			?>
			<meta name="viewport" id="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
			<form name="form1" class="t" action="regist.php" method="POST">
				<?php	//項目並び順
					echo "<div class=\"common\ name=\"日付１\"><strong>利用日</strong> : </div>";
				?>
				<input type="date" name="日付" value="" style\"width:100%">
				<!--車種、車番、空気圧をプルダウンで連動して選択する（一度選択したらその後空白は選べなくなる）-->
				<script>
					function changeCarInfo(){
						location.href="https://moo-mikipulley.ssl-lolipop.jp/cdd/welcome.php?kind="+document.getElementsByName("車種")[0].value;
					}
					//日付の初期表示
					window.onload = function() {
						//車種名確認
						var date = new Date();
						var year = date.getFullYear();
						var month = date.getMonth()+1;
						var day = date.getDate();
						document.querySelector("body > form.t > input[type=date]:nth-child(2)").value=year + '-' + ('0' + month).slice(-2) + '-' + ('0' + day).slice(-2);
					}
				</script>
			<div class="cs-common"><strong>車種</strong> : <font color="red">※必須</font>
				<select id="車種" name="車種" required onChange="changeCarInfo();">
					<option value="" selected></option>
					<option value="バネット">バネット</option>
					<option value="MAZDA3">MAZDA3</option>
					<option value="ヤリス">ヤリス</option>
				</select></div>
				<!--変更できません--　ヨシガエ>
				<div class="cs-common">車番 : 
				<!--変更できません　end-->
				<div class="cs-common"><strong>車番</strong> : <font color="red">※変更不可</font>
					<select name="車番" id="車番" style="pointer-events: none;" tabindex="-1">
						<option value="" selected></option>
						<option value="相模502ね1079">相模502ね1079</option>
						<option value="福山300も1744">福山300も1744</option>
						<option value="相模502は8071">相模502は8071</option>
					</select>
				</div>
				<hr>
				<strong>始業点検チェック</strong> : 
				<label class="check_lb">
					<input type="checkbox" name="始業点検チェック" value="始業点検チェック">始業点検チェック
				</label>
				<strong>除菌チェック</strong> : 
				<label class="check_lb">
					<input type="checkbox" name="除菌チェック" value="除菌チェック">除菌チェック
				</label>
					<input type="hidden" name="t" value="">
<?php
	$出社時メータ="";
	$dbh=DB_CONNE();
	$sql = "SELECT * FROM `車種別直近メータ` WHERE `車種` = '$_GET[kind]'";
	$stmt = $dbh->query($sql);
	if($stmt){
		foreach ($stmt as $row) {
			$出社時メータ=$row["最新のメータ"];
			break;
		}
	}
?>
				<strong>出社時メータ</strong> : 
				<label class="check_lb">
					<input type="number" name="出社時メータ" value="<?php echo $出社時メータ; ?>">km
				</label>
				<strong>帰社時メータ</strong> : <font color="red">※返却時に記入</font>
				<label class="check_lb">
					<input type="number" name="帰社時メータ" value="">km
				</label>
				<strong>行き先</strong> : 
				<label class="check_lb">
					<input type="text" name="行き先" value="">
				</label>
				<hr>
<?php
	echo date("Y")."/".date("m")."/".date("d")."：登録済み一覧";
	$dbh=DB_CONNE();
	$sql = "SELECT * FROM `自動車運転日報` WHERE 1 ORDER BY `t` DESC";
	// SQLステートメントを実行し、結果を変数に格納さ
	$stmt = $dbh->query($sql);
	echo '<table border="1" style="width: 100%;"><tbody border-style:solid;>';
	$p="";
	echo '<tr><td></td><td>日付</td><td>車種</td><td>車番</td><td>行き先</td><td>出社時メータ</td><td>帰社時メータ</td><td style="display:none"><input type="text" value="現在時刻"></td></tr>';
	if($stmt){
		foreach ($stmt as $row) {
			$p=$row['変更用']."t：value：".$row["t"]."、";
			echo '<tr class="pagetop"><td><input type="button" value="呼出" onclick="modPage(\''.$p.'\');"></td><td>'.$row["日付"].'</td><td>'.$row["車種"].'</td><td>'.$row["車番"].'</td><td>'.$row["行き先"].'</td><td>'.$row["出社時メータ"].'</td><td>'.$row["帰社時メータ"].'</td><td style="display:none">'.$row["t"].'</td></tr>';
		}
	}
	echo "</tbody></table>";
?>
				<hr>
				<p><font color="red">※異常が発覚した場合にチェックしてください。</font></p>
				<strong>灯火類</strong> : 
				<label class="check_lb">
					<input type="checkbox" name="灯火" value="ヘッドライト">ヘッドライト
				</label>
				<label class="check_lb">
					<input type="checkbox" name="灯火１" value="ブレーキランプ">ブレーキランプ
				</label>
				<label class="check_lb">
					<input type="checkbox" name="灯火２" value="ウィンカー">ウィンカー
				</label>
				<strong>タイヤ</strong> : 
				<label class="check_lb">
					<input type="checkbox" name="タイヤ" value="空気圧">空気圧
				</label>
				<label class="check_lb">
					<input type="checkbox" name="タイヤ１" value="サイドの損傷">サイドの損傷
				</label>
				<label class="check_lb">
					<input type="checkbox" name="タイヤ２" value="溝の深さ">溝の深さ
				</label>
				<strong>ボディー</strong> : 
				<label class="check_lb">
					<input type="checkbox" name="ボディー" value="キズ">キズ
				</label>
				<label class="check_lb">
					<input type="checkbox" name="ボディー１" value="凹み">凹み
				</label>
				<strong>警告灯</strong> : 
				<label class="check_lb">
					<input type="checkbox" name="警告灯" value="ガソリン残量">ガソリン残量
				</label>
				<label class="check_lb">
					<input type="checkbox" name="警告灯１" value="その他">その他 
				</label>
				<!--車種、車番、空気圧プルダウン-->

				<!--チェックボックス-->
				<hr>
				<div style="display:none;">
				<label class="check_lb">
				<strong>タイヤ空気圧補充</strong> : 
				</label>
				<label class="check_lb">
					<input type="date" name="月次点検日" value="" style\"width:100%">
				<label style="display:none;" class="check_lb">
					<input type="checkbox" name="タイヤ空気圧補充" value="タイヤ空気圧補充">
				</label>
				<label class="check_lb">
				<strong>前輪</strong> : 
				</label>
				<label class="check_lb">
					<input type="number" name="実施前" value="">KPA
				</label>
				<label class="check_lb">
				<strong>後輪</strong> : 
				</label>
				<label class="check_lb">
					<input type="number" name="実施後" value="">KPA
				</label>

				<div class="cs-common"><strong>指定空気圧</strong> : <font color="red">※変更不可</font>
					<select name="指定空気圧" style="pointer-events: none;" tabindex="-1">
					<option value="" selected></option>
					<option value="前 300KPA　後 300KPA">前 300KPA　後 300KPA</option>
					<option value="前 250KPA　後 250KPA">前 250KPA　後 250KPA</option>
					<option value="前 250KPA　後 240KPA">前 250KPA　後 240KPA</option>
					</select>
				</div>
				<label class="check_lb">
				<strong>洗車実施日</strong> : 
				</label>
				<label class="check_lb">
					<input type="date" name="洗車実施日" value="" style\"width:100%">
				</label>
				<label class="check_lb" style="display:none;">
					<input type="checkbox" name="洗車" value="洗車"><strong>洗車</strong> : 
				</label>
				<hr>
			</div>
				<label class="check_lb">
					<input class="pagetop" type="submit" name="登録種別" value="登録";>
					<input class="pagetop" type="submit" name="登録種別" value="変更";>
					<input class="pagetop" type="submit" name="登録種別" value="削除";>
				</label>
			</form>
			<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
<?php
	echo "<p></p><table border='2' style='width: 100%;'><tbody border-style:solid;><th align='left'>タイヤ空気補充日</th><th align='left'>車種</th><th align='right'>現在空気圧(KPA)</th><th align='right'>指定空気圧(KPA)</th>";
	$dbh=DB_CONNE();
	$sql = "SELECT * FROM `タイヤ空気圧` WHERE `車種` = '$_GET[kind]' ORDER BY `補充日` DESC";
	// SQLステートメントを実行し、結果を変数に格納さ
	$stmt = $dbh->query($sql);
	if($stmt){
		foreach ($stmt as $row) {
			if($row["補充日"]=="0000-00-00"){
				$補充日="no Data";
			}else{
				$補充日=$row["補充日"];
			}
			echo "<tr><td align='left'>".$補充日."</td><td align='left'>".$row["車種"]."</td><td align='right'>前 ".$row["補充後前輪空気圧"]."KPA　後 ".$row["補充後後輪空気圧"]."KPA</td><td align='right'>".$row["指定空気圧"]."</td></tr>";
			break;
		}
	}
	$dbh=null;
	echo "</tbody></table>";
?>
			<script>
var userAgent = window.navigator.userAgent.toLowerCase();
if(userAgent.indexOf('trident') != -1) {
    var kind = (location.href).replace("https://moo-mikipulley.ssl-lolipop.jp/cdd/welcome.php?kind=","");
}else{
    var url = new URL(window.location.href);
    var params = url.searchParams;
    var kind=params.get('kind');
}
if (kind.indexOf('バネット') != -1) {
    var no=1;
}
if(kind.indexOf('MAZDA3') != -1) {
    var no=2;
}
if (kind.indexOf('ヤリス') != -1) {
    var no=3;
}
document.getElementsByName('車種')[0].options[no].selected = true;
document.getElementsByName('車番')[0].options[no].selected = true;
document.getElementsByName('指定空気圧')[0].options[no].selected = true;
		</script>
			</script>
<!--	<font color="red">データ一覧は、POWEREGGの「データ共有」→「Webデータベース」→「総務関連」の自動車運転日報(DB)から閲覧できます。</font> -->
		<p style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</label>
</body>
</html>
