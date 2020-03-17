<?php
session_start();
require('../common/dbconnect.php');

if (!isset($_SESSION['join'])) {
  header('Location: index.php');
  exit();
}

if (!empty($_POST)) {
  // 登録処理をする
  $sql = 'INSERT INTO users SET name=?, email=?, password=?, user_picture=?, created=NOW()';
  $stmt = $dbh->prepare($sql);
  echo $rec = $stmt->execute(array(
    $_SESSION['join']['name'],
    $_SESSION['join']['email'],
    sha1($_SESSION['join']['password']),
    $_SESSION['join']['image']
  ));
  unset($_SESSION['join']);

  header('Location: thanks.php');
  exit();
}

$page_title = '確認画面';
include('../common/__head.php');
include('../common/__function.php');
?>

<body class="bg-light">
  <div class="container">
    <div class="card text-center mx-auto mt-5" style="max-width: 500px">
      <div class="card-body">
        <h1 class="card-title font-weight-bold mt-4" style="font-family: cursive,Comic Sans MS">Instagram</h1>
        <p class="card-text mt-5">内容を確認し、「登録する」ボタンを押してください。</p>
        <form action="" method="post" class="mx-4">
          <input type="hidden" name="action" value="submit">
          <div class="input-group mb-3 mt-5">
            <p>ユーザーネーム：<?php echo h($_SESSION['join']['name']); ?></p>
          </div>
          <div class="input-group mb-3">
            <p>メールアドレス：<?php echo h($_SESSION['join']['email']); ?></p>
          </div>
          <div class="input-group mb-3">
            <p>パスワード　　：【表示されません】</p>
          </div>
          <div class="input-group mb-3">
            <p>アイコン画像　：　<img src="../user_picture/<?php echo h($_SESSION['join']['image']); ?>" width="100" height="100" style="border-radius: 50%;"></p>
          </div>
          <div class="mt-4 mb-2">
            <a href="index.php?action=rewite"><input type="button" value="　戻る　" class="btn btn-success mr-3"></a>


            <input type="submit" value="登録する" class="btn btn-primary">

          </div>
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