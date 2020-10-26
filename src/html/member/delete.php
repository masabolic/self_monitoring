<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>削除</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
    <link rel="stylesheet" href="../css/monitor_list.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>削除</h1>
        <?php
        require_once('../common.php');
        // サニタイジング
        if(!empty($_POST)){
            $post = sanitize($_POST);
        }
        $get = sanitize($_GET);
        $day = $get["date"];
        ?>

    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    <button type="button" onclick="history.back()">元に戻る</button>
    <br><br>

    <table border="1">
        <!-- colorの青と黄のidを配列に入れる -->
        <?php
            $dbh = dbconnect();

            $sql = 'SELECT id, display_unnecessary, color FROM physical_condition_items WHERE color = ? OR color = ? ';
            $stmt = $dbh -> prepare($sql);
            $data = [];
            $data[] = 0;
            $data[] = 2; 
            $stmt -> execute($data);

            $dbh = null;

            $blue_roop = array();
            $yellow_roop = array();

            while(true) {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                if($rec==false){
                    break;
                }
                if($rec['display_unnecessary'] == 1){
                    continue;
                }
                
                if($rec['color'] == 0){
                    $blue_roop[] = $rec['id'];
                    
                }elseif($rec['color'] == 2){
                    $yellow_roop[] = $rec['id'];
                }
            }
            
        ?>

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
                // 青信号の項目をthに書き出す
                $dbh = dbconnect();

                $sql = 'SELECT item, short_name, display_unnecessary, color FROM physical_condition_items WHERE display_unnecessary = ? AND color = ?';
                $stmt = $dbh -> prepare($sql);
                $data = [];
                $data[] = 0;
                $data[] = 0;
                $stmt -> execute($data);

                $dbh = null;

                while(true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($rec==false){
                        break;
                    }
                    ?> <th> <?php print $rec['item'] ?> </th>
                <?php }

                // 黄信号の項目をthに書き出す
                $dbh = dbconnect();

                $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE display_unnecessary = ? AND color = ?';
                $stmt = $dbh -> prepare($sql);
                $data = [];
                $data[] = 0;
                $data[] = 2;
                $stmt -> execute($data);

                $dbh = null;
                
                while(true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($rec==false){
                        break;
                    }
                    ?> <th> <?php print $rec['item'] ?> </th>
                <?php } ?>
            <th>合計</th>
            <th>体調</th>
            <th width="100px">出来事1</th>
            <th width="100px">出来事2</th>
            <th width="100px">出来事3</th>
            <th width="300px">気づいたこと</th>
        </tr>
        <tr>
        <?php
            // 日付を元に表示
            $dbh = dbconnect();

            $sql = "SELECT id, entries_date, weekday, sleep_start_time, sleep_end_time, sleep_sum, sound_sleep, nap, nap_start_time, nap_end_time, nap_sum, weather, spirit_signal, event1, event2, event3, notice FROM monitoring  WHERE entries_date = ? AND 	is_deleted = ?";
            $data = [];
            $data[] = $day;
            $data[] = 0;
            $stmt = $dbh -> prepare($sql);
            $stmt -> execute($data);

            $dbh = null;

            // 曜日、熟睡度、昼寝、天気の配列、体調信号
            $week = array("日", "月", "火", "水", "木", "金", "土");
            $sound = array("", "〇", "✕", "△");
            $sound_nap = array("", "〇", "✕", "？");
            $weather_list = array(
                '0' => '', '1' => '晴れ', '2' => '晴れ時々曇り', '3' => '晴れ時々雨', '4' => '晴れのち曇り',
                '5' => '晴れのち雨', '6' => '雨', '7' => '雨時々晴れ', '8' => '雨時々曇り', '9' => '雨のち晴れ', '10' => '雨のち曇り',
                '11' => '曇り', '12' => '曇り時々晴れ', '13' => '曇り時々雨', '14' => '曇りのち晴れ', '15' => '曇りのち雨',
            );
            $condition_list = array("青", "緑", "黄", "橙", "赤", "黒");

            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            ?> <th> <?php print $day ?> </th>
            <th> <?php print $week[$rec['weekday']] ?> </th>
            <!-- 睡眠開始時間の時間だけ -->
            <?php
                $date = new DateTime($rec['sleep_start_time']);
                $sleep_start_time = $date->format('H:i');
            ?>
            <th> <?php print $sleep_start_time; ?> </th>
            <!-- 睡眠終了時間の時間だけ -->
            <?php
                $date = new DateTime($rec['sleep_end_time']);
                $sleep_end_time = $date->format('H:i');
                $date6 = new DateTime($rec['sleep_sum']);
                $sleep_sum = $date6->format('H:i');
            ?>
            <th> <?php print $sleep_end_time; ?> </th>
            <th> <?php print $sleep_sum; ?> </th>
            <th> <?php print $sound[$rec["sound_sleep"]]; ?> </th>
            <th> <?php print $sound_nap[$rec["nap"]]; ?> </th>
            <!-- 昼寝開始時間(0ばっかの時は記載しない) -->
            <?php
                if($rec['nap_start_time'] == "0000-00-00 00:00:00") {
                    ?> <th> </th> <?php
                }else{
                    $date = new DateTime($rec['nap_start_time']);
                    $nap_start_time = $date->format('H:i');
            ?>
            <th> <?php print $nap_start_time; ?> </th>
            <!-- 昼寝終了時間(0ばっかの時は記載しない) -->
            <?php } 
                if($rec['nap_end_time'] == "0000-00-00 00:00:00") {
                    ?> <th> </th> <?php
                }else{
                    $date = new DateTime($rec['nap_end_time']);
                    $nap_start_time = $date->format('H:i');
            ?>
            <th> <?php print $nap_start_time; ?> </th>
            <?php }
                $date5 = new DateTime($rec['nap_sum']);
                $nap_sum = $date5->format('H:i');
                if($nap_sum == "00:00") { 
                    ?> <th> </th> <?php
                }else{ ?>
                    <th><?php print $nap_sum; ?></th>
                <?php } ?>
            <th> <?php print $weather_list[$rec["weather"]]; ?> </th>
            <?php
            // 青信号のIDをもとに２重ループする
            foreach( $blue_roop as $value ){
                $dbh = dbconnect();

                $sql2 = 'SELECT P.id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                $stmt2 = $dbh -> prepare($sql2);
                $data2 = [];
                $data2[] = $rec['id'];
                $stmt2 -> execute($data2);

                $dbh = null;

                $roop_flag = false;

                while(true) {
                    $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                    if($rec2==false){
                        // $value == $rec2['id']がない場合は、空白が入るようにする。
                        if($roop_flag == false) {
                            ?> <th> </th> <?php   
                        }
                        break;
                    }

                    // 必要とされていない項目は表示しない。
                    if($rec2['display_unnecessary'] == 1){
                        continue;
                    }

                    // 青信号のIDが一致するときに通る。
                    if($value == $rec2['id'] ) {
                        if($rec2['condition_level'] == 5){
                            ?> <th>-</th>
                        <?php }else{
                            ?> <th> <?php print $rec2['condition_level'] ?> </th>
                        <?php }

                        // condition_levelsのデータベースにデータが記載されているか確認
                        $roop_flag = true;
                    }
                } 
            }

            // 黄信号の合計の初期化
            $yellow_total = 0;

            // 黄信号のIDをもとに２重ループする
            foreach( $yellow_roop as $v ){
                $dbh = dbconnect();

                $sql3 = 'SELECT condition_id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                $stmt3 = $dbh -> prepare($sql3);
                $data3 = [];
                $data3[] = $rec['id'];
                $stmt3 -> execute($data3);

                $dbh = null;

                $roop_flag = false;

                while(true) {
                    $rec3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                    if($rec3==false){
                        // $value == $rec2['id']がない場合は、空白が入るようにする。
                        if($roop_flag == false) {
                            ?> <th> </th> <?php   
                        }
                        break;
                    }

                    // 必要とされていない項目は表示しない。
                    if($rec3['display_unnecessary'] == 1){
                        continue;
                    }

                    // 黄信号のIDが一致するときに通る。
                    if($v == $rec3['condition_id'] ) {
                        ?> <th> <?php print $rec3['condition_level'] ?> </th>

                        <!-- condition_levelsのデータベースにデータが記載されているか確認 -->
                        <?php $roop_flag = true;

                        // 黄信号の合計を数える
                        $yellow_total += $rec3['condition_level'];
                    }
                } 
            } ?>
            <th> <?php print $yellow_total; ?> </th>
            <th> <?php print $condition_list[$rec["spirit_signal"]]; ?> </th>
            <th> <?php print $rec["event1"]; ?> </th>
            <th> <?php print $rec["event2"]; ?> </th>
            <th> <?php print $rec["event3"]; ?> </th>
            <th> <?php print $rec["notice"]; ?> </th>
        </tr>
    </table>

        <!-- 削除するidと日付を送る -->
        <form method="post" action="deleting_fixed.php">
            <input type="hidden" name="id" value="<?= $rec['id']; ?>">
            <input type="hidden" name="date" value="<?= $day; ?>">
            <h3>削除していいですか？？</h3>
            <input type="submit" value="削除確定">
        </form>

</div>

</body>
</html>