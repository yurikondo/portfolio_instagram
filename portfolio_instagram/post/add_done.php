<?php
session_start();
require('../common/dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time() && isset($_SESSION['add'])) {
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


$page_title = '投稿完了';
include('../common/__head.php');
include('../common/__header.php');
include('../common/__function.php');
?>

<body class="bg-light">
  <div class="container">
    <div class="card text-center mx-auto  my-5" style="max-width: 500px">
      <div class="card-body">
        <h2 class="card-text mb-4 pt-2">投稿しました</h2>
        <img src="../posted_picture/<?php echo h($_SESSION['add']['image']); ?>" width="430" height="430">
        <div class="form-group mt-2">
            <div class="float-left">
              <img src="../user_picture/<?php echo $user['user_picture']; ?>" width="40" height="40" style="border-radius: 50%;"> @<?php echo h($user['name']); ?>
            </div>
        </div><br><br>
        <div class="float-left">
          <?php echo nl2br(h($_SESSION['add']['message'])); ?>
        </div>
        <div class="mt-5">
          <a href="home.php">
            <input type="button" value="ホーム画面へ" class="btn btn-primary">
          </a>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  <?php
  include('../common/__footer.php');
  include('../common/__foot.php');
  ?>