<?php


function DB_CONNE(){
  $dsn = 'mysql:dbname=LAA1086961-miki3;host=mysql145.phy.lolipop.lan';
  $user = 'LAA1086961';
  $dbpassword = 'M1k1pulley';
  $dbh = new PDO($dsn,$user,$dbpassword);
  $dbh->query('SET NAMES utf8');
  return $dbh;
}

function sending_mail($to,$subj,$mess,$from) {	
	header('Content-Type: text/html; charset=UTF-8');
	header('Content_Language: ja');
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mb_send_mail($to, $subj, $mess, $from); //メール送信
	return true;
}
/*
* CSRF トークン作成
*/
function get_csrf_token() {
  $TOKEN_LENGTH = 16;//16*2=32byte
  $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
  return bin2hex($bytes);
}
 
/*
* パスワードをソルト＋ストレッチング 
*/
function strechedPassword($salt, $password){
    $hash_pass = "";
 
    for ($i = 0; $i < STRETCH_COUNT; $i++){
        $hash_pass  = hash("sha256", ($hash_pass . $salt . $password));
     }
 
     return $hash_pass;	
}
 
/*
* ソルトを作成
*/
function get_salt() {
  $TOKEN_LENGTH = 4;//4*2=8byte
  $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
  return bin2hex($bytes);
}
 
/*
* URL の一時パスワードを作成
*/
function get_url_password() {
  $TOKEN_LENGTH = 16;//16*2=32byte
  $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
  return hash("sha256", $bytes);
}


?>