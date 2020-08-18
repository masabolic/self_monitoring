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
    <?php 
    // サニタイジング
    if(!empty($_POST)){
        require_once('../../common.php');
        $post = sanitize($_POST);
    }

    if(isset($post)) {
        $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user2 = 'root';
        $password2 = '';
        $dbh2 = new PDO($dsn2, $user2, $password2);
        $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql2 = "SELECT id, short_name FROM physical_condition_items WHERE 1";
        $stmt2 = $dbh2 -> prepare($sql2);
        $stmt2 -> execute();

        $dbh2 = null;
        // 文字数制限をオバーしている個数をカウントするための初期値
        $over = 0;

        while(true) {
            $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            if($rec2==false){
                break;
            }

            // エラーをフラグで確認する
            $ok_flag = false;

            if(isset($rec2["id"])) {
                $item_id = 'signal' . $rec2['id'];
                $ok_flag = true;
                if(mb_strlen($post[$item_id], 'UTF-8') > 5){
                    // 文字数制限をオバーしている個数をカウントする
                    $over++;
                    $ok_flag = false;
                }
                
                if($ok_flag == true && $post[$rec2["id"]] == $rec2["id"]) {
                    $dsn3 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user3 = 'root';
                    $password3 = '';
                    $dbh3 = new PDO($dsn3, $user3, $password3);
                    $dbh3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql3 = 'UPDATE physical_condition_items SET short_name=? WHERE id=?';
                    $stmt3 = $dbh3 -> prepare($sql3);
                    $data3 = [];
                    $data3[] = $post[$item_id];
                    $data3[] = $rec2["id"];

                    $stmt3 -> execute($data3);
            
                    $dbh3 = null;
                }
            }
        }
        if($over > 0){
            print "✓　恐れ⼊りますが、". $over . "箇所が5文字以上です。５文字以内でお願いします。<br>";
        }elseif($ok_flag == true) {
            print "変更しました。";
        }
    }
    ?>

     <form method="post" action="./short_name.php">
    <br>
    <table border="1">
        <tr>
            <th colspan="2">青信号</th>
        </tr>
        <?php
            // 青信号の項目をthに書き出す
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE color = ?';
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
                if($rec['display_unnecessary'] == 1){
                    continue;
                }

                $item_id = 'signal' . $rec['id'];
                ?>
                <input type="hidden" name="<?= $rec['id'] ?>" value="<?= $rec['id'] ?>" >
                <tr>
                    <th> <?php print $rec['item'] ?> </th>
            <td><input type="text" name="<?= $item_id ?>" <?php if(isset($post[$item_id])) { ?> value="<?= $post[$item_id] ?>" <?php }else{ ?> value="<?php print $rec['short_name'] ?>" <?php } ?> ></td>
                </tr>
            <?php } ?>

            <tr>
                <th colspan="2">黄信号</th>
            </tr>
            <?php
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE color = ?';
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
                if($rec['display_unnecessary'] == 1){
                    continue;
                }

                $item_id = 'signal' . $rec['id'];
                ?>
                <input type="hidden" name="<?= $rec['id'] ?>" value="<?= $rec['id'] ?>" >
                <tr>
                    <th> <?php print $rec['item'] ?> </th>
                    <td><input type="text" name="<?= $item_id ?>" <?php if(isset($post[$item_id])) { ?> value="<?= $post[$item_id] ?>" <?php }else{ ?> value="<?php print $rec['short_name'] ?>" <?php } ?> ></td>
                </tr>
            <?php } ?>

            <tr>
                <th colspan="2">追加黄</th>
            </tr>
            <?php
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE color =? ';
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
                if($rec['display_unnecessary'] == 1){
                    continue;
                }
 
                $item_id = 'signal' . $rec['id'];
                ?>
                <input type="hidden" name="<?= $rec['id'] ?>" value="<?= $rec['id'] ?>" >
                <tr>
                    <th> <?php print $rec['item'] ?> </th>
                    <td><input type="text" name="<?= $item_id ?>" <?php if(isset($post[$item_id])) { ?> value="<?= $post[$item_id] ?>" <?php }else{ ?> value="<?php print $rec['short_name'] ?>" <?php } ?> ></td>
                </tr>
            <?php } ?>

            <tr>
                <th colspan="2">追加橙</th>
            </tr>
            <?php
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE color = ? ';
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
                if($rec['display_unnecessary'] == 1){
                    continue;
                }

                $item_id = 'signal' . $rec['id'];
                ?>
                <input type="hidden" name="<?= $rec['id'] ?>" value="<?= $rec['id'] ?>" >
                <tr>
                    <th> <?php print $rec['item'] ?> </th>
                    <td><input type="text" name="<?= $item_id ?>" <?php if(isset($post[$item_id])) { ?> value="<?= $post[$item_id] ?>" <?php }else{ ?> value="<?php print $rec['short_name'] ?>" <?php } ?> ></td>
                </tr>
            <?php } ?>

            <tr>
                <th colspan="2">追加赤</th>
            </tr>
            <?php
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE color = ?';
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
                if($rec['display_unnecessary'] == 1){
                    continue;
                }
                $item_id = 'signal' . $rec['id'];
                ?>
                <input type="hidden" name="<?= $rec['id'] ?>" value="<?= $rec['id'] ?>" >
                <tr>
                    <th> <?php print $rec['item'] ?> </th>
                    <td><input type="text" name="<?= $item_id ?>" <?php if(isset($post[$item_id])) { ?> value="<?= $post[$item_id] ?>" <?php }else{ ?> value="<?php print $rec['short_name'] ?>" <?php } ?> ></td>
                </tr>
            <?php } ?>
    </table>
    <br><br>
    <input type="submit" value="確定">
    <button type="button" onclick="location.href='../selected_screen.php'">最初の画面へ</button>
    <br><br>

</div>

</body>
</html>