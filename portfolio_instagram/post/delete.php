<?php
session_start();
require('../common/dbconnect.php');

if (isset($_SESSION['id'])){
  $id = $_REQUEST['id'];

  // 投稿を検査する
  $messages = $dbh->prepare('SELECT * FROM posts WHERE id=?');
  $messages->execute(array($id));
  $message = $messages->fetch();

  if ($message['user_id'] == $_SESSION['id']){
    // 削除する
    $del = $dbh->prepare('DELETE FROM posts WHERE id=?');
    $del->execute(array($id));
  }
}

header('Location: home.php');
exit();