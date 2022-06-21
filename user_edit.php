<?php
session_start();

include("functions.php");
check_session_id();

$id = $_GET["id"];

$pdo = connect_to_db();

$sql = 'SELECT * FROM user WHERE user_id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$record = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="css/main.css">
</head>

<body>
  <form action="user_update.php" method="POST">
    <fieldset>
      <legend>ユーザー編集画面</legend><a href="user_list.php">一覧画面に戻る</a>
      <div>
        <div>
          名前: <input type="text" name="name" value="<?= $record["name"] ?>">
        </div>
        <div>
          パスワード: <input type="text" name="password" value="<?= $record["password"] ?>">
        </div>
        <div>
          <input type="hidden" name="user_id" value="<?= $record['user_id'] ?>">
        </div>
        <div>
          <button>変更</button>
        </div>
    </fieldset>
    <a href="user_login.php">ログインへ</a>
  </form>

</body>

</html>