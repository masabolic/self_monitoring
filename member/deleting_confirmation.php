<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>削除確認</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>削除確認</h1>
    </div>
    <br>
    <form method="post" action="selected_screen.php">

    <?php print $_POST["date"]; ?>を
    <br>
    削除していいですか？？
    <br>

    <input type="submit" value="削除確定">
    <button type="button" onclick="history.back()">キャンセル</button>
    <button onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    </form>

</div>

</body>
</html>