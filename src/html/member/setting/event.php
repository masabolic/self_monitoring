<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出来事</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>週間の出来事</h1>
    </div>
    <br>
    <?php 
    require_once('../../common.php');
    if(!empty($_POST)){
        $post = sanitize($_POST);
    }

    $weekday_list = array("0" => "sunday", "1" => "monday", "2" => "tuesday", "3" => "wednesday", "4" => "thursday", "5" => "friday", "6" => "saturday");
    
    if(isset($post)) {

        foreach($weekday_list as $w => $y) {
            for($i=1; $i<=3; $i++){
                // 曜日の英語名の後に、1~3の番号を付けてpostで送られてくるのを受け取る。
                $week = $y . $i;

                $dbh = dbconnect();
    
                $sql2 = "SELECT id FROM event WHERE weekday = ? AND number = ?";
                $data2 = [];
                $data2[] = $w;
                $data2[] = $i;
                $stmt2 = $dbh -> prepare($sql2);
                $stmt2 -> execute($data2);
    
                $dbh = null;
    
                $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    
                // 同じweekdayと番号のidがあれば、既存に変わりデータベースに書き込む。
                if(!empty($rec2['id']) && is_numeric($rec2['id'])) {
                    $dbh = dbconnect();
    
                    $sql = 'UPDATE event SET weekday_item=? WHERE weekday = ? AND number = ?';
                    $stmt = $dbh -> prepare($sql);
                    $data = [];
                    $data[] = $post[$week];
                    $data[] = $w;
                    $data[] = $i;
    
                    $stmt -> execute($data);
            
                    $dbh = null;
    
                // 同じweekdayと番号のidが無かったら、新規でデータベースに書き込む。
                } else {
                    if(!empty($post[$week])) {
                        $dbh = dbconnect();

                        $sql3 = 'INSERT INTO event(weekday_item, weekday, number) VALUES(?,?,?)';
                        $stmt3 = $dbh -> prepare($sql3);
                        $data3 = [];
                        $data3[] = $post[$week];
                        $data3[] = $w;
                        $data3[] = $i;

                        $stmt3 -> execute($data3);

                        $dbh = null;
                    }
                }
            }
        }
    }

    ?>

    <form method="post" action="./event.php">
    <button type="button" onclick="location.href='../selected_screen.php'">最初の画面へ</button>
    <button type="button" onclick="history.back()">元に戻る</button><br><br>
    
    
    
    <?php
    // 初期値を入れる為、データベースを呼び出す。
    $weekday = array("0" => "日", "1" => "月", "2" => "火", "3" => "水", "4" => "木", "5" => "金", "6" => "土");
    
    $dbh = dbconnect();

    $sql4 = "SELECT weekday, weekday_item, number FROM event WHERE 1";
    $stmt4 = $dbh -> prepare($sql4);
    $stmt4 -> execute();

    $dbh = null;

    while(true) {
        $rec4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        if($rec4==false) {
            break;
        }
        // 曜日と1~3の番号で2重配列を作る
        $week_list[$rec4['weekday']][$rec4['number']] = $rec4['weekday_item'];
    } ?>
    
    <table border="1">
    <?php
    foreach($weekday as $e => $d) {
        ?> <tr> 
            <th><?php print $d ?></th>
            <?php
            for($i=1; $i<=3; $i++){
                // 曜日の英語名の後に、1~3の番号を付けてpostで送る。
                $week = $weekday_list[$e] . $i;
            ?><td><input type="text" name="<?= $week ?>" <?php if(isset($week_list[$e][$i])) { ?> value="<?= $week_list[$e][$i] ?>" <?php } ?>></td>
            <?php } ?>
        </tr>
    <?php } ?>
    </table>
    <br><br>
        <input type="submit" value="変更">
    </form>
</div>

</body>
</html>