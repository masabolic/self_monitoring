<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規項目完了画面</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>新規項目完了画面</h1>
    </div>
    <br>

    <?php
    require_once('../../../common.php');
    // サニタイジング
    if(!empty($_POST)){
        $post = sanitize($_POST);
    }


    if(isset($post)) {
        $ok_flag = true;
        if(!isset($post['signal'])) {
            print "✓　恐れ⼊りますが、体調信号を選んで下さい。<br>";
            $ok_flag = false;
        }

        if(mb_strlen($post['short_name'], 'UTF-8') > 5){
            print "✓　恐れ⼊りますが、略称名は５文字以内でお願いします。<br>";
            $ok_flag = false;
        }

        if(mb_strlen($post['item'], 'UTF-8') > 20){
            print "✓　恐れ⼊りますが、項目名は20文字以内でお願いします。<br>";
            $ok_flag = false;
        }

        if(empty($post['item'])){
            print "✓　恐れ⼊りますが、項目名が空です。<br>";
            $ok_flag = false;
        }

        if($ok_flag == true) {
            print $post['item'];
            print "<br>";
            print $post['short_name'];
            print "<br>";
            if($post['signal'] == 0){
                print "青";
            }elseif($post['signal'] == 2){
                print "黄";
            }elseif($post['signal'] == 6){
                print "追加黄";
            }elseif($post['signal'] == 7){
                print "追加橙";
            }elseif($post['signal'] == 8){
                print "追加赤";
            }
            print "<br>";
            print "を登録しました。";

            // 信号を新規追加する
            $dbh = dbconnect();
            
            $sql = 'INSERT INTO physical_condition_items(item, short_name, color) VALUES(?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $data = [];
            $data[] = $post['item'];
            $data[] = $post['short_name'];
            $data[] = $post['signal'];

            $stmt -> execute($data);
        
            $dbh = null;
        }
    }
    ?>

    <br>
    <button type="button" onclick="history.back()">元に戻る</button><br><br>


</div>

</body>
</html>