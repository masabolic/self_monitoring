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

    <button type="button" onclick="location.href='../../selected_screen.php'">最初の画面へ</button>
    <button type="button" onclick="history.back()">元に戻る</button>
    <br><br>
    
    <?php
    // 体調を示す信号
    $signal_color = array( 2 => '黄', 6 => '追加黄', 7 => '追加橙', 8 => '追加赤');
    ?>

    <table border="1">
        <?php
        // 青の項目を書き出す(1項目ごとに変更ボタンをつける)
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'SELECT id, item, display_unnecessary, color FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 0;
        $stmt -> execute($data);

        $dbh = null;

        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            } 
            ?>

            <tr>
                <form method="post" action="change_item.php">
                    <input type="hidden" name="id" value="<?= $rec['id'] ?>" >
                    <input type="hidden" name="signal_color" value="<?= $rec['color'] ?>" >
                    <th>青信号</th>
                    <td><input type="hidden" name="blue_signal" value="<?= $rec['item'] ?>" ><?php print $rec['item'] ?></td>
                    <td><input type="checkbox" name="do_not_need" value="1" <?php if($rec['display_unnecessary'] == 1) { ?> checked="checked" <?php } ?> >不要</td>
                    <td></td>
                    <td><input type="submit" value="変更"></td>
                </form>
            </tr>
        <?php } ?>

        <?php
        // 黄の項目を書き出す(1項目ごとに変更ボタンをつける)
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'SELECT id, item, display_unnecessary, color FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 2;
        $stmt -> execute($data);

        $dbh = null;

        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            } 
            ?>

        <tr>
            <form method="post" action="change_item.php">
                <th>黄信号</th>
                <input type="hidden" name="id" value="<?= $rec['id'] ?>" >
                <td><input type="hidden" name="signal" value="<?= $rec['item'] ?>" ><?php print $rec['item'] ?></td>
                <td><input type="checkbox" name="do_not_need" value="1" <?php if($rec['display_unnecessary'] == 1) { ?> checked="checked" <?php } ?> >不要</td>
                <td>
                    <select name="signal_color" id="signal_color">
                        <option value="" selected>--選択して下さい--</option>
                        <?php foreach ($signal_color as $s => $c) : ?>
                        <option value="<?= $s ?>" <?php if($s == 2) { ?> selected <?php } ?> ><?= $c ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><input type="submit" value="変更"></td>
            </form>
        </tr>
        <?php } 
        // 追加黄の項目を書き出す(1項目ごとに変更ボタンをつける)
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'SELECT id, item, display_unnecessary FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 6;
        $stmt -> execute($data);

        $dbh = null;

        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            } 
            ?>

        <tr>
            <form method="post" action="change_item.php">
            <input type="hidden" name="id" value="<?= $rec['id'] ?>" >
                <th>追加黄</th>
                <td><input type="hidden" name="signal" value="<?= $rec['item'] ?>" ><?php print $rec['item'] ?></td>
                <td><input type="checkbox" name="do_not_need" value="1"　<?php if($rec['display_unnecessary'] == 1) { ?> checked="checked" <?php } ?>　>不要</td>
                <td>
                    <select name="signal_color" id="signal_color">
                        <option value="" selected>--選択して下さい--</option>
                        <?php foreach ($signal_color as $s => $c) : ?>
                        <option value="<?= $s ?>" <?php if($s == 6) { ?> selected <?php } ?> ><?= $c ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><input type="submit" value="変更"></td>
            </form>
        </tr>
        <?php }
        // 追加橙の項目を書き出す(1項目ごとに変更ボタンをつける)
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'SELECT id, item, display_unnecessary FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 7;
        $stmt -> execute($data);

        $dbh = null;

        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            } 
            ?>

        <tr>
            <form method="post" action="change_item.php">
                <input type="hidden" name="id" value="<?= $rec['id'] ?>" >
                <th>追加橙</th>
                <td><input type="hidden" name="signal" value="<?= $rec['item'] ?>" ><?php print $rec['item'] ?></td>
                <td><input type="checkbox" name="do_not_need" value="1"　<?php if($rec['display_unnecessary'] == 1) { ?> checked="checked" <?php } ?>　>不要</td>
                <td>
                    <select name="signal_color" id="signal_color">
                        <option value="" selected>--選択して下さい--</option>
                        <?php foreach ($signal_color as $s => $c) : ?>
                        <option value="<?= $s ?>" <?php if($s == 7) { ?> selected <?php } ?> ><?= $c ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><input type="submit" value="変更"></td>
            </form>
        </tr>
        <?php }
        // 追加赤の項目を書き出す(1項目ごとに変更ボタンをつける)
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'SELECT id, item, display_unnecessary FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 8;
        $stmt -> execute($data);

        $dbh = null;

        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            } 
            ?>

        <tr>
            <form method="post" action="change_item.php">
                <input type="hidden" name="id" value="<?= $rec['id'] ?>" >
                <th>追加赤</th>
                <td><input type="hidden" name="signal" value="<?= $rec['item'] ?>" ><?php print $rec['item'] ?></td>
                <td><input type="checkbox" name="do_not_need" value="1"　<?php if($rec['display_unnecessary'] == 1) { ?> checked="checked" <?php } ?>　>不要</td>
                <td>
                    <select name="signal_color" id="signal_color">
                        <option value="" selected>--選択して下さい--</option>
                        <?php foreach ($signal_color as $s => $c) : ?>
                        <option value="<?= $s ?>" <?php if($s == 8) { ?> selected <?php } ?> ><?= $c ?></option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td><input type="submit" value="変更"></td>
            </form>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>