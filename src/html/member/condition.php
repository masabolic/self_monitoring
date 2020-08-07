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
    $monitoring_id = $_SESSION['monitoring_id'];

    if(isset($post)) {

        // 必要不要と色をループを回して取り出す。
        $dsn3 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user3 = 'root';
        $password3 = '';
        $dbh3 = new PDO($dsn3, $user3, $password3);
        $dbh3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql3 = 'SELECT id, display_unnecessary FROM physical_condition_items WHERE color >= ?';
        $data3 = [];
        $data3[] = 6;
        $stmt3 = $dbh3 -> prepare($sql3);
        $stmt3 -> execute($data3);

        $dbh3 = null;

        while(true) {
            $rec3 = $stmt3->fetch(PDO::FETCH_ASSOC);
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

                $dsn5 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user5 = 'root';
                $password5 = '';
                $dbh5 = new PDO($dsn5, $user5, $password5);
                $dbh5->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $sql5 = "SELECT id FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?";
                $data5 = [];
                $data5[] = $monitoring_id;
                $data5[] = $condition_id;
                $stmt5 = $dbh5 -> prepare($sql5);
                $stmt5 -> execute($data5);

                $dbh5 = null;

                $rec5 = $stmt5->fetch(PDO::FETCH_ASSOC);

                if(is_numeric($rec5['id'])) {
                    // 体調レベルのSQLをアップデートする。
                    $dsn6 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user6 = 'root';
                    $password6 = '';
                    $dbh6 = new PDO($dsn6, $user6, $password6);
                    $dbh6->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql6 = 'UPDATE condition_levels SET condition_level=? WHERE id = ?';
                    $stmt6 = $dbh6 -> prepare($sql6);
                    $data6 = [];
                    $data6[] = $condition_level;
                    $data6[] = $rec5['id'];
                    $stmt6 -> execute($data6);

                    $dbh6 = null;
                } else {
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
        }
        header('Location: selected_screen.php');
        exit();
    }

    $spirit_signal = $_SESSION['spirit_signal'];

    ?>
    <br>
    <div class="row">
        <div class="col-2">
            体調・精神信号
        </div>
        <div class="col-2">
            <?php
            $spirit = array("青", "緑", "黄", "橙", "赤", "黒");
            print $spirit[$spirit_signal];
            ?>
        </div>
    </div>
    <br>

    <?php
    $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
    $user2 = 'root';
    $password2 = '';
    $dbh2 = new PDO($dsn2, $user2, $password2);
    $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql2 = "SELECT activity, do_not_need, color FROM activity WHERE color = ?";
    $data2 = [];
    $data2[] = $spirit_signal;
    $stmt2 = $dbh2 -> prepare($sql2);
    $stmt2 -> execute($data2);

    $dbh2 = null;
    $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);

    if(isset($rec2["color"]) && $rec2["do_not_need"] == 0 && $rec2["color"] >= 2) { ?>
        <div class="row">
            <div class="col-2">
                行動指針
            </div>
            <div class="col-2">
                <?php
                print $rec2["activity"];
                ?> 
            </div>
        </div>
    <?php } ?>
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
            '1' => '1', '2' => '2', '3' => '3', '4' => '4'
        );

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
            if($rec['display_unnecessary'] == 1){
                continue;
            }
            
            $dsn7 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user7 = 'root';
            $password7 = '';
            $dbh7 = new PDO($dsn7, $user7, $password7);
            $dbh7->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql7 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
            $data7 = [];
            $data7[] = $monitoring_id;
            $data7[] = $rec['id'];
            $stmt7 = $dbh7 -> prepare($sql7);
            $stmt7 -> execute($data7);

            $dbh7 = null;
            $rec7 = $stmt7->fetch(PDO::FETCH_ASSOC);
            ?>
            <h5>
                <label for="<?= $rec['id']; ?>"><?php print $rec['item']; ?></label>
            </h5>
            <select name="<?= $rec['id']; ?>" id="<?= $rec['id']; ?>">
                <option value="" selected>--選択して下さい--</option>
                <option value="" <?php if(!isset($rec7['condition_level']) || is_null($rec7['condition_level'])){ ?> selected <?php } ?> >--選択して下さい--</option>
                <option value="0" <?php if(isset($rec7['condition_level'])){ ?> selected <?php } ?>>0</option>
                <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>" <?php if(isset($rec7['condition_level']) && $rec7['condition_level'] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <br>
            <?php
        } ?>

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


        $sql = 'SELECT id, item, display_unnecessary FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 8;
        $stmt -> execute($data);

        $dbh = null;
        
        while(true) {
            $rec2 = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec2==false){
                break;
            }
            if($rec2['display_unnecessary'] == 1){
                continue;
            }

            $dsn8 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user8 = 'root';
            $password8 = '';
            $dbh8 = new PDO($dsn8, $user8, $password8);
            $dbh8->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql8 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
            $data8 = [];
            $data8[] = $monitoring_id;
            $data8[] = $rec2['id'];
            $stmt8 = $dbh8 -> prepare($sql8);
            $stmt8 -> execute($data8);

            $dbh8 = null;
            $rec8 = $stmt8->fetch(PDO::FETCH_ASSOC);

            ?>
            <h5>
            <label for="<?= $rec2['id']; ?>"><?php print $rec2['item']; ?></label>
            </h5>
            <select name="<?= $rec2['id']; ?>" id="<?= $rec2['id']; ?>">
                <option value="" selected>--選択して下さい--</option>
                <option value="" <?php if(!isset($rec8['condition_level']) || is_null($rec8['condition_level'])){ ?> selected <?php } ?> >--選択して下さい--</option>
                <option value="0" <?php if(isset($rec8['condition_level'])){ ?> selected <?php } ?>>0</option>
                <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>" <?php if(isset($rec8['condition_level']) && $rec8['condition_level'] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <br>

        <?php
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