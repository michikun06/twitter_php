
<?php
try {
    $db = new PDO(
        'mysql:dbname=mini_bbs;host=localhost;port=8888;charset=utf8',
        'root',
        'root'
    );
} catch (PDOException $e) {
    print('接続エラー：' . $e->getMessage());
}
?>