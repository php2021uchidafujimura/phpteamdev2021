<?php
session_start();
include("functions.php");
check_session_id();

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
// exit();

if (
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_POST['password']) || $_POST['password'] == '' ||
  !isset($_POST['user_id']) || $_POST['user_id'] == ''
) {
  exit('ParamError');
}

$name = $_POST['name'];
$password = $_POST['password'];
$user_id = $_POST['user_id'];

$pdo = connect_to_db();

$sql = "UPDATE user SET name=:name, password=:password, updated_at=now() WHERE user_id=:user_id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:user_list.php");
exit();
