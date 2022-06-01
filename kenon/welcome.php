<?php 
	session_start();
	if(is_null($_SESSION['名前'])){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: login.php");
		exit();
	}
	header("Content-type: text/html; charset=utf-8");
	require_once("../function.php");
?>
<html>
	<head>
		<title>検温ＷＥＢシステム</title>
	</head>
<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<form name="form" action="login.php" method="POST">
<div id="top"><div id="indivisual"><?php echo $_SESSION['名前']."さん"; ?></div><input type="submit" class="container" value="ログアウト" name="x" onclick="submitAction('welcome.php')" style></div>
</form>
<script>
$(document).ready(function() {
  var pagetop = $('.pagetop');
    $(window).scroll(function () {
       if ($(this).scrollTop() > 100) {
            //pagetop.fadeIn();
       } else {
            //pagetop.fadeOut();
	            }
       });
       pagetop.click(function () {
           $('body, html').animate({ scrollTop: 0 }, 500);
		$("#temp").remove()
              return true;
   });
});
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
	echo "<br><div id=\"temp\">";
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
	echo "<div class=\"common\ name=\"検温日付\">検温日時 : <font color='red'>*必須</font></div>";
	$year=date("Y");
	$month=date("m");
	$day=date("d");
	$時=date("H");
	$分=date("i");
	echo "<input type=\"datetime-local\" name=\"datetime\" required value=\"{$year}-{$month}-{$day}T{$時}:{$分}\" style=\"width:100%\">";
	echo "<!--<p class=\"cs-common2\">検温値（数字）: <input type=\"number\" class=\"input\" step=\"0.1\" name=\"検温値1\" value=\"\" id=\"text1\" required></p>-->";
	echo "<div class=\"cs-common\">検温値(度): <font color='red'>*必須　（34度～40度)</font>
<select name=\"検温値1\">
<option value=\"34\">34.0</option>
<option value=\"34.1\">34.1</option>
<option value=\"34.2\">34.2</option>
<option value=\"34.4\">34.3</option>
<option value=\"34.4\">34.4</option>
<option value=\"34.5\">34.5</option>
<option value=\"34.6\">34.6</option>
<option value=\"34.7\">34.7</option>
<option value=\"34.8\">34.8</option>
<option value=\"34.9\">34.9</option>
<option value=\"35\">35.0</option>
<option value=\"35.1\">35.1</option>
<option value=\"35.2\">35.2</option>
<option value=\"35.3\">35.3</option>
<option value=\"35.4\">35.4</option>
<option value=\"35.5\">35.5</option>
<option value=\"35.6\">35.6</option>
<option value=\"35.7\">35.7</option>
<option value=\"35.8\">35.8</option>
<option value=\"35.9\">35.9</option>
<option value=\"36\">36.0</option>
<option value=\"36.1\">36.1</option>
<option value=\"\" selected></option>
<option value=\"36.2\">36.2</option>
<option value=\"36.3\">36.3</option>
<option value=\"36.4\">36.4</option>
<option value=\"36.5\">36.5</option>
<option value=\"36.6\">36.6</option>
<option value=\"36.7\">36.7</option>
<option value=\"36.8\">36.8</option>
<option value=\"36.9\">36.9</option>
<option value=\"37\">37.0</option>
<option value=\"37.1\">37.1</option>
<option value=\"37.2\">37.2</option>
<option value=\"37.3\">37.3</option>
<option value=\"37.4\">37.4</option>
<option value=\"37.5\">37.5</option>
<option value=\"37.6\">37.6</option>
<option value=\"37.7\">37.7</option>
<option value=\"37.8\">37.8</option>
<option value=\"37.9\">37.9</option>
<option value=\"38\">38.0</option>
<option value=\"38.1\">38.1</option>
<option value=\"38.2\">38.2</option>
<option value=\"38.3\">38.3</option>
<option value=\"38.4\">38.4</option>
<option value=\"38.5\">38.5</option>
<option value=\"38.6\">38.6</option>
<option value=\"38.7\">38.7</option>
<option value=\"38.8\">38.8</option>
<option value=\"38.9\">38.9</option>
<option value=\"39\">39.0</option>
<option value=\"39.1\">39.1</option>
<option value=\"39.2\">39.2</option>
<option value=\"39.3\">39.3</option>
<option value=\"39.4\">39.4</option>
<option value=\"39.5\">39.5</option>
<option value=\"39.6\">39.6</option>
<option value=\"39.7\">39.7</option>
<option value=\"39.8\">39.8</option>
<option value=\"39.9\">39.9</option>
<option value=\"40\">40.0</option>
</select></div>";
echo "<div class=\"cs-common\">体調: <font color='red'>*必須</font></font>
<select name=\"体調\">
<option value=\"良好\" selected>良好</option>
<option value=\"体調不良\">体調不良</option>
</select></div>";
echo "<div class=\"cs-common\" style='display:none' name=\"taityo\">症状: 
<select name=\"症状\">
<option value=\"\" selected></option>
<option value=\"風邪症状\">風邪症状</option>
<option value=\"それ以外\">それ以外</option>
</select></div>";
?>
<input type="hidden" name="t" value=""><br><br>
<input class="pagetop" type="submit" name="登録種別" value="登録" onclick="javascript:document.querySelector('body > form.t > input[type=submit]:nth-child(30)').disable=true";>
<input class="pagetop" type="submit" name="登録種別" value="更新" onclick="javascript:document.querySelector('body > form.t > input[type=submit]:nth-child(31)').disable=true";>
<input class="pagetop" type="submit" name="登録種別" value="削除" onclick="javascript:document.querySelector('body > form.t > input[type=submit]:nth-child(32)').disable=true";>
</form>
<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
    <script>
