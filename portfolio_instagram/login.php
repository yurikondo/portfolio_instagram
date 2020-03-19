<?php
require('common/dbconnect.php');

session_start();

if (isset($_COOKIE['email'])) {
  $_POST['email'] = $_COOKIE['email'];
  $_POST['password'] = $_COOKIE['password'];
  $_POST['save'] = 'on';
}

if (!empty($_POST)) {
  // ログインの処理
  if ($_POST['email'] != '' && $_POST['password'] != '') {
    $login = $dbh->prepare('SELECT * FROM users WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $user = $login->fetch();

    if ($user) {
      // ログイン成功
      $_SESSION['id'] = $user['id'];
      $_SESSION['time'] = time();

      // ログイン情報を記録する
      if ($_POST['save'] == 'on') {
        setcookie('email', $_POST['email'], time() + 60 * 60 * 4 * 14);
        setcookie('password', $_POST['password'], time() + 60 * 60 * 4 * 14);
      }
      header('Location: post/home.php');
      exit();
    } else {
      $error['login'] = 'failed';
    }
  } else {
    $error['login'] = 'blank';
  }
}
$page_title = 'ログイン';
include('common/__head.php');
include('common/__function.php');
?>

<body class="bg-light">
  <div class="container">
    <div class="card text-center mx-auto mt-5" style="max-width: 500px">
      <div class="card-body">
        <h1 class="card-title font-weight-bold mt-4" style="font-family: cursive,Comic Sans MS">Instagram</h1>
        <form action="" method="post" class="mx-4">
          <div class="input-group mb-3 mt-5">
            <input type="text" class="form-control bg-light" placeholder="メールアドレス" name="email" aria-describedby="basic-addon1" value="<?php echo h($_POST['email']); ?>">
          </div>
          <?php if ($error['login'] == 'blank') : ?>
            <p class="text-danger float-left">* メールアドレスとパスワードを入力してください</p>
          <?php endif; ?>
          <?php if ($error['login'] == 'failed') : ?>
            <p class="text-danger float-left">* ログインに失敗しました。正しく入力してください</p>
          <?php endif; ?>
          <div class="input-group mb-3">
            <input type="password" class="form-control bg-light" placeholder="パスワード" name="password" aria-describedby="basic-addon1" value="<?php echo h($_POST['password']); ?>">
          </div>
          <input class="mt-3" type="checkbox" id="save" name="save" value="on"><label for="save">　次回からは自動的にログインする</label>
          <div><input type="submit" value="ログイン" class="btn btn-primary mt-4 mb-2"></div>
        </form>
      </div>

    </div>
  </div>
  <div class="card text-center mt-2 mx-auto" style="max-width: 500px">
    <div class="card-body">
      <p class="card-text">アカウントをお持ちでないですか？<a href="join/index.php"> 登録する</a></p>
    </div>
  </div>
  </div>


  <?php
  include('common/__foot.php');
  ?>