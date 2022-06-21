<?php
session_start();
include("functions.php");
check_session_id();

$id = $_GET['id'];
$user_id = $_SESSION['id'];
$login_user = $_SESSION['name'];

$pdo = connect_to_db();

// $sql = 'SELECT * FROM todo_table LEFT OUTER JOIN (SELECT todo_id, COUNT(id) AS like_count FROM like_table GROUP BY todo_id) AS result_table ON todo_table.id = result_table.todo_id';
$sql = 'SELECT * FROM category';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}


$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";

// テーブルのデータをoptionタグに整形する
// foreach ($age_data as $age_data_val) {

//   $age_data .= "<option value='" . $age_data_val['age_val'];
//   $age_data .= "'>" . $age_data_val['age_data'] . "</option>";
// }

foreach ($result as $record) {
  $output .= "<option value='{$record["category_id"]}'>{$record["option_category"]}</option>";
}


/* select name aのoption     <?php
      $res = $mysqli->query("SELECT id, name FROM test");
      while ($data = $res->fetch_assoc()) {  ?>
        <option value="<?= $data['id'] ?>"><?= $data['name'] ?></option>
      <?php } ?> */
// header("Location:food_list_read.php");
// exit();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>食材編集画面</title>
  <link rel="stylesheet" href="food_list.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
  <h1>食材編集画面</h1>
  <p class="login-user"><?= $login_user ?></p>
  <a href="food_list_read.php" class="page-link"><i class="fas fa-book-open"></i>マイページ</a>
  <form action="edit_create_img_file.php" method="POST" enctype="multipart/form-data">
    <!--  -->
    <!-- ここからtest -->
    <div>
      <label for="category_id">ジャンル : </label>
      <select name="category_id" id="category_id">
        <option selected disabled>未選択</option>
        <?= $output ?>
      </select>
    </div>
    <div>
      <label for="item_id">食品名 : </label>
      <select name="item_id" id="item_id">
      </select>
    </div>
    <div>
      個数 : <input type="number" name="count">
    </div>
    <div>
      <label>保管場所 : </label>
      <select name="place_id">
        <option value="1">自室冷蔵庫</option>
        <option value="2">共有冷蔵庫</option>
      </select>
    </div>
    <div>
      賞味期限 : <input type="date" name="deadline">
    </div>
    <div>
      メモ : <textarea name="memo" id="" cols="30" rows="10"></textarea>
    </div>
    <div><input type="hidden" name="id" value="<?= $id ?>"></div>
    <legend>画像のアップロード</legend>
    <div>
      <input type="file" name="upfile" accept="image/*" capture="camera" />
    </div>
    <input type='submit' value='登録する' />
  </form>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <!-- 連動プルダウンを作るjquery -->
  <script>
    $("#category_id").on('change', function() {
      const aVal = $(this).val();
      const requestUrl = "ajax_get_option.php";

      axios
        .get(`${requestUrl}?searchword=${aVal}`)
        .then(function(response) {
          console.log(response);
          const array = [];
          response.data.forEach(function(x) {
            array.push(`<option value=${x.item_id}>${x.itemname}</option>`);
            // array.push(`<tr><td>${x.deadline}</td><td>${x.todo}</td></tr>`);
          });
          $('#item_id').html(array);
          console.log(array);
        })
        .catch(function(error) {
          // 省略
        })
        .finally(function() {
          // 省略
        });

      // ここからサイトからのコピペ
      /*$.ajax({
        type: "POST",
        url: "ajax_get_option.php",
        data: {
          "aaa": aVal
        },
        dataType: "json"
      }).done(function(data) {
        // map で option タグのオブジェクトを生成しておいて、ループの外で append　する
        var arr = $.map(data, function(val, index) {
          $option = $('', {
            value: index,
            text: val
          });
          return $option;
        });
        $('#bbb').append(arr);
      }).fail(function(XMLHttpRequest, textStatus, error) {
          alert("エラーが発生しました。);
          });
      });*/
    });
  </script>
</body>


</html>