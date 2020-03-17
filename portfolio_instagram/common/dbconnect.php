<?php
try {
$dbn = 'mysql:dbname=instagram_db;host=localhost:8889;charset=utf8';
$user = 'root';
$password = 'root';
$dbh = new PDO($dbn, $user, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
echo 'エラー：' . $e->getMessage();
}
