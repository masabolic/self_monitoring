<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>削除完了</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>削除完了</h1>
    </div>
    <br>

    <?php
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'UPDATE monitoring SET is_deleted=? WHERE id = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 1;
        $data[] = $_POST['id'];
        $stmt -> execute($data);

        $dbh = null;

    print $_POST["date"]; ?>を
    <br>
    削除しました。
    <br>

    <button onclick="location.href='./selected_screen.php'">最初の画面へ</button>

</div>

</body>
</html>