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
    require_once('../common.php');
    if(!empty($_POST)){
    $post = sanitize($_POST);
    }

    session_start();
    session_regenerate_id(true);
    // この前の画面（記入画面か編集画面）より送られてくる。
    $monitoring_id = $_SESSION['monitoring_id'];

    if(isset($post)) {

        // 必要不要と色をループを回して取り出す。
        $dbh = dbconnect();

        $sql3 = 'SELECT id, display_unnecessary FROM physical_condition_items WHERE color >= ?';
        $data3 = [];
        $data3[] = 6;
        $stmt3 = $dbh -> prepare($sql3);
        $stmt3 -> execute($data3);

        $dbh = null;

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

            // この画面内（ソースでは下記記載）から送られてくるpostがあれば、体長レベルを記録する為に通す
            if(is_numeric($condition_level)) {

                // 追加する信号に元々記載がないか確認するためにidをとる。
                $dbh = dbconnect();
    
                $sql5 = "SELECT id FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?";
                $data5 = [];
                $data5[] = $monitoring_id;
                $data5[] = $condition_id;
                $stmt5 = $dbh -> prepare($sql5);
                $stmt5 -> execute($data5);

                $dbh = null;

                $rec5 = $stmt5->fetch(PDO::FETCH_ASSOC);

                if(is_numeric($rec5['id'])) {
                    // 体調レベルのSQLをアップデートする。
                    $dbh = dbconnect();

                    $sql6 = 'UPDATE condition_levels SET condition_level=? WHERE id = ?';
                    $stmt6 = $dbh -> prepare($sql6);
                    $data6 = [];
                    $data6[] = $condition_level;
                    $data6[] = $rec5['id'];
                    $stmt6 -> execute($data6);

                    $dbh = null;
                } else {
                    // 体調レベルのSQLを記入する。
                    $dbh = dbconnect();

                    $sql4 = 'INSERT INTO condition_levels(monitoring_id, condition_id, condition_level) VALUES(?,?,?)';
                    $stmt4 = $dbh -> prepare($sql4);
                    $data4 = [];
                    $data4[] = $monitoring_id;
                    $data4[] = $condition_id;
                    $data4[] = $condition_level;

                    $stmt4 -> execute($data4);

                    $dbh = null;
                }
            }
        }
        header('Location: selected_screen.php');
        exit();
    }

    $spirit_signal = $_SESSION['spirit_signal'];

    ?>
    <!-- 体調信号を６色で表す -->
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

    <!-- 体調信号に合わせた行動指針を呼び出して、表示 -->
    <?php
    $dbh = dbconnect();

    $sql2 = "SELECT activity, do_not_need, color FROM activity WHERE color = ?";
    $data2 = [];
    $data2[] = $spirit_signal;
    $stmt2 = $dbh -> prepare($sql2);
    $stmt2 -> execute($data2);

    $dbh = null;
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
    <!-- 体調信号が黄以下の場合 -->
    <?php if($spirit_signal < 2) { ?>
        <button onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    <!-- 体調信号が黄以上の場合 -->
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
        // 体調レベルリスト
        $signal_list = array(
            '1' => '1', '2' => '2', '3' => '3', '4' => '4'
        );

        // 追加黄項目だけを取り出す
        $dbh = dbconnect();


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
            // 不必要となったら、記録しない
            if($rec['display_unnecessary'] == 1){
                continue;
            }
            
            // 追加黄項目の２つのidを入れて、元々入ってた場合の初期値を呼び出す。
            $dbh = dbconnect();

            $sql7 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
            $data7 = [];
            $data7[] = $monitoring_id;
            $data7[] = $rec['id'];
            $stmt7 = $dbh -> prepare($sql7);
            $stmt7 -> execute($data7);

            $dbh = null;
            $rec7 = $stmt7->fetch(PDO::FETCH_ASSOC);
            ?>
            <h5>
                <label for="<?= $rec['id']; ?>"><?php print $rec['item']; ?></label>
            </h5>
            <select name="<?= $rec['id']; ?>" id="<?= $rec['id']; ?>">
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

    if($spirit_signal >= 3) {
    ?>

        <h3>
        追加橙
        </h3>
        <br>

        <?php
        // 追加黄項目だけを取り出す
        $dbh = dbconnect();

        $sql9 = 'SELECT id, item, display_unnecessary FROM physical_condition_items WHERE color = ?';
        $stmt9 = $dbh -> prepare($sql9);
        $data9 = [];
        $data9[] = 7;
        $stmt9 -> execute($data9);

        $dbh = null;

        while(true) {
            $rec9 = $stmt9->fetch(PDO::FETCH_ASSOC);
            if($rec9==false){
                break;
            }
            // 不必要となったら、記録しない
            if($rec9['display_unnecessary'] == 1){
                continue;
            }
            
            // 追加黄項目の２つのidを入れて、元々入ってた場合の初期値を呼び出す。
            $dbh = dbconnect();

            $sql10 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
            $data10 = [];
            $data10[] = $monitoring_id;
            $data10[] = $rec9['id'];
            $stmt10 = $dbh -> prepare($sql10);
            $stmt10 -> execute($data10);

            $dbh = null;
            $rec10 = $stmt10->fetch(PDO::FETCH_ASSOC);
            ?>
            <h5>
                <label for="<?= $rec9['id']; ?>"><?php print $rec9['item']; ?></label>
            </h5>
            <select name="<?= $rec9['id']; ?>" id="<?= $rec9['id']; ?>">
                <option value="" <?php if(!isset($rec10['condition_level']) || is_null($rec10['condition_level'])){ ?> selected <?php } ?> >--選択して下さい--</option>
                <option value="0" <?php if(isset($rec10['condition_level'])){ ?> selected <?php } ?>>0</option>
                <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>" <?php if(isset($rec10['condition_level']) && $rec10['condition_level'] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <br>
            <?php
        } ?>

    <input type="button" value="一括(0)">
    <br><br><br><br>

    <?php }

    //　体調信号が赤以上の場合
    if($spirit_signal >= 4) {
    ?>
        
        <h3>
        追加赤
        </h3>
        <br>
        <!-- 追加赤だけを呼び出す -->
        <?php
        $dbh = dbconnect();

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
            // 不必要となったら、記録しない
            if($rec2['display_unnecessary'] == 1){
                continue;
            }

            // 追加赤の初期値がある場合に備えて、呼び出す。
            $dbh = dbconnect();

            $sql8 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
            $data8 = [];
            $data8[] = $monitoring_id;
            $data8[] = $rec2['id'];
            $stmt8 = $dbh -> prepare($sql8);
            $stmt8 -> execute($data8);

            $dbh = null;
            $rec8 = $stmt8->fetch(PDO::FETCH_ASSOC);

            ?>
            <h5>
            <label for="<?= $rec2['id']; ?>"><?php print $rec2['item']; ?></label>
            </h5>
            <select name="<?= $rec2['id']; ?>" id="<?= $rec2['id']; ?>">
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

    // 体調信号が黄以上の場合
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