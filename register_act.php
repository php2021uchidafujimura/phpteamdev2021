<?php
include('functions.php');

if (
  !isset($_POST['name']) || $_POST['name'] == '' ||
  !isset($_POST['password']) || $_POST['password'] == ''
) {
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

$name = $_POST["name"];
$password = $_POST["password"];

$pdo = connect_to_db();

$sql = 'INSERT INTO user(user_id, name, password, is_admin, is_deleted, created_at, updated_at) VALUES(NULL, :name, :password, 0, 0, now(), now())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:user_login.php");
exit();
