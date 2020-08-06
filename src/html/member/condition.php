<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>体調・精神信号</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>体調・精神信号、行動指針、(追加項目)</h1>
    </div>
    <?php
    if(!empty($_POST)){
    require_once('../common.php');
    $post = sanitize($_POST);
    }

    session_start();
    session_regenerate_id(true);

    if(isset($post)) {
        $monitoring_id = $_SESSION['monitoring_id'];

        // 必要不要と色をループを回して取り出す。
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT id, display_unnecessary FROM physical_condition_items WHERE color >= ?';
        $data = [];
        $data[] = 6;
        $stmt = $dbh -> prepare($sql);
        $stmt -> execute($data);

        $dbh = null;

        while(true) {
            $rec3 = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec3==false){
                break;
            }

            //不必要となったら、記録しない
            if($rec3['display_unnecessary'] == 1){
                continue;
            }

            $condition_id = $rec3['id'];
            $condition_level = $post[$condition_id];

            if(is_numeric($condition_level)) {

                // 体調レベルのSQLを記入する。
                $dsn4 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user4 = 'root';
                $password4 = '';
                $dbh4 = new PDO($dsn4, $user4, $password4);
                $dbh4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql4 = 'INSERT INTO condition_levels(monitoring_id, condition_id, condition_level) VALUES(?,?,?)';
                $stmt4 = $dbh4 -> prepare($sql4);
                $data4 = [];
                $data4[] = $monitoring_id;
                $data4[] = $condition_id;
                $data4[] = $condition_level;

                $stmt4 -> execute($data4);

                $dbh4 = null;
            }
        }
        header('Location: selected_screen.php');
        exit();
    }

    $spirit_signal = $_SESSION['spirit_signal'];

    ?>
    <br>
    <h5>
    体調・精神信号
    <?php
    if($spirit_signal == 0) {
        print "青";
    }elseif($spirit_signal == 1){
        print "緑";
    }elseif($spirit_signal == 2){
        print "黄";
    }elseif($spirit_signal == 3){
        print "橙";
    }elseif($spirit_signal == 4){
        print "赤";
    }elseif($spirit_signal == 5){
        print "黒";
    }
    ?>
    </h5>
    <br>

    <h5>
    行動指針
    <?php
    $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
    $user2 = 'root';
    $password2 = '';
    $dbh2 = new PDO($dsn2, $user2, $password2);
    $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql2 = "SELECT activity, do_not_need FROM activity WHERE color = ?";
    $data2 = [];
    $data2[] = $spirit_signal;
    $stmt2 = $dbh2 -> prepare($sql2);
    $stmt2 -> execute($data2);

    $dbh2 = null;

    $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);

    if($rec2["do_not_need"] == 0){
        print $rec2["activity"];
    }
    ?>
    </h5>
    <br>
    <?php if($spirit_signal < 2) { ?>
        <button onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    <?php } elseif($spirit_signal >= 2) { ?>
        <h5>
        追加項目
        </h5>
        <form method="post" action="condition.php">
        <button onclick="location.href='./selected_screen.php'">キャンセル</button>
        <p>※追加項目を記入するのがしんどい時にキャンセル出来ます。</p>
        <br>
        <br>

        <p>0：体調異常なし</p>
        <p>1：変化はあるけど、体調に関わるほどではない</p>
        <p>2：体調にちょっと関わる</p>
        <p>3：体調に関わる</p>
        <p>4：ひどいほど出てる</p>
        <br>
        <br>


        <h3>
        追加黄
        </h3>
        <br>

        <?php
        // 信号リスト
        $signal_list = array(
            '0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4',
        );

        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'SELECT id, item, display_unnecessary, color FROM physical_condition_items WHERE 1';
        $stmt = $dbh -> prepare($sql);
        $stmt -> execute();

        $dbh = null;
        
        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            }
            if($rec['display_unnecessary'] == 1){
                continue;
            }

            if($rec['color'] == 6) {
            ?>
            <h5>
            <label for="<?= $rec['id']; ?>"><?php print $rec['item']; ?></label>
            </h5>
            <select name="<?= $rec['id']; ?>" id="<?= $rec['id']; ?>">
                <option value="" selected>--選択して下さい--</option>
            <?php foreach ($signal_list as $v => $value) : ?>
                <option value="<?= $v ?>"><?= $value ?></option>
            <?php endforeach ?>
            </select>
            <br>
            <br>
            <?php }
        } ?><h5>

        <input type="button" value="一括(0)">
        <br><br><br><br>

    <?php }

    if($spirit_signal == 4) {
    ?>
        
        <h3>
        追加赤
        </h3>
        <br>
        <?php
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql = 'SELECT id, item, display_unnecessary, color FROM physical_condition_items WHERE 1';
        $stmt = $dbh -> prepare($sql);
        $stmt -> execute();

        $dbh = null;
        
        while(true) {
            $rec2 = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec2==false){
                break;
            }
            if($rec2['display_unnecessary'] == 1){
                continue;
            }

            if($rec2['color'] == 8) {
            ?>
            <h5>
            <label for="<?= $rec2['id']; ?>"><?php print $rec2['item']; ?></label>
            </h5>
            <select name="<?= $rec2['id']; ?>" id="<?= $rec2['id']; ?>">
                <option value="" selected>--選択して下さい--</option>
            <?php foreach ($signal_list as $v => $value) : ?>
                <option value="<?= $v ?>"><?= $value ?></option>
            <?php endforeach ?>
            </select>
            <br>
            <br>

            <?php
            }
        }
        ?>
        <input type="button" value="一括(0)">
        <br><br><br><br>
    <?php }

    if($spirit_signal >= 2) { 
    ?>

    <input type="submit" value="確定">
    <br>
    <br>
    </form>
    
    <?php } ?>

</div>

</body>
</html>