<?php
// サーバー側にデータを一時的に保存するための関数（$_SESSION['セッション名']＝保存したいもの　でサーバー側に保存できる。）
session_start();

if (!empty($_POST)) {     // 入力内容が空でない時にエラーチェックを実行する
	if ($_POST['name'] === '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] === '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] === '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];

	if (!empty($fileName)) {     // fileデータが存在する時に(fileName)内にデータが存在する時にファイル検査を実行
		$ext = substr($fileName, -3);     // ファイル名の後ろ3文字（拡張子）を切り取って変数に代入する
		if ($ext != 'jpg' && $ext != 'gif'  && $ext != 'png') {
			$error['image'] = 'type';
		}
	}

	if (empty($error)) {
		// echo '<script>';
		// echo 'console.log(' . json_encode('通過しました') . ')';
		// echo '</script>';
		$image = date('YmdHis') . $_FILES['image']['name'];     // 「日付+ファイル名.png」のような形でファイルを保存する。
		// アップロードされたファイルを専用のディレクトリに保存するための関数
		// move_uploaded_file($_FILES[‘name属性の値’][‘tmp_name’],　'移動先のパスを指定');
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);

		$_SESSION['join'] = $_POST;     // 入力内容を"$_SESSION['join']"に一時保存する
		$_SESSION['join']['image'] = $image;     // fileデータを"$_SESSION['join']['image']"に一時保存する
		header('Location: check.php');     // check.phpにジャンプする
		exit();        // 終了する
	}
}

// 書き直しの場合かつ、$_SESSION['join']が格納されている場合に実行
if ($_REQUEST['action'] = 'rewrite' && isset($_SESSION['join'])) {
	$_POST = $_SESSION['join'];     // $_POSTに入力データを格納
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
			<p>次のフォームに必要事項をご記入ください。</p>
			<form action="" method="post" enctype="multipart/form-data">
				<!-- type="file"を記載する場合はformタグにenctype="multipart/form-data"を追記する。 -->
				<dl>
					<dt>ニックネーム<span class="required">必須</span></dt>
					<dd>
						<input type="text" name="name" size="35" maxlength="255" value='<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>' />
						<?php if ($error['name'] === 'blank') : ?>
							<p class="error">※ ニックネームを入力してください。</p>
						<?php endif ?>
					</dd>
					<dt>メールアドレス<span class="required">必須</span></dt>
					<dd>
						<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" />
						<?php if ($error['email'] === 'blank') : ?>
							<p class="error">※ メールアドレスを入力してください。</p>
						<?php endif ?>
					<dt>パスワード<span class="required">必須</span></dt>
					<dd>
						<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
						<?php if ($error['password'] === 'length') : ?>
							<p class="error">※ パスワードは4文字以上で入力してください。</p>
						<?php endif ?>
						<?php if ($error['password'] === 'blank') : ?>
							<p class="error">※ パスワードを入力してください。</p>
						<?php endif ?>
					</dd>
					<dt>写真など</dt>
					<dd>
						<input type="file" name="image" size="35" value="test" /> <!-- type="file"とすることでアップロードファイルを指定することができる -->
						<?php if ($error['image'] === 'type') : ?>
							<p class="error">※ 写真などは拡張子が「.gif」「.jpg」「.png」のものを選択してください。</p>
						<?php endif ?>

						<!-- fileデータは上の項目でエラーがあった場合は一時保存できない -->
						<?php if (!empty($error)) : ?>
							<p class="error">※ 恐れ入りますが、もう一度画像を指定してください。</p>
						<?php endif ?>

					</dd>
				</dl>
				<div><input type="submit" value="入力内容を確認する" /></div>
			</form>
		</div>
</body>

</html>