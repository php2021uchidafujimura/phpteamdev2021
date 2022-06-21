<?php
session_start();
include("functions.php");
check_session_id();
// echo "<pre>";
// var_dump($_POST);
// var_dump($_FILES);
// echo "</pre>";
// exit();

// ファイルアップロードの処理
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  // 送信が正常に行われたときの処理

  // 一時的な保存領域tmp領域から指定の場所に保存するための必要な情報の取得
  $uploaded_file_name = $_FILES['upfile']['name']; //ファイル名
  $temp_path  = $_FILES['upfile']['tmp_name']; //一時保存されている場所
  $directory_path = 'upload/'; //指定の保存場所

  // ファイル名が重複しないような名前の準備
  $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION); //拡張子の取得
  $unique_name = date('YmdHis') . md5(session_id()) . '.' . $extension; //ユニークな名前の作成
  $save_path = $directory_path . $unique_name; //保存用のパスupload/hogehoge.pngの作成

  // tmp 領域から指定の保存場所へファイルを移動する
  // まずは、tmp 領域にファイルが存在しているかどうか*/
  if (is_uploaded_file($temp_path)) {
    // つぎに、指定のパスでファイルの保存が成功したかどうか 
    if (move_uploaded_file($temp_path, $save_path)) {
      // PHP がファイルを操作するために権限を変更
      chmod($save_path, 0644);
    }
  } else {
    exit('Error:アップロードできませんでした');
  }
} else {
  // exit('Error:画像が送信されていません');
}


if (
  !isset($_POST['category_id']) || $_POST['category_id'] == '' ||
  !isset($_POST['item_id']) || $_POST['item_id'] == '' ||
  !isset($_POST['count']) || $_POST['count'] == '' ||
  !isset($_POST['place_id']) || $_POST['place_id'] == '' ||
  !isset($_POST['deadline']) || $_POST['deadline'] == ''
) {
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

$category_id = $_POST['category_id'];
$item_id = $_POST['item_id'];
$count = $_POST['count'];
$place_id = $_POST['place_id'];
$deadline = $_POST['deadline'];
$memo = $_POST['memo'];
$id = $_POST['id'];
$user_id = $_SESSION['user_id'];

// DB登録の処理を追加しよう！！！

$pdo = connect_to_db();
// 今回はimageのkeyとvalueを追加した。valueは、データ保存場所のパス$save_path
// $sql = 'INSERT INTO food(id, user_id, category_id, item_id, count, place_id, deadline,memo, image, togive, created_at, updated_at) 
// VALUES(NULL, :user_id, :category_id, :item_id, :count, :place_id,:deadline,:memo, :image, 0,now(), now())';
$sql = 'UPDATE food SET id=:id, user_id=:user_id, category_id=:category_id, item_id=:item_id, count=:count, place_id=:place_id, deadline=:deadline,memo=:memo, image=:image, togive=0, updated_at=now() WHERE id=:id';
$stmt = $pdo->prepare($sql);

// $stmt->bindValue(':name_id', $name_id, PDO::PARAM_STR);
$stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);
$stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
$stmt->bindValue(':count', $count, PDO::PARAM_STR);
$stmt->bindValue(':place_id', $place_id, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);

// imageのバインド変数追加
$stmt->bindValue(':image', $save_path, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header("Location:food_input.php");
exit();
