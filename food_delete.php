<?php
session_start();
include("functions.php");
check_session_id();

// 食品一覧の「削除する」クリック時の処理

$id = $_GET['id'];

$pdo = connect_to_db();

$sql = 'DELETE FROM food WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:food_list_read.php");
exit();
