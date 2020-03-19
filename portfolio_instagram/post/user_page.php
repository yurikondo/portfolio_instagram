<?php
session_start();
require('../common/dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  // ログインしている
  $_SESSION['time'] = time();

  $users = $dbh->prepare('SELECT * FROM users WHERE id=?');
  $users->execute(array($_SESSION['id']));
  $user = $users->fetch();
} else {
  // ログインしていない
  header('Location: ../login.php');
  exit();
}

$page_title = 'ユーザーページ';
include('../common/__head.php');
include('../common/__header.php');
include('../common/__function.php');
?>

<body class="bg-light">
  <div class="container">
    <div class="card text-center mx-auto  my-5" style="max-width: 500px">
      <div class="card-body">
        <h2 class="card-text mb-5 pt-2">ユーザーページ</h2>
        <div class="mx-auto">
          <img src="../user_picture/<?php echo $user['user_picture']; ?>" width="40" height="40" style="border-radius: 50%;"> @<?php echo h($user['name']); ?>
          <a href="logout.php">&nbsp;ログアウトする</a>
        </div>
      </div>
      <img src="../images/instagram_icon.svg" width="250" class="my-5 mx-auto">
    </div>
  </div>
  <?php
  include('../common/__footer.php');
  include('../common/__foot.php');
  ?>