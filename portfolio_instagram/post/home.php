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

// 投稿を取得する
$page = $_REQUEST['page'];
if ($page == '') {
  $page = 1;
}
$page = max($page, 1);

// 最終ページを取得する
$counts = $dbh->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$max_page = ceil($cnt['cnt'] / 5);
$page = min($page, $max_page);

$start = ($page - 1) * 5;

$posts = $dbh->prepare('SELECT u.name, u.user_picture, p.* FROM users u, posts p WHERE u.id=p.user_id ORDER BY p.created DESC LIMIT ?, 5');

$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

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
          <div class="my-2">
            <img src="../user_picture/<?php echo h($post['user_picture']); ?>" width="40" height="40" style="border-radius: 50%;"> @<?php echo h($post['name']); ?>
          </div>
          <img src="../posted_picture/<?php echo h($post['picture']); ?>" width="430" height="430" class="mx-auto">
          <p class="mt-2">
            <img src="../images/my_img/heart.svg" width="40" height="40">
            <span class="font-weight-light">&nbsp;<?php echo h($post['created']); ?></span>
            <?php if ($_SESSION['id'] == $post['user_id']) : ?>
              &nbsp;[<a href="delete.php?id=<?php echo h($post['id']); ?>" class="text-danger">削除</a>]
            <?php endif; ?>
          </p>
          <p><?php echo nl2br(h($post['message'])); ?></p>
          <hr class="mb-1">
        <?php endforeach; ?>
      </div>
      <ul class="paging mx-auto">
        <?php if ($page > 1) : ?>
          <a href="home.php?page=<?php echo ($page - 1); ?>">前のページへ</a>
        <?php else : ?>
          前のページへ&nbsp;&nbsp;
        <?php endif; ?>
        <?php if ($page < $max_page) : ?>
          <a href="home.php?page=<?php echo ($page + 1); ?>">次のページへ</a>
        <?php else : ?>
          &nbsp;&nbsp;次のページへ
        <?php endif; ?>
      </ul>
    </div>
  </div>
  </div>
  </div>
  <?php
  include('../common/__footer.php');
  include('../common/__foot.php');
  ?>