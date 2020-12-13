<?php
session_start();
require('dbconnect.php');

if ($_COOKIE['email'] !== '') {
  $email = $_COOKIE['email'];
}

// 入力内容が空でなければ、loginチェック処理を実施
if (!empty($_POST)) {
  $email = $_POST['email'];

  // email,passwordのどちらかが空であればblankエラー
  if ($_POST['email'] === '' || $_POST['password'] === '') {

    if ($_POST['email'] === '') {
      $error['elogin'] = 'blank';
    }

    if ($_POST['password'] === '') {
      $error['passlogin'] = 'blank';
    }
  } else {
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])     // 同じ文字列であればsha1で暗号化させていれば同じ文字列になる。
    ));
    $member = $login->fetch();     // 同じEmailとPassががあればmemberに何かしらのデータが入る。

    // 一致していればidと現時刻をsessionに保管してページ遷移
    if ($member) {
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      if ($_POST['save'] === 'on') {
        setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14);
      }

      header('Location: index.php');
      exit();
    } else {
      $error['login'] = 'failed';     // 一致していなければfailedエラー
    }
  }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style.css" />
  <title>ログインする</title>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>ログインする</h1>
    </div>
    <div id="content">
      <div id="lead">
        <p>メールアドレスとパスワードを記入してログインしてください。</p>
        <p>入会手続きがまだの方はこちらからどうぞ。</p>
        <p>&raquo;<a href="join/">入会手続きをする</a></p>
      </div>

      <form action="" method="post">
        <dl>
          <dt>メールアドレス</dt>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>" /> <!-- failedエラーが発生しても入力内容は消えない -->

            <?php if ($error['elogin'] === 'blank') : ?>
              　　<p class="error">メールアドレスを入力してください</p>
            <?php endif; ?>

          </dd>
          <dt>パスワード</dt>
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" /> <!-- failedエラーが発生しても入力内容は消えない -->

            <?php if ($error['passlogin'] === 'blank') : ?>
              　　<p class="error">パスワードを入力してください</p>
            <?php endif; ?>
            <?php if ($error['login'] === 'failed') : ?>
              　　<p class="error">*ログインに失敗しました。正しいメールアドレスとパスワードを入力してください。</p>
            <?php endif; ?>

          </dd>
          <dt>ログイン情報の記録</dt>
          <dd>
            <input id="save" type="checkbox" name="save" value="on">
            <label for="save">次回からは自動的にログインする</label>
          </dd>
        </dl>
        <div>
          <input type="submit" value="ログインする" />
        </div>
      </form>
    </div>
    <div id="foot">
      <p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O Space. MYCOM" /></p>
    </div>
  </div>
</body>

</html>