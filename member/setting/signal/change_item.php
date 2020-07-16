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

    if(isset($_POST['blue_signal'])) {
        print $_POST['blue_signal'];
        print "<br>";
        if($_POST['do_not_need'] == 0) {
            print "必要";
        }else{
            print "不必要";
        }
    }

    if(isset($_POST['yellow_signal'])) {
        print $_POST['yellow_signal'];
        print "<br>";
        if($_POST['do_not_need'] == 0) {
            print "必要";
        }else{
            print "不必要";
        }
        print "<br>";
        print $_POST['signal_color'];
    }

    ?>

    <br>
    <button type="button" onclick="history.back()">元に戻る</button><br><br>


</div>

</body>
</html>