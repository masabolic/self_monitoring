<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>検索結果</h1>
    </div>
    <br>
    
    <?php
    if(!empty($_POST)){
        require_once('../common.php');
        $post = sanitize($_POST);
    }

    $count = 0;
    $abbreviation = 0;

    if(isset($post['count'])) {
        $count = $post['count'];
    }
    if(isset($post['abbreviation'])) {
        $abbreviation = $post['abbreviation'];
    }
    ?>

    <form method="post" action="search_result.php">
    <button type="button" onclick="history.back()">元に戻る</button>
    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面に戻る</button>
    <br><br>

        <div class="row">
            <div class="col-2">件数</div>
            <div class="col-2">
                <input type="radio" name="count" id="one_week" value="0" checked="checked">
                <label for="one_week">10件</label>
            </div>
            <div class="col-2">
                <input type="radio" name="count" id="one_month" value="1">
                <label for="one_month">50件</label>
            </div>
            <div class="col-2">
                <input type="radio" name="count" id="one_month" value="2">
                <label for="one_month">全部</label>
            </div>
        </div>
        <div class="row">
            <div class="col-2"><label for="abbreviation">略称</label></div>
            <div class="col-2">
                <input type="checkbox" name="abbreviation" id="abbreviation" value="0">
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
        <!-- colorの青と黄のidを配列に入れる -->
        <?php
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sql = 'SELECT id, display_unnecessary, color FROM physical_condition_items WHERE 1';
            $stmt = $dbh -> prepare($sql);
            $stmt -> execute();

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
            <?php
                if($abbreviation == 0) { ?>
                    <th>朝起きた時の熟睡感</th>
                <?php }else{ ?>
                    <th>熟睡感</th>
                <?php } ?>
            <th>昼寝した？？</th>
            <th>昼寝開始時間</th>
            <th>昼寝終了時間</th>
            <th>昼寝合計時間</th>
            <th>天気</th>
            <?php
                // 青信号の項目をthに書き出す
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $sql = 'SELECT item, short_name, display_unnecessary, color FROM physical_condition_items WHERE 1';
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
                        if($abbreviation == 0) {
                            ?> <th> <?php print $rec['item'] ?> </th>
                        <?php }else{ 
                            ?> <th> <?php print $rec['short_name'] ?> </th>
                        <?php }
                    }
                }

                // 黄信号の項目をthに書き出す
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE 1';
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
                        if($abbreviation == 0) {
                            ?> <th> <?php print $rec['item'] ?> </th>
                        <?php }else{ 
                            ?> <th> <?php print $rec['short_name'] ?> </th>
                        <?php }
                    }
                } 
            ?>
            <th>合計</th>
            <th>体調</th>
            <th width="100px">出来事1</th>
            <th width="100px">出来事2</th>
            <th width="100px">出来事3</th>
            <th width="300px">気づいたこと</th>
        </tr>
        <tr>
        <?php
            if(!empty($_POST)){
                require_once('../common.php');
                $post = sanitize($_POST);
            }

            $count = 0;
            $abbreviation = 0;

            if(isset($post)) {
                $start_day = $post['start_day'];
                $end_day = $post['end_day'];
                $start_to_sleep = $post['start_to_sleep'];
                $end_to_sleep = $post['end_to_sleep'];
                $sleep_total = $post['sleep_total'];
                $sound_sleep = $post['sound_sleep'];
                $nap_total = $post['nap_total'];
                $blue_signal = $post['blue_signal'];
                $yellow_signal = $post['yellow_signal'];
                $yellow_up_down = $post['yellow_up_down'];
                $condition = $post['condition'];
                $condition_up_down = $post['condition_up_down'];
                $weather = $post['weather'];
                $event = $post['event'];
                $start_day = $post['start_day'];
                $start_day = $post['start_day'];
                $signal_flag = false;
                
                //　検索をする
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "";
                $sql .= "SELECT entries_date, sleep_start_time, sleep_end_time, sound_sleep, nap, nap_start_time, nap_end_time, spirit_signal, weather, event1, event2, event3, notice ";
                // if(!empty($blue_signal)){
                    $sql .= ", M.id, condition_id, condition_level FROM monitoring M JOIN condition_levels C ON M.id = C.monitoring_id";
                // }else{
                //     $sql .= ", id FROM monitoring "; 
                // }
                $sql .= " WHERE is_deleted = ? ";
                if(!empty($start_day)) {
                    $sql .= "AND entries_date >= ? ";
                }
                if(!empty($end_day)) {
                    $sql .= "AND entries_date <= ? ";
                }
                if(!empty($start_to_sleep)){
                    $sql .= "AND sleep_start_time LIKE ? ";
                }
                if(!empty($end_to_sleep)){
                    $sql .= "AND sleep_end_time LIKE ? ";
                }
                if(!empty($sound_sleep)){
                    $sql .= "AND sound_sleep = ? ";
                }
                if(is_numeric($blue_signal) || is_numeric($yellow_signal)){
                    if($signal_flag == false) {
                        $sql .= "AND (condition_id, condition_level) in (";
                        $signal_flag = true;
                    } 
                    $dsn4 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user4 = 'root';
                    $password4 = '';
                    $dbh4 = new PDO($dsn4, $user4, $password4);
                    $dbh4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql4 = 'SELECT DISTINCT P.id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = C.condition_id WHERE 1';
                    $stmt4 = $dbh4 -> prepare($sql4);
                    $stmt4 -> execute();

                    $dbh4 = null;

                    while(true) {
                        $rec4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                        if($rec4==false){
                            break;
                        }
                        if($rec4['display_unnecessary'] == 1){
                            continue;
                        }
                        if($rec4['color'] == 0 && is_numeric($blue_signal)) {
                            if($blue_signal == $rec4['condition_level']){
                                $sql .= " (?,?) ,";
                            }
                        }elseif($rec4['color'] == 2 && is_numeric($yellow_signal)) {
                            if($yellow_signal == $rec4['condition_level']){
                                $sql .= "(?,?) ,";
                            }
                        }
                    }
                }
                
                $dsn6 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user6 = 'root';
                $password6 = '';
                $dbh6 = new PDO($dsn6, $user6, $password6);
                $dbh6->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql6 = 'SELECT P.id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = C.condition_id WHERE 1';
                $stmt6 = $dbh6 -> prepare($sql6);
                $stmt6 -> execute();

                $dbh6 = null;
                $condition_flag = false;

                while(true) {
                    $rec6 = $stmt6->fetch(PDO::FETCH_ASSOC);
                    if($rec6==false){
                        if($condition_flag == true){
                            $sql .= "(?,?),";
                        }
                        break;
                    }
                    $physical_id = '';
                    if($rec6['color'] == 0 || $rec6['color'] == 2) {
                        if(is_numeric($post[$rec6['id']])) {
                            $physical_id = $post[$rec6['id']];
                            $item_id = 'signal' . $physical_id;
                            $signal_name = $post[$item_id];
                            if(is_numeric($signal_name)){
                                if($signal_flag == false) {
                                    $sql .= "AND (condition_id, condition_level) in (";
                                    $signal_flag = true;
                                    $condition_flag = true;
                                }
                                if($rec6['display_unnecessary'] == 1){
                                    continue;
                                }
                                if($physical_id == $rec6['id']){
                                    if($signal_name == $rec6['condition_level']) {
                                        $sql .= "(?,?),";
                                        $condition_flag = false;
                                    }
                                }
                            }
                        }
                    }
                }


                if($signal_flag == true) {
                    $sql = rtrim($sql, ",");
                    $sql .= ")";
                }

                $sql .= " ORDER BY entries_date DESC";
                $data = [];
                $data[] = 0;
                if(!empty($start_day)) {
                    $data[] = $start_day;
                }
                if(!empty($end_day)) {
                    $data[] = $end_day;
                }
                if(!empty($start_to_sleep)) {
                    $data[] = '%'.$start_to_sleep.':00';
                }
                if(!empty($end_to_sleep)) {
                    $data[] = '%'.$end_to_sleep.':00';
                }
                if(!empty($sound_sleep)) {
                    $data[] = $sound_sleep;
                }
                if(is_numeric($blue_signal) || is_numeric($yellow_signal)){
                    $dsn5 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user5 = 'root';
                    $password5 = '';
                    $dbh5 = new PDO($dsn5, $user5, $password5);
                    $dbh5->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql5 = 'SELECT DISTINCT P.id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = C.condition_id WHERE 1';
                    $stmt5 = $dbh5 -> prepare($sql5);
                    $stmt5 -> execute();

                    $dbh5 = null;
                    
                    while(true) {
                        $rec5 = $stmt5->fetch(PDO::FETCH_ASSOC);
                        if($rec5==false){
                            break;
                        }
                        if($rec5['display_unnecessary'] == 1){
                            continue;
                        }
                        if($rec5['color'] == 0 && is_numeric($blue_signal)) {
                            if($blue_signal == $rec5['condition_level']) {
                                $data[] = $rec5['id'];
                                $data[] = $blue_signal;
                            }
                        }elseif($rec5['color'] == 2 && is_numeric($yellow_signal)) {
                            if($yellow_signal == $rec5['condition_level']){
                                $data[] = $rec5['id'];
                                $data[] = $yellow_signal;
                            }
                        }
                    }
                }

                $dsn7 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user7 = 'root';
                $password7 = '';
                $dbh7 = new PDO($dsn7, $user7, $password7);
                $dbh7->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql7 = 'SELECT P.id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = C.condition_id WHERE 1';
                $stmt7 = $dbh7 -> prepare($sql7);
                $stmt7 -> execute();

                $dbh7 = null;
                
                while(true) {
                    $rec7 = $stmt7->fetch(PDO::FETCH_ASSOC);
                    if($rec7==false){
                        if($condition_flag == true){
                            $data[] = $rec7['id'];
                            $data[] = $signal_name;
                        }
                        break;
                    }
                    $physical_id = '';
                    if($rec7['color'] == 0 || $rec7['color'] == 2) {
                        if(is_numeric($post[$rec7['id']])) {
                            $physical_id = $post[$rec7['id']];
                            $item_id = 'signal' . $physical_id;
                            $signal_name = $post[$item_id];
                            if(is_numeric($signal_name)){
                                if($rec7['display_unnecessary'] == 1){
                                    continue;
                                }
                                if($physical_id == $rec7['id']){
                                    if($signal_name == $rec7['condition_level']) {
                                        $data[] = $rec7['id'];
                                        $data[] = $signal_name;
                                    }
                                }
                            }
                        }
                    }
                }

                var_dump($sql);
                $stmt = $dbh -> prepare($sql);
                $stmt -> execute($data);

                $dbh = null;

                // 曜日、熟睡度、昼寝、天気の配列
                $week = array("日", "月", "火", "水", "木", "金", "土");
                $sound = array("", "〇", "✕", "△");
                $sound_nap = array("", "〇", "✕", "？");
                $weather_list = array(
                    '0' => '', '1' => '晴れ', '2' => '晴れ時々曇り', '3' => '晴れ時々雨', '4' => '晴れのち曇り',
                    '5' => '晴れのち雨', '6' => '雨', '7' => '雨時々晴れ', '8' => '雨時々曇り', '9' => '雨のち晴れ', '10' => '雨のち曇り',
                    '11' => '曇り', '12' => '曇り時々晴れ', '13' => '曇り時々雨', '14' => '曇りのち晴れ', '15' => '曇りのち雨',
                );
                $condition_list = array("青", "緑", "黄", "橙", "赤", "黒");
                $same_id = 0;
                while(true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($rec==false){
                        break;
                    }
                    if($same_id == $rec['id']){
                        continue;
                    }
                    $same_id = $rec['id'];
                    ?> <th> <?php print $rec['entries_date'] ?> </th>
                    <?php
                        $date = new DateTime($rec['entries_date']);
                        $w = (int)$date->format('w');
                    ?>
                    <th> <?php print $week[$w] ?> </th>
                    <!-- 睡眠開始時間の時間だけ -->
                    <?php
                        $date = new DateTime($rec['sleep_start_time']);
                        $sleep_start_time = $date->format('H:i');
                    ?>
                    <th> <?php print $sleep_start_time; ?> </th>
                    <!-- 睡眠終了時間の時間だけ -->
                    <?php
                        $date2 = new DateTime($rec['sleep_end_time']);
                        $sleep_end_time = $date2->format('H:i');
                        $interval = date_diff($date, $date2);
                    ?>
                    <th> <?php print $sleep_end_time; ?> </th>
                    <th> <?php print $interval->format('%H:%I'); ?> </th>
                    <th> <?php print $sound[$rec["sound_sleep"]]; ?> </th>
                    <th> <?php print $sound_nap[$rec["nap"]]; ?> </th>
                    <!-- 昼寝開始時間(0ばっかの時は記載しない) -->
                    <?php
                        if($rec['nap_start_time'] == "0000-00-00 00:00:00") {
                            ?> <th> </th> <?php
                        }else{
                            $date3 = new DateTime($rec['nap_start_time']);
                            $nap_start_time = $date3->format('H:i');
                    ?>
                    <th> <?php print $nap_start_time; ?> </th>
                    <!-- 昼寝終了時間(0ばっかの時は記載しない) -->
                    <?php } 
                        if($rec['nap_end_time'] == "0000-00-00 00:00:00") {
                            ?> <th> </th> <?php
                        }else{
                            $date4 = new DateTime($rec['nap_end_time']);
                            $nap_start_time = $date4->format('H:i');
                            $interval2 = date_diff($date3, $date4);
                    ?>
                    <th> <?php print $nap_start_time; ?> </th>
                    <?php }
                    if(($rec['nap_start_time'] == "0000-00-00 00:00:00") || ($rec['nap_end_time'] == "0000-00-00 00:00:00")) {
                        ?> <th> 00:00 </th>
                    <?php }else { ?>
                        <th> <?php print $interval2->format('%H:%I'); ?> </th>
                    <?php } ?>
                    <th> <?php print $weather_list[$rec["weather"]]; ?> </th>
                    <?php
                    // 青信号のIDをもとに２重ループする
                    foreach( $blue_roop as $value ){
                        $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                        $user2 = 'root';
                        $password2 = '';
                        $dbh2 = new PDO($dsn2, $user2, $password2);
                        $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                        $sql2 = 'SELECT P.id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                        $stmt2 = $dbh2 -> prepare($sql2);
                        $data2 = [];
                        $data2[] = $rec['id'];
                        $stmt2 -> execute($data2);

                        $dbh2 = null;

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
                        $dsn3 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                        $user3 = 'root';
                        $password3 = '';
                        $dbh3 = new PDO($dsn3, $user3, $password3);
                        $dbh3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                        $sql3 = 'SELECT condition_id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                        $stmt3 = $dbh3 -> prepare($sql3);
                        $data3 = [];
                        $data3[] = $rec['id'];
                        $stmt3 -> execute($data3);

                        $dbh3 = null;

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
                    <?php
            ?>
            </tr>
            <?php } 
        } ?>
    








    </table>






</div>

</body>
</html>