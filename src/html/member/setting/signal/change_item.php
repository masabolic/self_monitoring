<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>完了画面</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>完了画面</h1>
    </div>
    <br>

    <?php
    // 体調を示す信号
    $signal_color = array( 2 => '黄', 6 => '追加黄', 7 => '追加橙', 8 => '追加赤');

    if(!empty($_POST)){
        require_once('../../../common.php');
        $post = sanitize($_POST);
    }

    if(isset($post['blue_signal'])) {
        print $post['blue_signal'];
        print "<br>";
        if(empty($post['do_not_need'])) {
            print "必要";
        }else{
            print "不必要";
        }
    }

    if(isset($post['signal'])) {
        print $post['signal'];
        print "<br>";
        if(empty($post['do_not_need'])) {
            print "必要";
        }else{
            print "不必要";
        }
        print "<br>";
        print $signal_color[$post['signal_color']];
    }

    if(isset($post['blue_signal']) || isset($post['signal'])) {
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'UPDATE physical_condition_items SET display_unnecessary=?, color=? WHERE id=?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        if(empty($post['do_not_need'])) {
            $data[] = 0;
        } else {
            $data[] = 1;
        }
        $data[] = $post['signal_color'];
        $data[] = $post['id'];

        $stmt -> execute($data);

        $dbh = null;
    }

    ?>

    <br>
    <button type="button" onclick="location.href='./existing_item_change.php'">元に戻る</button><br><br>


</div>

</body>
</html>