$('select[name="体調"]').eq(0).change(function() {
	var extraction_val = $('select[name="体調"]').eq(0).val();
	if(extraction_val == "体調不良") {
		document.querySelector("body > form.t > div:nth-child(5)").style.display='block';
	}else if(extraction_val == "良好") {
		document.querySelector("body > form.t > div:nth-child(5) > select").value="";
		document.querySelector("body > form.t > div:nth-child(5)").style.display='none';
	}
});
function modPage(arr) {
	var TMViewSort=["検温値1","t", "datetime","体調","症状"];
	arr1=arr.split(",");
	sleep(100);
	for(i=0;i<=TMViewSort.length-1;i++){
		document.getElementsByName(TMViewSort[i]).item(0).value=arr1[i];
		if(arr1[i]!=""){
			document.getElementsByName("taityo").item(0).style.display='block';
			document.getElementsByName(TMViewSort[i]).item(0).style.display='block';

		}else if(arr1[i]==""){
			document.getElementsByName("taityo").item(0).style.display='none';
			document.getElementsByName(TMViewSort[i]).item(0).style.display='none';
			document.getElementsByName(TMViewSort[i]).item(0).value='';
		}
    }
}
function sleep(waitMsec) {
  var startMsec = new Date();
  while (new Date() - startMsec < waitMsec);
}

function checkChara(_type,_str){
    var tmp = _str.split("");
    for(var cnt=0;cnt<tmp.length;cnt++){
        switch(_type){
            case "katakana":
                if(tmp[cnt].match(/^[ァ-ヶー]*$/)==null){
                    tmp[cnt] = "";
                }
            break;
            case "hankaku":
                if(tmp[cnt].match("^[a-zA-Z0-9!-/:-@¥[-`{-~]+$")==null){
                    tmp[cnt] = "";
                }
            break;
            case "number":
                if(tmp[cnt].match(/^[0-9\.]+$/)==null){
                    tmp[cnt] = "";
                }
            break;
        }
    }
    return tmp.join("");
}
$(document).ready(function(e) {
    $("#text1").change(function(e) {
        $(this).val(checkChara("number",$(this).val()));
    });
    $("#text2").change(function(e) {
        $(this).val(checkChara("number",$(this).val()));
    });
});
function resize(Tarea){
	var areaH = Tarea.style.height;
	areaH = parseInt(areaH) - 54;
	if(areaH < 30){ areaH = 30; }
	Tarea.style.height = areaH + "px";
	Tarea.style.height = parseInt(Tarea.scrollHeight + 30) + "px";
}	
// ドキュメント内の全てのテキストエリアを走査して高さ調整関数を適用します
onload = function(){
	var els = document.getElementsByClassName("input");
	for (var i = 0; i < els.length; i++){
		var obj = els[i];
		resize(obj);
		obj.onkeyup = function(){ resize(this); }
	}
}
    </script>
<?php
	echo date("Y")."/".date("m")."/".date("d")."：登録済み一覧";
	$dbh=DB_CONNE();
	$table="`検温結果`";
	$sql = "SELECT * FROM `検温結果` WHERE `id` = '$id' ORDER BY `年月日` DESC , `t` DESC";
	// SQLステートメントを実行し、結果を変数に格納さ
	$stmt = $dbh->query($sql);
	echo '<table border="1" style="width: 100%;"><tbody border-style:solid;>';
	$p="";
	echo '<tr class="pagetop"><td></td><td>日付</td><td>時刻</td><td>検温値</td><td>体調</td><td>症状</td></tr>';
	foreach ($stmt as $row) {
		$color="black";
		$value="";
		if($row['検温値1']>=37.5){$color="red";}
		if($row['flg']==0){
			$p=$row['検温値1'].",".$row['t'].",".$row['年月日']."T".sprintf('%02d',$row['時']).":".sprintf('%02d',$row['分']).",".$row['体調'].",".$row['症状'];
			$value="呼出";
		}
		echo '<tr class="agetop"><td><input type="button" value="'.$value.'" onclick="modPage(\''.$p.'\')"></td><td><font color=\''.$color.'\'>'.$row["年月日"].'</font></td><td><font color="'.$color.'">'.sprintf('%02d',$row["時"]).":".sprintf('%02d',$row["分"]).'</font></td><td><font color="'.$color.'">'.$row["検温値1"].'</font></td><td><font color="'.$color.'">'.$row["体調"].'</font></td><td><font color="'.$color.'">'.$row["症状"].'</font></td></tr>';
		$p="";
	}
	echo "</tbody></table>";
?>
	<!--<a 	href="http://192.168.200.28/pe4j/XDB/XDB0102l.jsf?p1=105301&p2=10178&p3=9803" target="_blank"><p>【テスト中】パワーエッグの検温ＤＢへ（社内システムからのみ閲覧可能）</p></a>-->
	<p style="text-align: left"><font color="red">過去データの変更は「呼出」と表示されている行のみ可能です。それ以外のデータ修正はPOWEREGGの「データ共有」→「Webデータベース」→「総務関連」の検温(DB)からお願いします。</font></p>
<?php
	if($id=="003109" or $id=="002845" or $id=="003553" or $id=="001676" or $id=="003519" or $id=="000931" or $id=="000542"){
		echo '<p style="text-align:left">システム課のメンバーのみ使用（表示）可能です。<br>テキストボックスに入力した社員IDに対して「パスワードの初期化」及び「ロック解除」を行います。</p>';
		echo '<form name="form1" action="regist.php" method="POST" style="text-align:left"><input type="number" placeholder="6桁のIDを入力" name="tid" value=""><input class="pagetop" type="submit" name="リセット" value="リセット"></form>';
		echo "<hr>";
	}
	echo '<p style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</p>';
?>
</body>
</html>
