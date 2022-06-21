<?php
session_start();
include("functions.php");
check_session_id();

$user_id = $_SESSION['user_id'];
$login_user = $_SESSION['name'];

$pdo = connect_to_db();

$sql = 'SELECT * FROM `food` LEFT OUTER JOIN user ON food.user_id=user.user_id LEFT OUTER JOIN item ON food.item_id=item.item_id LEFT OUTER JOIN category ON item.category_id=category.category_id LEFT OUTER JOIN place ON food.place_id=place.place_id WHERE  togive=1 ORDER BY deadline ASC';
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
      <td></td>
      <td data-label='持主'>{$record["name"]}</td>
      <td data-label='食材'>{$record["itemname"]}</td>
      <td data-label='分類'>{$record["option_category"]}</td>
      <td data-label='数量'>{$record["count"]}</td>
      <td data-label='期限'>{$record["deadline"]}</td>
      <td data-label='場所'>{$record["place_name"]}</td>
      <td data-label='メモ'>{$record["memo"]}</td>
      <td data-label='画像'><img src='{$record["image"]}'style='width:50%;'></td>
      <td data-label='取得'><a href='toget_item_update.php?id={$record["id"]}'>もらう</a></td>
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
  <title>もらえる食品一覧</title>
  <link rel="stylesheet" href="food_list.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
  <h3>もらってくれる人を探しています</h3>
  <p class="login-user"><?= $login_user ?></p>
  <!-- <a href="food_input.php" class="page-link"><i class="fas fa-pencil-alt"></i>入力画面</a> -->
  <a href="food_list_read.php" class="page-link"><i class="fas fa-book-open"></i>マイページ</a>

  <table class="food-table">
    <thead>
      <tr class="food-thead">
        <th></th>
        <th>持主</th>
        <th>食材</th>
        <th>分類</th>
        <th>数量</th>
        <th>消費期限</th>
        <th>保存場所</th>
        <th>メモ</th>
        <th>画像</th>
        <th>もらう</th>
      </tr>
    </thead>
    <tbody>
      <?= $output ?>
    </tbody>
  </table>

</body>

</html>