<?php
session_start();
include("functions.php");
check_session_id();

$login_user = $_SESSION['name'];

$pdo = connect_to_db();

$sql = 'SELECT * FROM user ORDER BY created_at  DESC';

$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output = "";
foreach ($result as $record) {
  $output .= "
    <tr>
    <td>{$record["name"]}</td>
    <td>{$record["password"]}</td>
      <td><a href='user_edit.php?id={$record["user_id"]}'>修正</a></td>
      <td><a href='user_delete.php?id={$record["user_id"]}'>削除</a></td>
    </tr>
  ";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザーリストページ</title>
  <link rel="stylesheet" href="food_list.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
  <p class="login-user"><?= $login_user ?></p>
  <a href="food_list_read.php" class="page-link"><i class="fas fa-book-open"></i>マイページ</a>
  <p>ユーザーリスト</p>
  <table>
    <thead>
      <tr>
        <th>ユーザー名</th>
        <th>パスワード</th>
      </tr>
    </thead>
    <tbody>
      <?= $output ?>
    </tbody>
  </table>
  <style>
    table {
      margin: auto;
      text-align: center;
    }

    a {
      text-decoration: none;
    }
  </style>
</body>

</html>