<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>信号項目</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>信号項目</h1>
    </div>
    <br>
    
    <form method="post" action="signal_branch.php">
        <input type="submit" name="item_change" value="既存項目の変更"><br><br>
        <input type="submit" name="new" value="新規項目追加"><br><br><br><br>
        <button type="button" onclick="location.href='../../selected_screen.php'">最初の画面へ</button>
        <button type="button" onclick="history.back()">元に戻る</button><br><br>
    </form>
</div>

</body>
</html>