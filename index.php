<?php
session_start();
require('dbconnect.php');

// ログインされていて、ログインしてから1時間後以内であればページを表示することができる。
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');     // 登録しているメンバーの情報を取得するSQLを準備
  $members->execute(array($_SESSION['id']));     // 保存しておいたIDをセットして、SQLを実行
  $member = $members->fetch();     // 取得した内容を$memberに保存

} else {
  header('Location: login.php');     // ログイン画面へ戻る
  exit();
}


// formが送信された時に行われる処理
if (!empty($_POST)) {     // postのあるformが空出なければ実行

  // 入力項目が空でなければDBに挿入する。
  if ($_POST['message'] !== "") {
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');     // DBへデータを挿入するためのSQL文を準備しておく。
    $message->execute(array(     // 実際にSQL文を実行、？で仮置きされていたパラメータは以下に指定する
      $member['id'],     // 入力した人物のid情報
      $_POST['message'],     // 実際に入力したテキストメッセージ
      $_POST['reply_post_id']     // 返信したいメッセージのid
    ));
    header('Location: index.php');     // メッセージ投稿画面へ戻る
    exit();
  }
}

// メンバーの名前、画像と投稿メッセージの全ての情報を取得して、メンバーのidとメッセージ投稿者のidが一致するものを連結させて、投稿された時間順で並べて格納。
$posts = $db->query('SELECT m.name, m.picture, p.*FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

// 「Re」がクリックされた時に返信できるような処理を行う。
if (isset($_REQUEST['res'])) {
  $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $message = '@' . $table['name'] . ' ' . $table['message'];     // formの入力蘭に初期値をセット
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ひとこと掲示板</title>

  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>ひとこと掲示板</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="logout.php">ログアウト</a></div>

      <!-- ここから入力フォーム -->
      <form action="" method="post">
        <dl>
          <dt><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
          <dd>
            <textarea name="message" cols="50" rows="5"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
            <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>" />
          </dd>
        </dl>
        <div>
          <p>
            <input type="submit" value="投稿する" />
          </p>
        </div>
      </form>

      <!-- 投稿した内容を(繰り返し文で表示) -->
      <?php foreach ($posts as $post) : ?>
        <div class="msg">
          <img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" /> <!-- 投稿者の画像を表示 -->
          <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?><span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）</span>[<a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">Re</a>]</p> <!-- 投稿内容と名前を表示、Reにリンクを示す -->
          <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'])); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a> <!-- 投稿日時を表示(メッセージ専用ページへとぶ) -->

            <!-- 返信メッセージにのみ表示 -->
            <?php if ($post['reply_message_id']) : ?>
              <!-- 返信元のリンクが表示される -->
              <a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'])); ?>">返信元のメッセージ</a>
            <?php endif; ?>

            <!-- 自分の投稿にのみ削除ボタンを実装 -->
            <?php if ($_SESSION['id']  === $post['member_id']) : ?>
              [<a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>" style="color: #F33;">削除</a>]
            <?php endif; ?>

          </p>
        </div>
      <?php endforeach; ?>

      <ul class="paging">
        <li><a href="index.php?page=">前のページへ</a></li>
        <li><a href="index.php?page=">次のページへ</a></li>
      </ul>
    </div>
  </div>
</body>

</html>