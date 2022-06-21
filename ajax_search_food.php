<?php
// echo '<pre>';
// var_dump($_GET);
// echo '</pre>';
// exit();
session_start();
include("functions.php");
$user_id = $_SESSION['user_id'];
$keyword = $_GET["searchword"];
// echo $search_word;

// db接続
$pdo = connect_to_db();
// sqlの作成
$sql = 'SELECT * FROM (SELECT * FROM food WHERE user_id=:user_id)AS mytable LEFT OUTER JOIN user ON mytable.user_id=user.user_id LEFT OUTER JOIN item ON mytable.item_id=item.item_id LEFT OUTER JOIN category ON mytable.category_id=category.category_id LEFT OUTER JOIN place ON mytable.place_id=place.place_id WHERE other_name LIKE :keyword OR itemname LIKE :keyword';


$stmt = $pdo->prepare($sql);

$stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
$stmt->bindValue(':user_id', "%{$user_id}%", PDO::PARAM_STR);


// 省略

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// var_dump($result);
// echo '</pre>';
// exit();

echo json_encode($result);
exit();
