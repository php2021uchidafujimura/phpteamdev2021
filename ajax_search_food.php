<?php
// echo '<pre>';
// var_dump($_GET);
// echo '</pre>';
// exit();

include("functions.php");

$keyword = $_GET["searchword"];
// echo $search_word;

// db接続
$pdo = connect_to_db();
// sqlの作成
$sql = 'SELECT * FROM `food` LEFT OUTER JOIN user ON food.user_id=user.user_id LEFT OUTER JOIN item ON food.item_id=item.item_id LEFT OUTER JOIN category ON item.category_id=category.category_id LEFT OUTER JOIN place ON food.place_id=place.place_id WHERE other_name LIKE :keyword OR itemname LIKE :keyword';


// 'SELECT * FROM item WHERE other_name LIKE :keyword OR itemname LIKE :keyword';

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);

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
