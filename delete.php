<?php
session_start();
require('dbconnect.php');

// セッションにidが入っていれば実行
if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id']; // 削除したいメッセのidを格納

    $messages = $db->prepare('SELECT * FROM posts WHERE id=?'); // 削除したいメッセージのデータを取得するためのSQL文を準備する
    $messages->execute(array($id)); // SQLを実行する

    $message = $messages->fetch();     // 削除したい投稿データを取得


    // メッセージの投稿者と今の使用者が同位置であれば、削除実行
    if ($message['member_id'] === $_SESSION['id']) {
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
    }
}
header('Location: index.php');
exit();
