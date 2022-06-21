<?php
session_start();
include("functions.php");
check_session_id();

// 食品一覧の「もらう」クリック時の処理
$user_id = $_SESSION['user_id'];
$id = $_GET["id"];

$pdo = connect_to_db();

$sql = 'UPDATE food SET togive=0,user_id=:user_id WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

//sql実行
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}


header("Location:food_list_read.php");
exit();
