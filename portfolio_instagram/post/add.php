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


if (!empty($_POST)) {
  // エラー項目の確認
  if ($_POST['message'] == '') {
    $error['message'] = 'blank';
  }

  if ($_FILES['image']['name'] == ''){
    $error['picture'] = 'blank';
  }

  $file_name = $_FILES['image']['name'];
  if (!empty($file_name)) {
    $ext = substr($file_name, -3);
    if ($ext != 'jpg' && $ext != 'gif' && $ext != 'svg') {
      $error['image'] = 'type';
    }
  }

  if (empty($error)) {
    // 画像をアップロードする
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../posted_picture/' . $image);
    $_SESSION['add'] = $_POST;
    $_SESSION['add']['image'] = $image;

    $posted_pic_mes = $dbh->prepare('INSERT INTO posts SET user_id=?,picture=?,message=?,created=NOW()');
    $posted_pic_mes->execute(array(
      $user['id'],
      $image,
      $_POST['message']
    ));
    header('Location: add_done.php');
    exit();
  }
}

$page_title = '新規投稿';
include('../common/__head.php');
include('../common/__header.php');
include('../common/__function.php');
?>

<body class="bg-light">
  <div class="container">
    <div class="card text-center mx-auto  my-5" style="max-width: 500px">
      <div class="card-body">
        <h2 class="card-text mb-5 pt-2">新規投稿</h2>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="input-group mb-3">
            <input class="mx-auto" type="file" name="image"><br><br>
            <?php if ($error['image'] == 'type') : ?>
              <p class="text-danger mx-auto">* 「.gif」「.jpg」「.svg」の画像を選択してください</p>
            <?php elseif (!empty($error)) : ?>
              <p class="text-danger mx-auto">* 恐れ入りますが、画像を改めて選択してください</p>
            <?php endif; ?>
          </div>
          <img src="../images/instagram_icon.svg" width="250">
          <div class="form-group mt-3">
            <label for="exampleFormControlTextarea1" class="float-left">
              <div class="float-left">
                <img src="../user_picture/<?php echo $user['user_picture']; ?>" width="40" height="40" style="border-radius: 50%;"> @<?php echo h($user['name']); ?> コメント...
                <?php if ($error['message'] == 'blank') : ?>
                  <span class="text-danger">* 入力してください</span>
                <?php endif; ?>
              </div>
            </label>
            <textarea name="message" class="form-control" id="exampleFormControlTextarea1" rows="3"><?php echo h($_POST['message']); ?></textarea>
          </div>
          <div class="mt-4">
            <input type="submit" value="投稿する" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
  include('../common/__footer.php');
  include('../common/__foot.php');
  ?>