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
    if(!empty($_POST)){
        require_once('../../common.php');
        $post = sanitize($_POST);
    }

    if(isset($post)) {
        $yellow_act = $post['yellow_act'];
        $orenge_act = $post['orenge_act'];
        $red_act = $post['red_act'];
        $black_act = $post['black_act'];
        $not_activity = $post['not_activity'];

        $act = array("2" => $yellow_act, "3" => $orenge_act, "4" => $red_act, "5" => $black_act);

        for($i=2; $i<=5; $i++){
            $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user2 = 'root';
            $password2 = '';
            $dbh2 = new PDO($dsn2, $user2, $password2);
            $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql2 = "SELECT id FROM activity WHERE color = ?";
            $data2 = [];
            $data2[] = $i
            $stmt2 = $dbh2 -> prepare($sql2);
            $stmt2 -> execute($data2);

            $dbh2 = null;

            $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            if(is_numeric($rec2['id'])) {
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = 'UPDATE activity SET activity=?, do_not_need=? WHERE color=?';
                $stmt = $dbh -> prepare($sql);
                $data = [];
                $data[] = $act[$i];
                $data[] = $not_activity;
                $data[] = $i;

                $stmt -> execute($data);
        
                $dbh = null;

            } else {
                $dsn3 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user3 = 'root';
                $password3 = '';
                $dbh3 = new PDO($dsn3, $user3, $password3);
                $dbh3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

      

    $dsn4 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
    $user4 = 'root';
    $password4 = '';
    $dbh4 = new PDO($dsn4, $user4, $password4);
    $dbh4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql4 = "SELECT color, activity, do_not_need FROM activity WHERE 1";
    $data4 = [];
    $data4[] = $i
    $stmt4 = $dbh4 -> prepare($sql4);
    $stmt4 -> execute($data4);

    $dbh4 = null;

    while(true) {
        $rec4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        if($rec4==false){
            break;
        }
    
        ?>

        <form method="post" action="../selected_screen.php">
            <input type="checkbox" name="not_activity" id="not_activity" value="1" <?php if($rec4['do_not_need'] == 1) { ?> checked="checked" <?php } ?> >
            <label for="not_activity">行動指針が出てこないようにします（非表示）</label><br><br>

            <p>黄　　　<input type="text" name="yellow_act" <?php isset($post) { ?> value="<?= $yellow_act ?>" <?php }elseif($rec4['color'] == 2) { ?> value="<?= $rec4['activity'] ?>" <?php } ?> > </p><br><br>
            <p>橙　　　<input type="text" name="orenge_act" <?php isset($post) { ?> value="<?= $orenge_act ?>" <?php }elseif($rec4['color'] == 3) { ?> value="<?= $rec4['activity'] ?>" <?php } ?> > </p><br><br>
            <p>赤　　　<input type="text" name="red_act" <?php isset($post) { ?> value="<?= $red_act ?>" <?php }elseif($rec4['color'] == 4) { ?> value="<?= $rec4['activity'] ?>" <?php } ?> ></p><br><br>
            <p>黒　　　<input type="text" name="black_act" <?php isset($post) { ?> value="<?= $black_act ?>" <?php }elseif($rec4['color'] == 5) { ?> value="<?= $rec4['activity'] ?>" <?php } ?> ></p><br><br>

            <input type="submit" value="確定">
        </form>
    }

</div>

</body>
</html>