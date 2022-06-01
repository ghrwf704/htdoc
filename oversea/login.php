<?php session_start();
	require_once("function.php");
	header("Content-type: text/html; charset=utf-8");
?>

<!DOCTYPE html>
	<html lang="ja">
	<head>
		<title>海外日次売上・受注・注残取込システム - ログイン<br>Oversea daily sales amount, sales order, outstanding order import system.<br>海外日次销售额、受注、注残获取系统</title>
		<LINK href=<?php echo 'style.css?d='.date("h").date("m").date("s"); ?> rel="stylesheet" type="text/css">
		<meta name="viewport" content=width=device-width, initial-scale=1.0>
	</head>
	<body>
<script type="text/javascript">
$(function(){
    location.hash = "#prepage";
    $(window).on("hashchange", function(){
        location.hash = "#prepage2";
    });
});
</script>
	<h5 style="text-align:left">本システムの使用中は、意図せぬ動作となる可能性があるのでブラウザの「戻る」「進む」「更新」機能は使用しないでください。<br>There is possibility of unintentional actions when this system is being used. Do not use the function of browser 「return」,「forward」 and 「update」.<br>在使用本系统时，可能会出现意想不到的动作，所以请不要使用浏览器的“返回”、“前进”、“更新”功能。</h5>
<?php
	error_reporting(0);
	echo "<meta name=\"viewport\" id=\"viewport\" content=\"viewport-fit=cover, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\">";
	echo "<div id=\"temp\">";
	if (isset($_SESSION["error_status"])) {
		if ($_SESSION["error_status"] == 1) {
			echo "<h2 style='color:red'>IDまたはパスワードが異なります。<br>ID or password will be different<br>ID或密码会不同</h2>";
		}
		if ($_SESSION["error_status"] == 2) {
			echo "<h2 style='color:red'>不正なリクエストです<br>Invalid request<br>无效请求。</h2>";
		}
		if ($_SESSION["error_status"] == 3) {
			echo "<h2 style='color:red'>Formatエラー。IDは6桁で入力してください。</h2>";
		}
		if ($_SESSION["error_status"] == 4) {
			echo "<h2 style='color:red'>DB接続中にエラーが発生しました。</h2>";
		}
		if ($_SESSION["error_status"] == 5) {
			//echo "<h2 style='color:red'>パスワードを変更しました。<br>Password changed.<br>密码已更改。</h2>";
		}
		if ($_SESSION["error_status"] == 6) {
			echo "<h2 style='color:red'>パスワードを3回間違えたのでロックされました。<br>総務部　システム課までご連絡ください。（直通：046-257-5105）</h2>";
		}
	}
	echo "</div>";
	//エラー情報のリセット
	$_SESSION["error_msg"]="";
	$_SESSION["error_status"] = 0;
?>
<script>
function passChange(){
	if (document.querySelector("#loginbtn").value=="ログイン"){
		document.querySelector("#login_pass > td:nth-child(1)").innerText="現在のパスワード";
		document.getElementById('loginbtn').value='パスワード変更';
		var ele=document.createElement("tr")
		document.getElementsByTagName("tbody").item(0).appendChild(ele)
		var ele2=document.createElement("td")
		ele2.appendChild(document.createTextNode("新しいパスワード"))
		document.getElementsByTagName("tr").item(2).appendChild(ele2)
		txt3=document.createElement("input")
		txt3.setAttribute("type","id")
		txt3.setAttribute("name","passchange")
		txt3.setAttribute("required","")
		aa=document.createElement("td")
		aa.appendChild(txt3)
		document.getElementsByTagName("tr").item(2).appendChild(aa)

		var txt=document.createElement("p")
		var txt1=document.createElement("strong")
		var txt2=document.createTextNode="新しいパスワードを入力してください。<br>please enter a new password.<br>请输入新密码。";
		txt1.append(txt2)
		txt.append(txt1)
		document.getElementsByTagName("body").item(0).appendChild(txt)
		document.getElementById('loginbtn').value='パスワード変更';
	}
}

function showBack(){
	location.reload()
	if (document.querySelector("#loginbtn").value=="パスワード変更"){
		try{
			document.getElementsByTagName("tr").item(2).remove()
			document.getElementsByTagName("p").item(1).remove()
		}catch{

		}
	}
}	
</script>
		<h2>海外日次売上・受注・注残取込システム - ログイン<br>Oversea daily sales amount, sales order, outstanding order import system.<br>海外日次销售额、受注、注残获取系统</h2>
<h2 style='color:red'></h2>
		<form action="./login_check.php" method="post">
			<table align="center" border="1">
				<tr>
				<td>ID </td>
				<td><input type="id" name="id" placeholder=" Id" required></td>
				</tr>
				<tr id="login_pass">
				<td>
					現在のパスワード<br>Current Password<br>当前密码<br>
				</td>
				<td>
			<input type="password" name="password" placeholder=" Password" value="" required>
				</td>
				</tr>
			</table> 
			<input type="radio" checked name="startup" value="ログイン" onclick="showBack()">ログイン/Login/登录</input>
			<input type="radio" name="startup" value="パスワード変更" onclick="passChange()">パスワード変更/Change Password/更改密码</input>
			<P><input type="submit" value="ログイン" name="loginkind" id="loginbtn" ></P>
		</form>
		<br>
		<?php echo '<p style="text-align: center">Copyright @Miki Pulley Co.,Ltd All Rights Reserved</p>'; ?>
	</body>
</html>