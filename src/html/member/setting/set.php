<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>設定</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>設定</h1>
    </div>
    <br>
    <form method="post" action="setting_branch.php">
        <input type="submit" name="signal" value="信号項目"><br><br>
        <input type="submit" name="act" value="行動方針"><br><br>
        <input type="submit" name="short_name" value="略称変更"><br><br>
        <input type="submit" name="event" value="週間出来事"><br><br><br><br>
        <button type="button" onclick="location.href='../selected_screen.php'">最初の画面へ</button><br><br>
    </form>
        

</div>

</body>
</html>