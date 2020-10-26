<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>行動指針</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>行動指針</h1>
    </div>
    <br>
    <?php
    require_once('../../common.php');
    // サニタイジング
    if(!empty($_POST)){
        
        $post = sanitize($_POST);
    }

    if(isset($post)) {
        $yellow_act = $post['yellow_act'];
        $orenge_act = $post['orenge_act'];
        $red_act = $post['red_act'];
        $black_act = $post['black_act'];
        if(isset($post['not_activity'])) {
            $not_activity = $post['not_activity'];
        }else{
            $not_activity = 0;
        }

        $act = array("2" => $yellow_act, "3" => $orenge_act, "4" => $red_act, "5" => $black_act);

        for($i=2; $i<=5; $i++){
            $dbh = dbconnect();

            $sql2 = "SELECT id FROM activity WHERE color = ?";
            $data2 = [];
            $data2[] = $i;
            $stmt2 = $dbh -> prepare($sql2);
            $stmt2 -> execute($data2);

            $dbh = null;

            $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            // 同じcolorのidがあれば、既存に変わりデータベースに書き込む。
            if(!empty($rec2['id']) && is_numeric($rec2['id'])) {
                $dbh = dbconnect();

                $sql = 'UPDATE activity SET activity=?, do_not_need=? WHERE color=?';
                $stmt = $dbh -> prepare($sql);
                $data = [];
                $data[] = $act[$i];
                $data[] = $not_activity;
                $data[] = $i;

                $stmt -> execute($data);
        
                $dbh = null;

            // 同じcolorのidが無かったら、新規でデータベースに書き込む。
            } else {
                if(!empty($act[$i])){
                    $dbh = dbconnect();

                    $sql3 = 'INSERT INTO activity(activity, color, do_not_need) VALUES(?,?,?)';
                    $stmt3 = $dbh3 -> prepare($sql3);
                    $data3 = [];
                    $data3[] = $act[$i];
                    $data3[] = $i;
                    $data3[] = $not_activity;

                    $stmt3 -> execute($data3);

                    $dbh3 = null;
                }
            }
        }
    }

      
    // 初期値を入れる為にデータベースを呼び出す。
    $dbh = dbconnect();

    $sql4 = "SELECT color, activity, do_not_need FROM activity WHERE 1";
    $stmt4 = $dbh -> prepare($sql4);
    $stmt4 -> execute();

    $dbh = null;
    $color_list = [];
    $do_not_need = 0;

    while(true) {
        $rec4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        if($rec4==false) {
            break;
        }
        $color_list[] = $rec4['activity'];
        $do_not_need = $rec4['do_not_need'];
    } ?>


        <!-- 行動指針の記入場所を作る -->
        <form method="post" action="./activity.php">
            <input type="checkbox" name="not_activity" id="not_activity" value="1" <?php if($do_not_need == 1) { ?> checked="checked" <?php } ?> >
            <label for="not_activity">行動指針が出てこないようにします（非表示）</label><br><br>

                <p>黄　　　<input type="text" name="yellow_act" <?php if(isset($color_list[0])) { ?> value="<?= $color_list[0] ?>" <?php } ?> > </p><br><br>
                <p>橙　　　<input type="text" name="orenge_act" <?php if(isset($color_list[1])) { ?> value="<?= $color_list[1] ?>" <?php } ?> > </p><br><br>
                <p>赤　　　<input type="text" name="red_act" <?php if(isset($color_list[2])) { ?> value="<?= $color_list[2] ?>" <?php } ?> ></p><br><br>
                <p>黒　　　<input type="text" name="black_act" <?php if(isset($color_list[3])) { ?> value="<?= $color_list[3] ?>" <?php } ?> ></p><br><br>

            <input type="submit" value="確定">
            <button type="button" onclick="location.href='../selected_screen.php'">最初の画面へ</button>
        </form>

</div>

</body>
</html>