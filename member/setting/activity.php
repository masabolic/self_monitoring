<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>行動指針</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>行動指針</h1>
    </div>
    <br>
    <form method="post" action="../selected_screen.php">
        <input type="checkbox" name="not_activity" id="not_activity" value="1">
        <label for="not_activity">行動指針が出てこないようにします（非表示）</label><br><br>

        <p>黄　　　<input type="text" name="yellow_act"></p><br><br>
        <p>橙　　　<input type="text" name="orenge_act"></p><br><br>
        <p>赤　　　<input type="text" name="red_act"></p><br><br>
        <p>黒　　　<input type="text" name="black_act"></p><br><br>

        <input type="submit" value="確定">
    </form>

</div>

</body>
</html>