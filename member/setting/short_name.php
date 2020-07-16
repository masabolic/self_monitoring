<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>略称変更</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>略称変更（５文字まで）</h1>
    </div>
    <br>
    <form method="post" action="../selected_screen.php">
    <br>
    <table border="1">
        <tr>
            <th colspan="2">青信号</th>
        </tr>
        <tr>
            <th>TV(漫画)が楽しめている</th>
            <td><input type="text" name="TV_short"></td>
        </tr>
        <tr>
            <th>本が３０分読める</th>
            <td><input type="text" name="book_short"></td>
        </tr>
        <tr>
            <th colspan="2">黄信号</th>
        </tr>
        <tr>
            <th>眠い。欠伸する。</th>
            <td><input type="text" name="sleep_short"></td>
        </tr>
        <tr>
            <th>やる気がない</th>
            <td><input type="text" name="motivation_short"></td>
        </tr>
        <tr>
            <th>金遣いがルーズになる</th>
            <td><input type="text" name="money_short"></td>
        </tr>
        <tr>
            <th>食欲が異常に湧く</th>
            <td><input type="text" name="food_short"></td>
        </tr>
        <tr>
            <th>ニヤニヤが止まらない</th>
            <td><input type="text" name="smile_short"></td>
        </tr>
        <tr>
            <th>イライラ・モヤモヤ</th>
            <td><input type="text" name="frustration_short"></td>
        </tr>
        <tr>
            <th>幻聴、首のそわそわ</th>
            <td><input type="text" name="auditory_hallucination_short"></td>
        </tr>
    </table>

</div>

</body>
</html>