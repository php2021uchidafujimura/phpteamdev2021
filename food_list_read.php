<?php
session_start();
include("functions.php");
check_session_id();

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'];
$login_user = $_SESSION['name'];

// 管理者の場合、ユーザー管理画面へのリンクを表示する うまく機能しない
if ($is_admin === '1') {
  $settings = "<a href='user_list.php' class='page-link'>ユーザー管理</a>";
}


$pdo = connect_to_db();

// $sql = 'SELECT * FROM todo_table LEFT OUTER JOIN (SELECT todo_id, COUNT(id) AS like_count FROM like_table GROUP BY todo_id) AS result_table ON todo_table.id = result_table.todo_id';
$sql = 'SELECT * FROM `food` LEFT OUTER JOIN user ON food.user_id=user.user_id LEFT OUTER JOIN item ON food.item_id=item.item_id LEFT OUTER JOIN category ON item.category_id=category.category_id LEFT OUTER JOIN place ON food.place_id=place.place_id WHERE food.user_id= :user_id AND togive=0 ORDER BY deadline ASC ';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);


try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}


$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo "<pre>";
// var_dump($result);
// echo "</pre>";
// exit();
$output = "";
foreach ($result as $record) {
  $output .= "
    <tr>
      <td></td>
      <td data-label='食材'>{$record["itemname"]}</td>
      <td data-label='分類'>{$record["option_category"]}</td>
      <td data-label='数量'>{$record["count"]}</td>
      <td data-label='期限'>{$record["deadline"]}</td>
      <td data-label='場所'>{$record["place_name"]}</td>
      <td data-label='修正'><a href='food_edit.php?id={$record["id"]}'>修正する</a></td>
      <td data-label='削除'><a href='food_delete.php?id={$record["id"]}'>削除する</a></td>
      <td data-label='譲渡'><a href='togive_update.php?id={$record["id"]}'>ゆずる</a></td>
    </tr>
  ";
}
// <td><img src='{$record["image"]}' height='150px'></td>
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>食品一覧</title>
  <link rel="stylesheet" href="food_list.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
  <!-- top icon img -->
  <div class="title-container">
    <h1>CHIKUZO</h1>
    <div>
      <a href="user_logout.php" class="page-link-logout"><i class="fas fa-sign-out-alt"></i>ログアウト</a>
    </div>
  </div>
  <p class="login-user"><?= $login_user ?></p>
  <div><?= $settings ?></div>
  <!-- pege top bar -->
  <div class="top-container">
    <div>
      <a href="food_input.php" class="page-link"><i class="fas fa-pencil-alt"></i>入力</a>
    </div>
    <div>
      <a href="togive_list_read.php" class="page-link"><i class="fas fa-gift"></i>もらう</a>
    </div>
    <div>
      <a href="sharebox_list_read.php" class="page-link"><i class="fas fa-people-arrows"></i>公開</a>
    </div>
  </div>
  <h3>マイページ</h3>
  <div>
    <table class="food-table">
      <thead>
        <tr class="food-thead">
          <th></th>
          <th>食材</th>
          <th>分類</th>
          <th>数量</th>
          <th>消費期限</th>
          <th>保存場所</th>
          <th>修正</th>
          <th>削除</th>
          <th>譲渡</th>
        </tr>
      </thead>
      <tbody>
        <?= $output ?>
      </tbody>
  </div>
  </table>

</body>

</html>