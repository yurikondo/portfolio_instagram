<?php
require('../common/dbconnect.php');

session_start();

if (!empty($_POST)) {
  // エラー項目の確認
  if ($_POST['name'] == '') {
    $error['name'] = 'blank';
  }
  if ($_POST['email'] == '') {
    $error['email'] = 'blank';
  }

  if ($_POST['password'] == '') {
    $error['password'] = 'blank';
  } elseif (strlen($_POST['password']) < 4) {
    $error['password'] = 'length';
  }

  // if ($_POST['image'] == '') {
  //   $error['image'] = 'blank';
  // }
  $file_name = $_FILES['image']['name'];
  if (!empty($file_name)) {
    $ext = substr($file_name, -3);
    if ($ext != 'jpg' && $ext != 'gif' && $ext != 'svg') {
      $error['image'] = 'type';
    }
  }

  // 重複アカウントのチェック
  if (empty($error)) {
    $user = $dbh->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
    $user->execute(array($_POST['email']));
    $record = $user->fetch();
    if ($record['cnt'] > 0) {
      $error['email'] = 'duplicate';
    }
  }

  if (empty($error)) {
    // 画像をアップロードする
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../user_picture/' . $image);
    $_SESSION['join'] = $_POST;
    $_SESSION['join']['image'] = $image;
    header('Location: check.php');
    exit();
  }
}

// 書き直し
if ($_REQUEST['action'] == 'rewite') {
  $_POST = $_SESSION['join'];
  $error['rewite'] = true;
}

$page_title = '新規登録';
include('../common/__head.php');
include('../common/__function.php');
?>

<body class="bg-light">
  <div class="container">
    <div class="card text-center mx-auto mt-5" style="max-width: 500px">
      <div class="card-body">
        <h1 class="card-title font-weight-bold mt-4" style="font-family: cursive,Comic Sans MS">Instagram</h1>
        <p class="card-text mt-5">登録して友達の写真や動画をチェックしよう</p>
        <form action="" method="post" class="mx-4" enctype="multipart/form-data">
          <div class="input-group mb-3 mt-5">
            <input type="text" class="form-control bg-light" placeholder="ユーザーネーム" name="name" maxlength="255" value="<?php echo h($_POST['name']); ?>">
          </div>
          <?php if ($error['name'] == 'blank') : ?>
            <p class="text-danger float-left">* ユーザーネームを入力してください</p>
          <?php endif; ?>
          <div class="input-group mb-3">
            <input type="text" class="form-control bg-light" placeholder="メールアドレス" name="email" maxlength="255" value="<?php echo h($_POST['email']); ?>">
          </div>
          <?php if ($error['email'] == 'blank') : ?>
            <p class="text-danger float-left">* メールアドレスを入力してください</p>
          <?php endif; ?>
          <?php if ($error['email'] == 'duplicate') : ?>
            <p class="text-danger float-left">* 入力されたメールアドレスは、すでに登録されています</p>
          <?php endif; ?>
          <div class="input-group mb-3">
            <input type="password" class="form-control bg-light" placeholder="パスワード" name="password" maxlength="20" value="<?php echo h($_POST['password']); ?>">
          </div>
          <?php if ($error['password'] == 'blank') : ?>
            <p class="text-danger float-left">* パスワードを入力してください</p>
          <?php endif; ?>
          <?php if ($error['password'] == 'length') : ?>
            <p class="text-danger float-left">* パスワードは４文字以上で入力してください</p>
          <?php endif; ?>

          <div class="input-group mb-3">
            <p>アイコン画像</p>
            <input type="file" name="image">
          </div>
          <?php if ($error['image'] == 'type') : ?>
            <p class="text-danger float-left">* 「.gif」「.jpg」「.svg」の画像を選択してください</p>
          <?php elseif (!empty($error)) : ?>
            <p class="text-danger float-left">* 恐れ入りますが、画像を改めて選択してください</p>
          <?php endif; ?>


          <div><input type="submit" value="確認画面へ" class="btn btn-primary mt-4 mb-2"></div>
        </form>
      </div>
    </div>
  </div>
  <div class="card text-center mt-2 mb-5 mx-auto" style="max-width: 500px">
    <div class="card-body">
      <p class="card-text">アカウントをお持ちですか？<a href="../login.php"> ログインする</a></p>
    </div>
  </div>
  </div>


  <?php
  include('../common/__foot.php');
  ?>