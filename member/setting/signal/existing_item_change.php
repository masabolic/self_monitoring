<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>既存項目の変更</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>既存項目の変更</h1>
    </div>
    <br>

    <button type="button" onclick="history.back()">元に戻る</button>
    <br><br>
    
    <?php
    // 天気
    $signal_color = [
        1 => '黄', 2 => '追加黄', 3 => '追加橙', 4 => '追加赤',
    ];
    ?>

<table border="1">
        <tr>
            <form method="post" action="change_item.php">
                <th>青信号</th>
                <td><input type="hidden" name="blue_signal" value="TV(漫画)が楽しめている">TV(漫画)が楽しめている</td>
                <td><input type="checkbox" name="do_not_need" value="1">不要</td>
                <td></td>
                <td><input type="submit" value="変更"></td>
            </form>
        </tr>
        <tr>
            <form method="post" action="change_item.php">
                <th>黄信号</th>
                <td><input type="hidden" name="yellow_signal" value="眠い。欠伸する。">眠い。欠伸する。</td>
                <td><input type="checkbox" name="do_not_need" value="1">不要</td>
                <td>
                    <select name="signal_color" id="signal_color">
                        <option value="" selected>--選択して下さい--</option>
                        <?php foreach ($signal_color as $v) : ?>
                            <option value="<?= $v ?>"><?= $v ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><input type="submit" value="変更"></td>
            </form>
        </tr>







    </table>
</div>

</body>
</html>