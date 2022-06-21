<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <!-- <link rel="stylesheet" href="css/main.css"> -->
  <link rel="stylesheet" href="food_list.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
  <h1>CHIKUZO</h1>
  <form action="user_login_act.php" method="POST">
    <fieldset>
      <legend>ログイン画面</legend>
      <div>
        名前: <input type="text" name="name">
      </div>
      <div>
        パスワード: <input type="text" name="password">
      </div>
      <div>
        <button>ログイン</button>
      </div>
      <a href="register.php" class="page-link-ragister">アカウントを作る</a>
    </fieldset>
  </form>

</body>

</html>