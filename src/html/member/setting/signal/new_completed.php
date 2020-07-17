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

    if(!empty($_POST)){
        require_once('../../../common.php');
        $post = sanitize($_POST);
    }

    if(isset($post)) {
        print $post['item'];
        print "<br>";
        print $post['short_name'];
        print "<br>";
        if($post['signal'] == 0){
            print "青";
        }elseif($post['signal'] == 1){
            print "黄";
        }elseif($post['signal'] == 2){
            print "追加黄";
        }elseif($post['signal'] == 3){
            print "追加橙";
        }else{
            print "追加赤";
        }
        print "<br>";
        print "を登録しました。";
    }

    ?>

    <br>
    <button type="button" onclick="history.back()">元に戻る</button><br><br>


</div>

</body>
</html>