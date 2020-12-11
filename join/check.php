<?php
session_start();

if (!isset($_SESSION['join'])) {     // $_SESSION['join'](入力画面)が空の時に実行する（再度チェック）
	header('Location: index.php');     // 不正にチェック画面にアクセス（入力画面を通過せずにチェック画面へいくこと）できないように入力画面へ遷移する。
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>会員登録</h1>
		</div>

		<div id="content">
			<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
			<form action="" method="post">
				<input type="hidden" name="action" value="submit" />
				<dl>
					<dt>ニックネーム</dt>
					<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
					<dd>
					</dd>
					<dt>メールアドレス</dt>
					<?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
					<dd>
					</dd>
					<dt>パスワード</dt>
					<dd>
						【表示されません】
					</dd>
					<dt>写真など</dt>
					<?php if ($_SESSION['join']['image'] !== '') : ?>
						<!-- fileデータが入力されていたら表示する -->
						<img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>" width="300px">
					<?php endif ?>
					<dd>
					</dd>
				</dl>
				<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
			</form>
		</div>

	</div>
</body>

</html>