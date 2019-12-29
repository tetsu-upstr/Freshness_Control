<?php

try {
  $pdo = new PDO(
    'mysql:dbname=test_freshness;host=localhost;charset=utf8mb4',
    'root',
    '');

  // PDOのエラーレポートを表示
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $items = $pdo->query('SELECT * FROM items ORDER BY date');


} catch(PDOException $e) {
  echo 'DB接続エラー: ' . $e->getMessage();
  var_dump($e);
}
?>