<?php 
	session_start();
	header("Content-type: text/html; charset=utf-8");
	require_once("./function.php");
?>
<html><body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
	$_SESSION["error_status"]=0;
	echo "</div>";
?>
	<meta name="viewport" id="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<form name="form1" class="t" action="regist.php" method="POST" onclick="public a=html();">
<?php	//項目並び順
	$TMViewSort=["`T-M地域分類`","`T-Mインプット分類`","`T-M目的分類`","`T-M機能分類`","`T-M活動分類`","`T-M製品区分`","`T-M製品中分類`","顧客名","協力会社名","関連会社名","year","month","day","hour","minute","t"];
	$dbh=DB_CONNE();
	for($i=0;$i<count($TMViewSort);$i++){
		if(strpos($TMViewSort[$i],'T-M') !== false){
			$sql = 'SELECT * FROM '.$TMViewSort[$i];
			// SQLステートメントを実行し、結果を変数に格納
			$stmt = $dbh->query($sql);
			echo " <div class=\"common\">".@mb_convert_kana(str_replace(array("`","T-M"),array("",""),$TMViewSort[$i]),"k")." : </div>";
			if($i==5){
				echo "<style>select[name=".$TMViewSort[$i]."] {  cursor: pointer;}</style>";
				echo "<select name=\"".$TMViewSort[$i]."\" onchange='document.getElementsByName(\"`T-M製品中分類`\").item(0).innerHTML=document.getElementById(document.getElementsByName(\"`T-M製品区分`\").item(0).value).innerHTML'>";
			}else{
				echo "<select name=\"".$TMViewSort[$i]."\">";
			}
	        	echo "<option selected> </option>";
			foreach ($stmt as $row) {
				echo "<option value=\"".$row[1]."\">".$row[1]."</option>";
			}
			echo '</select>';
		}else if($TMViewSort[$i]=="顧客名"){
			echo "<div class=\"common\">".$TMViewSort[$i]." : </div>";
			echo "<select name=\"year\" class=\"cs-common\">";
			  echo "<option selected=\"".date('Y')."><".date('Y')."</option>";
			  echo "<option value=\"".(date('Y'))."\">".(date('Y'))."</option>年";
			  echo "<option value=\"".(date('Y')-1)."\">".(date('Y')-1)."</option>年";
			  echo "</select>"; 
  			  echo "<select name=\"month\" class=\"cs-common\">";
			  echo "<option selected=\"".date('m')."\">".date('m')."</option>";
				for($ii=1;$ii<13;$ii++){
					echo "<option value=".str_pad($ii,2,0,STR_PAD_LEFT).">".str_pad($ii,2,0,STR_PAD_LEFT)." 月"."</option>";
				}
			echo "</select>";
			echo "<select name=\"day\" class=\"cs-common\">";
			echo "<option selected=\"".date('d')."\">".date('d')."</option>";
			for($ii=1;$ii<32;$ii++){
				echo "<option value=".str_pad($ii,2,0,STR_PAD_LEFT).">".str_pad($ii,2,0,STR_PAD_LEFT)." 日"."</option>";
			}
			echo "</select>";
			echo "<div class=\"common\">　<br>作業時間 : </div>";
			echo "<select name=\"hour\" class=\"cs-common2\">";
			echo" <option value=\"0\">0</option>";
			echo" <option value=\"1\">1</option>";
			echo" <option value=\"2\">2</option>";
			echo" <option value=\"3\">3</option>";
			echo" <option value=\"4\">4</option>";
			echo" <option value=\"5\">5</option>";
			echo" <option value=\"6\">6</option>";
			echo" <option value=\"7\">7</option>";
			echo" <option value=\"8\">8</option>";
			echo" <option value=\"9\">9</option>";
			echo "</select>";
			echo "<select name=\"minute\" class=\"cs-common2\">";
			  echo "<option value=\"0\">0</option>";
			  echo "<option value=\"30\">30</option>";
			echo "</select>";
			echo "<p class=\"cs-common2\">顧客名（カタカナ）: <input type=\"text\" class=\"input\" name=\"顧客名\" value=\"\" id=\"text1\"></p>";
		}else if($TMViewSort[$i]=='協力会社名'){
			echo "<p class=\"cs-common2\">協力会社名（カタカナ）: <input type=\"text\" class=\"input\" name=\"協力会社名\" value=\"\" id=\"text2\"></p>";
		}else if($TMViewSort[$i]=='関連会社名'){
			echo "<div id=\"cs-common2\">　関連会社名 : </div>";
			echo "<select name=\"関連会社名\" class=\"cs-common2\">";
			echo "<option selected=\"\"></option>";
			echo" <option value=\"MPSG\">MPSG</option>";
			echo" <option value=\"MPC\">MPC</option>";
			echo" <option value=\"MB\">MB</option>";
			echo" <option value=\"東京メータ\">東京メータ</option>";
			echo "</select>";
		}
	}
