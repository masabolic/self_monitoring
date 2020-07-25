<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>一覧</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
    <link rel="stylesheet" href="../css/monitor_list.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>一覧</h1>
    </div>
    <br>
    <form method="post" action="list.php">
    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    <br><br>

    <?php
    if(!empty($_POST)){
        require_once('../common.php');
        $post = sanitize($_POST);
    }

    $period = 0;
    $abbreviation = 0;

    if(isset($post)) {
        $period = $post['period'];
        if(isset($post['abbreviation'])) {
            $abbreviation = $post['abbreviation'];
        }
    }
    ?>

        <div class="row">
            <div class="col-2">期間</div>
            <div class="col-2">
                <input type="radio" name="period" id="one_week" value="0" <?php if($period == 0) { ?> checked="checked" <?php } ?> >
                <label for="one_week">１週間</label>
            </div>
            <div class="col-2">
                <input type="radio" name="period" id="one_month" value="1" <?php if($period == 1) { ?> checked="checked" <?php } ?>>
                <label for="one_month">１ヶ月</label>
            </div>
        </div>
        <div class="row">
            <div class="col-2"><label for="abbreviation">略称</label></div>
            <div class="col-2">
                <input type="checkbox" name="abbreviation" id="abbreviation" value="1" <?php if($abbreviation == 1) { ?> checked="checked" <?php } ?>>
                <label for="abbreviation">する</label>
            </div>
            <div class="col-2"></div>
            <div class="col-2">
                <input type="submit" value="変更">
            </div>
        </div>
    </form>
    <br><br>

    <table border="1">
        <tr>
            <th>年月日</th>
            <th>曜日</th>
            <th>睡眠開始時間</th>
            <th>睡眠終了時間</th>
            <th>睡眠合計時間</th>
            <th>朝起きた時の熟睡感</th>
            <th>昼寝した？？</th>
            <th>昼寝開始時間</th>
            <th>昼寝終了時間</th>
            <th>昼寝合計時間</th>
            <th>天気</th>
            <?php
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $sql = 'SELECT item, display_unnecessary, color FROM physical_condition_items WHERE 1';
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

                    if($rec['color'] == 0){
                ?> <th> <?php print $rec['item'] ?> </th>
                <?php }
                }

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

                    if($rec['color'] == 2) {
                        ?> <th> <?php print $rec['item'] ?> </th>
                <?php }
                } 
            ?>
            <th>合計</th>
            <th width="100px">出来事1</th>
            <th width="100px">出来事2</th>
            <th width="100px">出来事3</th>
            <th width="300px">気づいたこと</th>
        </tr>
        <tr>
        <?php
            $date = new DateTime('now', new \DateTimeZone('Asia/Tokyo'));
            $daytime = $date -> format("Y-m-d");
            $date -> sub(new DateInterval("P1M"));
            $before_month = $date -> format("Y-m-d");
            
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT id, entries_date, sleep_start_time, sleep_end_time, sound_sleep, nap, nap_start_time, nap_end_time, weather, event1, event2, event3, notice FROM monitoring  WHERE entries_date >= ? AND entries_date < ? ORDER BY entries_date DESC";
            $data = [];
            $data[] = $before_month;
            $data[] = $daytime;
            $stmt = $dbh -> prepare($sql);
            $stmt -> execute($data);

            $dbh = null;

            $week = array("日", "月", "火", "水", "木", "金", "土");
            $sound = array("", "〇", "✕", "△");
            $sound_nap = array("", "〇", "✕", "？");
            $weather_list = array(
                '0' => '', '1' => '晴れ', '2' => '晴れ時々曇り', '3' => '晴れ時々雨', '4' => '晴れのち曇り',
                '5' => '晴れのち雨', '6' => '雨', '7' => '雨時々晴れ', '8' => '雨時々曇り', '9' => '雨のち晴れ', '10' => '雨のち曇り',
                '11' => '曇り', '12' => '曇り時々晴れ', '13' => '曇り時々雨', '14' => '曇りのち晴れ', '15' => '曇りのち雨',
            );
            while(true) {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                if($rec==false){
                    break;
                }
                ?> <th> <?php print $rec['entries_date'] ?> </th>
                <?php
                    $date = new DateTime($rec['entries_date']);
                    $w = (int)$date->format('w');
                ?>
                <th> <?php print $week[$w] ?> </th>
                <?php
                    $date = new DateTime($rec['sleep_start_time']);
                    $sleep_start_time = $date->format('H:i');
                ?>
                <th> <?php print $sleep_start_time; ?> </th>
                <?php
                    $date = new DateTime($rec['sleep_end_time']);
                    $sleep_end_time = $date->format('H:i');
                ?>
                <th> <?php print $sleep_end_time; ?> </th>
                <th> </th>
                <th> <?php print $sound[$rec["sound_sleep"]]; ?> </th>
                <th> <?php print $sound_nap[$rec["nap"]]; ?> </th>
                <?php
                    if($rec['nap_start_time'] == "0000-00-00 00:00:00") {
                        ?> <th> </th> <?php
                    }else{
                        $date = new DateTime($rec['nap_start_time']);
                        $nap_start_time = $date->format('H:i');
                ?>
                <th> <?php print $nap_start_time; ?> </th>
                <?php } 
                    if($rec['nap_end_time'] == "0000-00-00 00:00:00") {
                        ?> <th> </th> <?php
                    }else{
                        $date = new DateTime($rec['nap_end_time']);
                        $nap_start_time = $date->format('H:i');
                ?>
                <th> <?php print $nap_start_time; ?> </th>
                <?php } ?> 
                <th> </th>
                <th> <?php print $weather_list[$rec["weather"]]; ?> </th>
                <?php
                $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user2 = 'root';
                $password2 = '';
                $dbh2 = new PDO($dsn2, $user2, $password2);
                $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $sql2 = 'SELECT condition_id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                $stmt2 = $dbh2 -> prepare($sql2);
                $data2 = [];
                $data2[] = $rec['id'];
                $stmt2 -> execute();

                $dbh2 = null;

                while(true) {
                    $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                    if($rec2==false){
                        break;
                    }
                    if($rec2['display_unnecessary'] == 1){
                        continue;
                    }

                    if($rec2['color'] == 0){
                ?> <th> <?php print $rec2['condition_level'] ?> </th>
                <?php }
                } 
            ?>
                <th> <?php print $rec["event1"]; ?> </th>
                <th> <?php print $rec["event2"]; ?> </th>
                <th> <?php print $rec["event3"]; ?> </th>
                <th> <?php print $rec["notice"]; ?> </th>
                <?php
        ?>
        </tr>
        <?php } ?>
    








    </table>






</div>

</body>
</html>