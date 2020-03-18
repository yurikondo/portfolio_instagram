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

// 投稿を取得する
$posts = $dbh->query('SELECT u.name, u.user_picture, p.* FROM users u, posts p WHERE u.id=p.user_id ORDER BY p.created DESC');

$page_title = 'ホーム';
include('../common/__head.php');
include('../common/__header.php');
include('../common/__function.php');
?>

<body class="bg-light">
  <div class="container">
    <div class="card my-5 mx-auto" style="max-width: 500px">
      <div class="card-body">
        <?php foreach ($posts as $post) : ?>
          <div class="mb-2">
            <img src="../user_picture/<?php echo h($post['user_picture']); ?>" width="40" height="40" style="border-radius: 50%;"> @<?php echo h($post['name']); ?>
          </div>
          <img src="../posted_picture/<?php echo h($post['picture']); ?>" width="430" height="430" class="mx-auto">
          <p class="mt-2">
            <img src="../images/my_img/heart.svg" width="40" height="40">
            <span class="font-weight-light">&nbsp;<?php echo h($post['created']); ?></span>
            <?php if ($_SESSION['id'] == $post['member_id']): ?>
              [<a href="delete.php?id=<?php echo h($post['id']); ?>" class="text-danger">削除</a>]
            <?php endif; ?>
          </p>
          <p><?php echo h($post['message']); ?></p>
          <hr>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  </div>
  </div>
  <?php
  include('../common/__footer.php');
  include('../common/__foot.php');
  ?>