?>
<input type="hidden" name="t" value=""><br><br>
<input type="submit" name="登録種別" value="追加" onclick="">
<input type="submit" name="登録種別" value="更新" onclick="">
<input type="submit" name="登録種別" value="削除" onclick="">
	    </form>
<script>
function isDate(strDate){
    // 空文字は無視
    if(strDate == ""){
        return true;
    }  
    // 年/月/日の形式のみ許容する
    if(!strDate.match(/^\d{4}\/\d{1,2}\/\d{1,2}$/)){
        return false;
    } 

    // 日付変換された日付が入力値と同じ事を確認
    // new Date()の引数に不正な日付が入力された場合、相当する日付に変換されてしまうため
    // 
    var date = new Date(strDate);  
    if(date.getFullYear() !=  strDate.split("/")[0] 
        || date.getMonth() != strDate.split("/")[1] - 1 
        || date.getDate() != strDate.split("/")[2]
    ){
        return false;
    }

    return true;
}
</script>
<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
<?php
	//分類コード別のHTML作成しておく（ON、OFFはjavascriptで行う）
	$table="`T-M製品中分類`";
	$sql = "SELECT DISTINCT 分類コード FROM ".$table;
	// SQLステートメントを実行し、結果を変数に格納
	$dbh=DB_CONNE();
	$stmt = $dbh->query($sql);
	foreach ($stmt as $row) {
		$tyubunrui="`T-M製品中分類`";
		$code=trim($row['分類コード']);
		$sql = "SELECT 製品中分類 FROM `T-M製品中分類` WHERE `分類コード` LIKE '%".trim($code)."%'";
		// SQLステートメントを実行し、結果を変数に格納
		$stmt = $dbh->query($sql);

		echo "<div class=\"enable\"><div style=\"display: none;\" id=\"".$code."\">";
		echo "<select name=\""."\">";
	        	echo "<option selected> </option>";
		foreach ($stmt as $row1) {
			echo "<option value=\"".$row1[0]."\">".$row1[0]."</option>";
		}
		echo '</select></div></div>';
	}
	$dbh=null;
