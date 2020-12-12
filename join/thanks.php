<?php

// ユーザー登録に成功したかどうかを判断する
session_start();
if (!empty($_SESSION['LOGON'])) {
	echo '<script>';
	echo 'console.log(' . json_encode('成功しました') . ')';
	echo '</script>';
} else {
	echo '<script>';
	echo 'console.log(' . json_encode('失敗しました') . ')';
	echo '</script>';
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
			<p>ユーザー登録が完了しました</p>
			<p><a href="../">ログインする</a></p>
		</div>

	</div>
</body>

</html>