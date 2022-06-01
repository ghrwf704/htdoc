<?php

function DB_CONNE2(){
  $dsn = 'mysql:dbname=LAA1086961-overseas;host=mysql153.phy.lolipop.lan';
  $user = 'LAA1086961';
  $dbpassword = 'M1k1pulley';
  $dbh = new PDO($dsn,$user,$dbpassword);
  $dbh->query('SET NAMES utf8');
  return $dbh;
}

?>