?>	
    <script>
        function chkdate() {
		var nenn=document.getElementsByName("year").item(0).getElementsByTagName("option").item(0).innerText;
		var month=document.getElementsByName("month").item(0).getElementsByTagName("option").item(0).innerText;
		var day=document.getElementsByName("day").item(0).getElementsByTagName("option").item(0).innerText;
		return nenn+"/"+month+"/"+day;
        }
        function modPage(arr) {
		var TMViewSort=["`T-M地域分類`","`T-Mインプット分類`","`T-M目的分類`","`T-M機能分類`","`T-M活動分類`","`T-M製品区分`","`T-M製品中分類`","顧客名","協力会社名","関連会社名","year","month","day","hour","minute","t"];
		arr1=arr.split(",");
		sleep(100);
		for(i=0;i<TMViewSort.length;i++){
			document.getElementsByName(TMViewSort[i]).item(0).value=arr1[i];
    	        }
	}
	function sleep(waitMsec) {
	  var startMsec = new Date();
	  while (new Date() - startMsec < waitMsec);
	}
	function html(){
		return document.getElementsByTagName("html").outerHTML;
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
                if(tmp[cnt].match(/^[0-9]+$/)==null){
                    tmp[cnt] = "";
                }
            break;
        }
    }
    return tmp.join("");
}
$(document).ready(function(e) {
    $("#text1").change(function(e) {
        $(this).val(checkChara("katakana",$(this).val()));
    });
    $("#text2").change(function(e) {
        $(this).val(checkChara("katakana",$(this).val()));
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
	//$a=isset($_POST["`T-Mインプット分類`"]) ? document.getElementsByName("`T-Mインプット分類`").item(0).value=$_POST["`T-Mインプット分類`"] : document.getElementsByName("`T-Mインプット分類`").item(0).value= "";
	//$b=isset($_POST["`T-M製品区分`"]) ? document.getElementsByName("`T-M製品区分`").item(0).value=$_POST["`T-M製品区分`"] : document.getElementsByName("`T-M製品区分`").item(0).value= "";
	//$c=isset($_POST["`T-M製品中分類`"]) ? document.getElementsByName("`T-M製品中分類`").item(0).value=$_POST["`T-M製品中分類`"] : document.getElementsByName("`T-M製品中分類`").item(0).value= "";
	//$d=isset($_POST["`T-M地域分類`"]) ? document.getElementsByName("`T-M地域分類`").item(0).value=$_POST["`T-M地域分類`"] : document.getElementsByName("`T-M地域分類`").item(0).value= "";
	//$e=isset($_POST["`T-M活動分類`"]) ? document.getElementsByName("`T-M活動分類`").item(0).value=$_POST["`T-M活動分類`"] : document.getElementsByName("`T-M活動分類`").item(0).value= "";
	//$f=isset($_POST["year"]) ? $document.getElementsByName("year").item(0).value=$_POST["year"] : document.getElementsByName("year").item(0).value= "";
	//$g=isset($_POST["month"]) ? document.getElementsByName("month").item(0).value=$_POST["month"] : document.getElementsByName("month").item(0).value= "";
	//$h=isset($_POST["day"]) ? document.getElementsByName("day").item(0).value=$_POST["day"] : document.getElementsByName("day").item(0).value= "";
	//$i=isset($_POST["hour"]) ? document.getElementsByName("hour").item(0).value=$_POST["hour"] : document.getElementsByName("hour").item(0).value= "";
	//$j=isset($_POST["minute"]) ? document.getElementsByName("minute").item(0).value=$_POST["minute"] : document.getElementsByName("minute").item(0).value= "";
	//$k=isset($_POST["contents"]) ? document.getElementsByName("contents").item(0).value=$_POST["contents"] : document.getElementsByName("contents").item(0).value= "";

	echo date("Y")."/".date("m")."/".date("d")."：登録済み一覧";
	$dbh=DB_CONNE();
	$table="`工数`";
	$年月日=strval(date("Y").date("m").date("d"));
	$sql = "SELECT * FROM `工数` WHERE `id` = $id ORDER BY `t` DESC";
	// SQLステートメントを実行し、結果を変数に格納さ
	$stmt = $dbh->query($sql);
	echo '<table border="1" style="width: 100%;"><tbody border-style:solid;>';
	$p="";
	foreach ($stmt as $row) {
		$p=$p.$row['地域分類'].",".$row['インプット分類'].",".$row['目的分類'].",".$row['機能分類'].",".$row['活動分類'].",".$row['製品区分'].",".$row['製品中分類'].",".$row['顧客名'].",".$row['協力会社名'].",".$row['関連会社名'].",".mb_substr($row['年月日'],0,4,"UTF-8").",".mb_substr($row['年月日'],4,2,"UTF-8").",".mb_substr($row['年月日'],6,2,"UTF-8").",".intval($row['分']/60).",".intval($row['分'] % 60).",".$row['t'];
		echo '<tr class="pagetop"><td><input type="button" value="呼出" onclick="modPage(\''.$p.'\')"></td><td>'.$row["年月日"].'</td><td>'.$row["インプット分類"].'</td><td>'.$row["活動分類"].'</td><td>'.$row["分"].'</td></tr>';
		$p="";
	}
	echo "</table></tbody>";
?>
<script>
	document.getElementsByName("`T-M地域分類`").item(0).selectedIndex=1;
</script>
</body>
</html>
