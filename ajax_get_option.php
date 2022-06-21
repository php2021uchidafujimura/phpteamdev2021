<?php
// echo '<pre>';
// var_dump($_GET);
// echo '</pre>';
// exit();

include("functions.php");

$search_word = $_GET["searchword"];
// echo $search_word;

// db接続
$pdo = connect_to_db();
// sqlの作成
$sql = "SELECT * FROM item WHERE $search_word =category_id ";

$stmt = $pdo->prepare($sql);

// 省略

// $stmt->bindValue(':search_word', "%{$search_word}%", PDO::PARAM_STR);

// 省略

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}


$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);
exit();
