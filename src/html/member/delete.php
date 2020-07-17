<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>削除</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
    <link rel="stylesheet" href="../css/monitor_list.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>削除</h1>
    </div>
    <br>
    <form method="post" action="list.php">
    <button type="button" onclick="history.back()">元に戻る</button>
    <br><br>

        <div class="row">
            <div class="col-2"><label for="abbreviation">略称</label></div>
            <div class="col-2">
                <input type="checkbox" name="abbreviation" id="abbreviation" value="0">
                <label for="abbreviation">する</label>
            </div>
            <div class="col-2"></div>
            <div class="col-2">
                <input type="submit" value="変更">
            </div>
        </div>
    </form>
    <br><br>

    <table border="1">
        <tr>
            <th>年月日</th>
            <th>曜日</th>
            <th>睡眠開始時間</th>
            <th>睡眠終了時間</th>
            <th>睡眠合計時間</th>
            <th>朝起きた時の熟睡感</th>
            <th>昼寝した？？</th>
            <th>昼寝開始時間</th>
            <th>昼寝終了時間</th>
            <th>昼寝合計時間</th>
            <th>天気</th>
            <th>TV(漫画)が楽しめている</th>
            <th>本が３０分読める</th>
            <th>眠い。欠伸する。</th>
            <th>やる気がない</th>
            <th>金遣いがルーズになる</th>
            <th>食欲が異常に湧く</th>
            <th>ニヤニヤが止まらない</th>
            <th>イライラ・モヤモヤ</th>
            <th>幻聴、首のそわそわ</th>
            <th>合計</th>
            <th width="100px">出来事1</th>
            <th width="100px">出来事2</th>
            <th width="100px">出来事3</th>
            <th width="150px">気づいたこと</th>
        </tr>
        <tr>
                <th><?php  print $_GET["date"]; ?></th>
        </tr>
    </table>

        <form method="post" action="deleting_confirmation.php">
            <input type="hidden" name="date" value="<?= $_GET["date"]; ?>">
            <input type="submit" value="削除確認">
        </form>












</div>

</body>
</html>