<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規項目追加</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>新規項目追加</h1>
    </div>
    <br>
    <form method="post" action="new_completed.php">
    <button type="button" onclick="history.back()">元に戻る</button>
    <br><br>
    <table>
        <tr>
            <th>項目名</th>
            <td><input type="text" name="item"></td>
        </tr>
        <tr>
            <td colspan="2">※決定後、項目名は変更不可</td>
        </tr>
        <tr>
            <th>略称名</th>
            <td><input type="text" name="short_name"></td>
        </tr>
        <tr>
            <th>体調・精神信号</th>
            <td>
                <input type="radio" name="signal" id="blue" value=0>
                <label for="blue">青</label>
                <input type="radio" name="signal" id="yellow" value=2>
                <label for="yellow">黄</label>
                <input type="radio" name="signal" id="add_yellow" value=6>
                <label for="add_yellow">追加黄</label>
                <input type="radio" name="signal" id="add_orenge" value=7>
                <label for="add_orenge">追加橙</label>
                <input type="radio" name="signal" id="add_red" value=8>
                <label for="add_red">追加赤</label>
            </td>
        </tr>
        <tr>
            <td colspan="2">※青から他の色には変えれません。</td>
        </tr>
        <tr>
            <td colspan="2">※黄以降に選択してから青に変えれません。</td>
        </tr>
    </table>
    <input type="submit" value="追加">

</div>

</body>
</html>