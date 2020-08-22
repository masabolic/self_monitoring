<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
    <link rel="stylesheet" href="../css/monitor_list.css">
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

    // 件数と略称の初期値
    $count = 0;
    $abbreviation = 0;
    $add_item = [];

    if(isset($post['count'])) {
        $count = $post['count'];
    }
    if(isset($post['abbreviation'])) {
        $abbreviation = $post['abbreviation'];
    }
    if(isset($post['add_item']) && is_array($post['add_item'])) {
        $add_item = $post['add_item'];
    }

    ?>

    <form method="post" action="search_result.php">
    <button type="button" onclick="history.back()">元に戻る</button>
    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面に戻る</button>
    <br><br>

    <!-- 次に引き継ぐ処理 -->
    <input type="hidden" name="weekday" value="<?= $post['weekday'] ?>">
    <input type="hidden" name="start_day" value="<?= $post['start_day'] ?>">
    <input type="hidden" name="end_day" value="<?= $post['end_day'] ?>">
    <input type="hidden" name="start_to_sleep" value="<?= $post['start_to_sleep'] ?>">
    <input type="hidden" name="end_to_sleep" value="<?= $post['end_to_sleep'] ?>">
    <input type="hidden" name="sleep_total" value="<?= $post['sleep_total'] ?>">
    <input type="hidden" name="sleep_up_down" value="<?= $post['sleep_up_down'] ?>">
    <input type="hidden" name="sound_sleep" value="<?= $post['sound_sleep'] ?>">
    <input type="hidden" name="nap_total" value="<?= $post['nap_total'] ?>">
    <input type="hidden" name="nap_up_down" value="<?= $post['nap_up_down'] ?>">
    <input type="hidden" name="blue_signal" value="<?= $post['blue_signal'] ?>">
    <input type="hidden" name="yellow_signal" value="<?= $post['yellow_signal'] ?>">
    <input type="hidden" name="yellow_up_down" value="<?= $post['yellow_up_down'] ?>">
    <input type="hidden" name="condition" value="<?= $post['condition'] ?>">
    <input type="hidden" name="condition_up_down" value="<?= $post['condition_up_down'] ?>">
    <input type="hidden" name="weather" value="<?= $post['weather'] ?>">
    <input type="hidden" name="event" value="<?= $post['event'] ?>">

    <!-- 青信号と黄信号(以上以下も)引き継ぐ -->
    <?php
        $dsn3 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user3 = 'root';
        $password3 = '';
        $dbh3 = new PDO($dsn3, $user3, $password3);
        $dbh3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql3 = 'SELECT id, item, display_unnecessary, color FROM physical_condition_items WHERE color = ? OR color = ?';
        $stmt3 = $dbh3 -> prepare($sql3);
        $data3 = [];
        $data3[] = 0;
        $data3[] = 2;
        $stmt3 -> execute($data3);

        $dbh3 = null;

        
        while(true) {
            $rec3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            if($rec3==false){
                break;
            }
            if($rec3['display_unnecessary'] == 1){
                continue;
            }
            $item_id = 'signal' . $rec3['id'];
            ?>
            <input type="hidden" name="<?= $rec3['id']; ?>" value="<?= $post[$rec3['id']]; ?>">
            <input type="hidden" name="<?= $item_id; ?>" id="<?= $post[$item_id]; ?>">
            <?php if($rec3['color'] == 2) {
                $up_down = 'up_down' . $rec3['id']; ?>
            <input type="hidden" name="<?= $up_down; ?>" value="<?= $post[$up_down]; ?>">
            <?php } ?>
        <?php } ?>




        <div class="row">
            <div class="col-2">件数</div>
            <div class="col-2">
            <input type="radio" name="count" id="all" value="0" <?php if($count == 0) { ?> checked="checked" <?php } ?> >
                <label for="all">全部</label>
            </div>
            <div class="col-2">
                <input type="radio" name="count" id="fifty" value="1" <?php if($count == 1) { ?> checked="checked" <?php } ?> >
                <label for="fifty">50件</label>
            </div>
            <div class="col-2">
                <input type="radio" name="count" id="ten" value="2" <?php if($count == 2) { ?> checked="checked" <?php } ?> >
                <label for="ten">10件</label>
            </div>
        </div>
        <div class="row">
            <div class="col-2"><label for="abbreviation">略称</label></div>
            <div class="col-2">
                <input type="checkbox" name="abbreviation" id="abbreviation" value="1" <?php if($abbreviation == 1) { ?> checked="checked" <?php } ?> >
                <label for="abbreviation">する</label>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-2">追加項目</div>
            <div class="col-2">
                <input type="checkbox" name="add_item[]" id="add_yellow" value="1" <?php foreach($add_item as $e) { if($e == 1) { ?> checked="checked" <?php } } ?>>
                <label for="add_yellow">追加黄</label>
            </div>
            <div class="col-2">
                <input type="checkbox" name="add_item[]" id="add_orenge" value="2" <?php foreach($add_item as $r) { if($r == 2) { ?> checked="checked" <?php } } ?>>
                <label for="add_orenge">追加橙</label>
            </div>
            <div class="col-2">
                <input type="checkbox" name="add_item[]" id="add_red" value="3" <?php foreach($add_item as $d) { if($d == 3) { ?> checked="checked" <?php } } ?>>
                <label for="add_red">追加赤</label>
            </div>
            <div class="col-2">
                <input type="submit" value="変更">
            </div>
        </div>

    </form>
    <br><br>


    <table border="1" class="table-striped">
        <!-- colorのidを配列に入れる -->
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
            $add_yellow_roop = array();
            $add_orenge_roop = array();
            $add_red_roop = array();

            while(true) {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                if($rec==false){
                    break;
                }

                // 削除したものは除く
                if($rec['display_unnecessary'] == 1){
                    continue;
                }
                
                // 青を判定するidを全て入れる。
                if($rec['color'] == 0){
                    $blue_roop[] = $rec['id'];
                
                // 黄を判定するidを全ていれる。
                }elseif($rec['color'] == 2){
                    $yellow_roop[] = $rec['id'];

                // 追加黄を判定するidを全ていれる。
                }elseif($rec['color'] == 6){
                    $add_yellow_roop[] = $rec['id'];

                // 追加橙を判定するidを全ていれる。
                }elseif($rec['color'] == 7){
                    $add_orenge_roop[] = $rec['id'];

                // 追加赤を判定するidを全ていれる。
                }elseif($rec['color'] == 8){
                    $add_red_roop[] = $rec['id'];

                }
            }
            
        ?>
        <thead class="scrollHead">
        <tr>
            <th class="year">年月日</th>
            <th class="weekday">曜日</th>
            <th class="sleep">睡眠開始時間</th>
            <th class="sleep">睡眠終了時間</th>
            <th class="sleep">睡眠合計時間</th>
            <?php
                if($abbreviation == 0) { ?>
                    <th class="sleep_sound">朝起きた時の熟睡感</th>
                <?php }else{ ?>
                    <th class="sleep_sound">熟睡感</th>
                <?php } ?>
            <th class="nap">昼寝した？</th>
            <th class="sleep">昼寝開始時間</th>
            <th class="sleep">昼寝終了時間</th>
            <th class="sleep">昼寝合計時間</th>
            <th class="weather">天気</th>
            <?php
                // 青信号の項目をthに書き出す
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $sql = 'SELECT item, short_name, color FROM physical_condition_items WHERE display_unnecessary = ? AND color = ?';
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
                    if($abbreviation == 0) {
                        ?> <th class="signal"> <?php print $rec['item'] ?> </th>
                    <?php }else{ 
                        ?> <th class="signal"> <?php print $rec['short_name'] ?> </th>
                    <?php }
                }

                // 黄信号の項目をthに書き出す
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


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

                    if($abbreviation == 0) {
                        ?> <th class="signal"> <?php print $rec['item'] ?> </th>
                    <?php }else{ 
                        ?> <th class="signal"> <?php print $rec['short_name'] ?> </th>
                    <?php }
                } ?>
            <th class="signal_total">合計</th>
            <?php foreach($add_item as $y ) {
                if($y == "1") {
                    // 追加黄の項目をthに書き出す
                    $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user = 'root';
                    $password = '';
                    $dbh = new PDO($dsn, $user, $password);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                    $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE display_unnecessary = ? AND color = ?';
                    $stmt = $dbh -> prepare($sql);
                    $data = [];
                    $data[] = 0;
                    $data[] = 6;
                    $stmt -> execute($data);

                    $dbh = null;
                    
                    while(true) {
                        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($rec==false){
                            break;
                        }
                        if($abbreviation == 0) {
                            ?> <th class="signal"> <?php print $rec['item'] ?> </th>
                        <?php }else{ 
                            ?> <th class="signal"> <?php print $rec['short_name'] ?> </th>
                        <?php }
                    }
                }elseif($y == "2") {
                    // 追加黄の項目をthに書き出す
                    $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user = 'root';
                    $password = '';
                    $dbh = new PDO($dsn, $user, $password);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                    $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE display_unnecessary = ? AND color = ?';
                    $stmt = $dbh -> prepare($sql);
                    $data = [];
                    $data[] = 0;
                    $data[] = 7;
                    $stmt -> execute($data);

                    $dbh = null;
                    
                    while(true) {
                        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($rec==false){
                            break;
                        }
                        if($abbreviation == 0) {
                            ?> <th class="signal"> <?php print $rec['item'] ?> </th>
                        <?php }else{ 
                            ?> <th class="signal"> <?php print $rec['short_name'] ?> </th>
                        <?php }
                    }
                }elseif($y == "3") {
                    // 追加黄の項目をthに書き出す
                    $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user = 'root';
                    $password = '';
                    $dbh = new PDO($dsn, $user, $password);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                    $sql = 'SELECT id, item, short_name, display_unnecessary, color FROM physical_condition_items WHERE display_unnecessary = ? AND color = ?';
                    $stmt = $dbh -> prepare($sql);
                    $data = [];
                    $data[] = 0;
                    $data[] = 8;
                    $stmt -> execute($data);

                    $dbh = null;
                    
                    while(true) {
                        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($rec==false){
                            break;
                        }
                        if($abbreviation == 0) {
                            ?> <th class="signal"> <?php print $rec['item'] ?> </th>
                        <?php }else{ 
                            ?> <th class="signal"> <?php print $rec['short_name'] ?> </th>
                        <?php }
                    }
                }
            }
            ?>

            <th class="spirit">体調</th>
            <th class="event">出来事1</th>
            <th class="event">出来事2</th>
            <th class="event">出来事3</th>
            <th class="notice">気づいたこと</th>
        </tr>
        </thead>
        <tbody class="scrollBody">
        <tr>
        <?php

            if(isset($post)) {
                $weekday = $post['weekday'];
                $start_day = $post['start_day'];
                $end_day = $post['end_day'];
                $start_to_sleep = $post['start_to_sleep'];
                $end_to_sleep = $post['end_to_sleep'];
                $sleep_total = $post['sleep_total'];
                $sleep_up_down = $post['sleep_up_down'];
                $sound_sleep = $post['sound_sleep'];
                $nap_total = $post['nap_total'];
                $nap_up_down = $post['nap_up_down'];
                $blue_signal = $post['blue_signal'];
                $yellow_signal = $post['yellow_signal'];
                $yellow_up_down = $post['yellow_up_down'];
                $condition = $post['condition'];
                $condition_up_down = $post['condition_up_down'];
                $weather = $post['weather'];
                $event = $post['event'];
                // 青や黄信号が検索に使われたか判定
                $signal_flag = false;

                //　検索をする
                $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn, $user, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "";
                $sql .= "SELECT entries_date, weekday, sleep_start_time, sleep_end_time, sleep_sum, sound_sleep, nap, nap_start_time, nap_end_time, nap_sum, spirit_signal, weather, event1, event2, event3, notice ";
                $sql .= ", M.id, condition_id, condition_level FROM monitoring M JOIN condition_levels C ON M.id = C.monitoring_id";
                $sql .= " WHERE is_deleted = ? ";
                if(is_numeric($weekday)) {
                    $sql .= "AND weekday = ? ";
                }
                if(!empty($start_day)) {
                    $sql .= "AND entries_date >= ? ";
                }
                if(!empty($end_day)) {
                    $sql .= "AND entries_date <= ? ";
                }
                // データベースには日付と秒も含まれているため、LIKE
                if(!empty($start_to_sleep)){
                    $sql .= "AND sleep_start_time LIKE ? ";
                }
                if(!empty($end_to_sleep)){
                    $sql .= "AND sleep_end_time LIKE ? ";
                }
                if(!empty($sleep_total)){
                    // up_downは以上、以下。0の時以下、1の時以上
                    if(is_numeric($sleep_up_down) && $sleep_up_down == 0) {
                        $sql .= "AND sleep_sum <= ? ";
                    }elseif($sleep_up_down == 1) {
                        $sql .= "AND sleep_sum >= ? ";
                    }else{
                        $sql .= "AND sleep_sum = ? ";
                    }
                }
                if(!empty($sound_sleep)){
                    $sql .= "AND sound_sleep = ? ";
                }
                if(!empty($nap_total)) {
                    if(is_numeric($nap_up_down) && $nap_up_down == 0) {
                    // up_downは以上、以下。0の時以下、1の時以上
                    $sql .= "AND nap_sum <= ? ";
                    }elseif($nap_up_down == 1) {
                        $sql .= "AND nap_sum >= ? ";
                    }else{
                        $sql .= "AND nap_sum = ? ";
                    }
                }
                if(is_numeric($blue_signal) || is_numeric($yellow_signal)){
                    if($signal_flag == false) {
                        $sql .= "AND (";
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
                                $sql .= " (condition_id=? AND condition_level=? ) OR";
                            }
                        }elseif($rec4['color'] == 2 && is_numeric($yellow_signal)) {
                            if($yellow_signal == $rec4['condition_level']){
                                // up_downは以上、以下。0の時以下、1の時以上
                                if(is_numeric($yellow_up_down) && $yellow_up_down == 0) {
                                    $sql .= " (condition_id=? AND condition_level<=? ) OR";
                                }elseif($yellow_up_down == 1) {
                                    $sql .= " (condition_id=? AND condition_level>=? ) OR";
                                }else{
                                    $sql .= " (condition_id=? AND condition_level=? ) OR";
                                }
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
                        // $condition_flagは検索にはかけたけど、引っかからない時につかう。
                        if($condition_flag == true){
                            $sql .= " (condition_id=? OR condition_level=?)";
                        }
                        break;
                    }
                    // 削除されているか確認
                    if($rec6['display_unnecessary'] == 1){
                        continue;
                    }
                    $physical_id = '';
                    // 青と黄の時に通る
                    if($rec6['color'] == 0 || $rec6['color'] == 2) {
                        // physical_condition_itemsのidが飛ばされていたら、通る
                        if(is_numeric($post[$rec6['id']])) {
                            $physical_id = $post[$rec6['id']];
                            $item_id = 'signal' . $physical_id;
                            $signal_name = $post[$item_id];
                            // 以下か以上を送ってきてるか確認
                            $up_down = 'up_down' . $physical_id;
                            $signal_up_down = '';
                            if($rec6['color'] == 2) {
                                $signal_up_down = $post[$up_down];
                            }
                            if(is_numeric($signal_name)){
                                // ここまでで信号の検索がとばされてなかったら、入れる。
                                if($signal_flag == false) {
                                    $sql .= "AND (";
                                    $signal_flag = true;
                                    $condition_flag = true;
                                }
                                if($physical_id == $rec6['id']){
                                    if($signal_name == $rec6['condition_level']) {
                                        if($rec6['color'] == 2){
                                            // up_downは以上、以下。0の時以下、1の時以上
                                            if(is_numeric($signal_up_down) && $signal_up_down == 0) {
                                                $sql .= " (condition_id=? AND condition_level<=? ) OR";
                                            }elseif($signal_up_down == 1) {
                                                $sql .= " (condition_id=? AND condition_level>=? ) OR";
                                            }else{
                                                $sql .= " (condition_id=? AND condition_level=? ) OR";
                                            }
                                        }else{
                                        $sql .= " (condition_id=? AND condition_level=? ) OR";
                                        }
                                    $condition_flag = false;
                                    }
                                }
                            }
                        }
                    }
                }

                // 信号の検索がかかっていたら、最後のORを消して、カッコで閉じる。
                if($signal_flag == true) {
                    $sql = rtrim($sql, "OR");
                    $sql .= ")";
                }

                // 体調信号の検索。以上以下もできる。
                if(!empty($condition)) {
                    if(is_numeric($condition_up_down) && $condition_up_down == 0) {
                        $sql .= "AND spirit_signal <= ? ";
                    }elseif($condition_up_down == 1) {
                        $sql .= "AND spirit_signal >= ? ";
                    }else{
                        $sql .= "AND spirit_signal = ? ";
                    }   
                }

                // 天気の検索
                if(!empty($weather)) {
                    $sql .= "AND weather = ? ";
                }

                // 出来事全部の一括検索
                if(!empty($event)) {
                    $sql .= "AND ( event1 LIKE ? OR event2 LIKE ? OR event3 LIKE ? ) ";
                }

                // 日付は最新のものから
                $sql .= " ORDER BY entries_date DESC ";

                // 検索が入ったものをif文で確認して、dataに代入する。
                $data = [];
                $data[] = 0;
                if(is_numeric($weekday)) {
                    $data[] = $weekday;
                }
                if(!empty($start_day)) {
                    $data[] = $start_day;
                }
                if(!empty($end_day)) {
                    $data[] = $end_day;
                }

                // データベースには秒も入っているからここでは00を入れている。
                if(!empty($start_to_sleep)) {
                    $data[] = '%'.$start_to_sleep.':00';
                }
                if(!empty($end_to_sleep)) {
                    $data[] = '%'.$end_to_sleep.':00';
                }
                if(!empty($sleep_total)){
                    $data[] = $sleep_total;
                }
                if(!empty($sound_sleep)) {
                    $data[] = $sound_sleep;
                }
                if(!empty($nap_total)) {
                    $data[] = $nap_total;
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
                        // 青に検索かけているか確認する。rec5の青の登録だけ通す
                        if($rec5['color'] == 0 && is_numeric($blue_signal)) {
                            // 検索をかけた数値と登録されている数値が一致するかを確認する
                            if($blue_signal == $rec5['condition_level']) {
                                $data[] = $rec5['id'];
                                $data[] = $blue_signal;
                            }
                        // 黄に検索かけているか確認する。rec5の黄の登録だけ通す
                        }elseif($rec5['color'] == 2 && is_numeric($yellow_signal)) {
                            // 検索をかけた数値と登録されている数値が一致するかを確認する
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
                        // $condition_flagは検索にはかけたけど、引っかからない時につかう。
                        if($condition_flag == true){
                            $data[] = $rec7['id'];
                            $data[] = $signal_name;
                        }
                        break;
                    }
                    if($rec7['display_unnecessary'] == 1){
                        continue;
                    }
                    $physical_id = '';
                    if($rec7['color'] == 0 || $rec7['color'] == 2) {
                        if(is_numeric($post[$rec7['id']])) {
                            // physical_condition_itemsのidを元にしてpostで送ってきたidを受け取ってる。
                            $physical_id = $post[$rec7['id']];
                            // signalをつけて、condition_levelsの体調レベルを送っている。
                            $item_id = 'signal' . $physical_id;
                            $signal_name = $post[$item_id];
                            if(is_numeric($signal_name)){
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
                // 体調信号
                if(!empty($condition)) {
                        $data[] = $condition;
                }
                // 天気
                if(!empty($weather)) {
                    $data[] = $weather;
                }
                // 出来事
                if(!empty($event)){
                    $data[] = '%'.$event.'%';
                    $data[] = '%'.$event.'%';
                    $data[] = '%'.$event.'%';
                }

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
                $limit = 0;
                while(true) {
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($rec==false){
                        break;
                    }
                    // condition_levelsとmonitoringのデータベースを結合した時にmonitoringのidのレコードが複数できるため、一つにする。
                    if($same_id == $rec['id']){
                        continue;
                    }
                    $same_id = $rec['id'];
                    $limit++;

                    // 検索結果に制限をかける。
                    if($count == 2) {
                        if($limit > 10) {
                            break;
                        }
                    }elseif($count == 1) {
                        if($limit > 50) {
                            break;
                        }
                    }
                    ?> <td class="year"> <?php print $rec['entries_date'] ?> </td>
                    <td class="weekday"> <?php print $week[$rec['weekday']] ?> </td>
                    <!-- 睡眠開始時間の時間だけ -->
                    <?php
                        $date = new DateTime($rec['sleep_start_time']);
                        $sleep_start_time = $date->format('H:i');
                    ?>
                    <td  class="sleep"> <?php print $sleep_start_time; ?> </td>
                    <!-- 睡眠終了時間の時間だけ -->
                    <?php
                        $date2 = new DateTime($rec['sleep_end_time']);
                        $sleep_end_time = $date2->format('H:i');
                        $date6 = new DateTime($rec['sleep_sum']);
                        $sleep_sum = $date6->format('H:i');
                    ?>
                    <td class="sleep"> <?php print $sleep_end_time; ?> </td>
                    <td class="sleep"> <?php print $sleep_sum; ?> </td>
                    <td class="sleep_sound"> <?php print $sound[$rec["sound_sleep"]]; ?> </td>
                    <td class="nap"> <?php print $sound_nap[$rec["nap"]]; ?> </td>
                    <!-- 昼寝開始時間(0ばっかの時は記載しない) -->
                    <?php
                        if($rec['nap_start_time'] == "0000-00-00 00:00:00") {
                            ?> <td class="sleep"> </td> <?php
                        }else{
                            $date3 = new DateTime($rec['nap_start_time']);
                            $nap_start_time = $date3->format('H:i');
                    ?>
                    <td class="sleep"> <?php print $nap_start_time; ?> </td>
                    <!-- 昼寝終了時間(0ばっかの時は記載しない) -->
                    <?php } 
                        if($rec['nap_end_time'] == "0000-00-00 00:00:00") {
                            ?> <td class="sleep"> </td> <?php
                        }else{
                            $date4 = new DateTime($rec['nap_end_time']);
                            $nap_start_time = $date4->format('H:i');
                            $interval2 = date_diff($date3, $date4);
                    ?>
                    <td class="sleep"> <?php print $nap_start_time; ?> </td>
                        <?php }
                    $date5 = new DateTime($rec["nap_sum"]);
                    $nap_sum = $date5->format('H:i');
                    if($nap_sum == "00:00") { 
                        ?> <td class="sleep"> </td> <?php
                    }else{ ?>
                        <td class="sleep"><?php print $nap_sum; ?></td>
                    <?php } ?>
                    <td class="weather"> <?php print $weather_list[$rec["weather"]]; ?> </td>
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
                                    ?> <td class="signal"> </td> <?php   
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
                                    ?> <td class="signal">-</td>
                                <?php }else{
                                    ?> <td class="signal"> <?php print $rec2['condition_level'] ?> </td>
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
                                    ?> <td class="signal"> </td> <?php   
                                }
                                break;
                            }

                            // 必要とされていない項目は表示しない。
                            if($rec3['display_unnecessary'] == 1){
                                continue;
                            }

                            // 黄信号のIDが一致するときに通る。
                            if($v == $rec3['condition_id'] ) {
                                ?> <td class="signal"> <?php print $rec3['condition_level'] ?> </td>

                                <!-- condition_levelsのデータベースにデータが記載されているか確認 -->
                                <?php $roop_flag = true;

                                // 黄信号の合計を数える
                                $yellow_total += $rec3['condition_level'];
                            }
                        } 
                    } ?>
                    <td class="signal_total"> <?php print $yellow_total; ?> </td>
                                    <!-- checkboxで送ったpostを確認 -->
                <?php foreach($add_item as $l ) {
                    if($l == "1") {
                        // 追加黄のIDをもとに２重ループする
                        foreach( $add_yellow_roop as $w ){
                            $dsn8 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                            $user8 = 'root';
                            $password8 = '';
                            $dbh8 = new PDO($dsn8, $user8, $password8);
                            $dbh8->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                            $sql8 = 'SELECT condition_id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                            $stmt8 = $dbh8 -> prepare($sql8);
                            $data8 = [];
                            $data8[] = $rec['id'];
                            $stmt8 -> execute($data8);

                            $dbh8 = null;

                            $roop_flag = false;

                            while(true) {
                                $rec8 = $stmt8->fetch(PDO::FETCH_ASSOC);
                                if($rec8==false){
                                    // $value == $rec8['id']がない場合は、空白が入るようにする。
                                    if($roop_flag == false) {
                                        ?> <td class="signal"> </td> <?php   
                                    }
                                    break;
                                }

                                // 必要とされていない項目は表示しない。
                                if($rec8['display_unnecessary'] == 1){
                                    continue;
                                }

                                // 追加黄のIDが一致するときに通る。
                                if($w == $rec8['condition_id'] ) {
                                    ?> <td class="signal"> <?php print $rec8['condition_level'] ?> </td>

                                    <!-- condition_levelsのデータベースにデータが記載されているか確認 -->
                                    <?php $roop_flag = true;
                                }
                            } 
                        }
                    }elseif($l == "2") {
                        // 追加橙のIDをもとに２重ループする
                        foreach( $add_orenge_roop as $n ){
                            $dsn9 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                            $user9 = 'root';
                            $password9 = '';
                            $dbh9 = new PDO($dsn9, $user9, $password9);
                            $dbh9->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                            $sql9 = 'SELECT condition_id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                            $stmt9 = $dbh9 -> prepare($sql9);
                            $data9 = [];
                            $data9[] = $rec['id'];
                            $stmt9 -> execute($data9);

                            $dbh9 = null;

                            $roop_flag = false;

                            while(true) {
                                $rec9 = $stmt9->fetch(PDO::FETCH_ASSOC);
                                if($rec9==false){
                                    // $value == $rec9['id']がない場合は、空白が入るようにする。
                                    if($roop_flag == false) {
                                        ?> <td class="signal"> </td> <?php   
                                    }
                                    break;
                                }

                                // 必要とされていない項目は表示しない。
                                if($rec9['display_unnecessary'] == 1){
                                    continue;
                                }

                                // 追加橙のIDが一致するときに通る。
                                if($n == $rec9['condition_id'] ) {
                                    ?> <td class="signal"> <?php print $rec9['condition_level'] ?> </td>

                                    <!-- condition_levelsのデータベースにデータが記載されているか確認 -->
                                    <?php $roop_flag = true;
                                }
                            } 
                        }
                    }elseif($l == "3") {
                        // 追加橙のIDをもとに２重ループする
                        foreach( $add_red_roop as $red ){
                            $dsn10 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                            $user10 = 'root';
                            $password10 = '';
                            $dbh10 = new PDO($dsn10, $user10, $password10);
                            $dbh10->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                            $sql10 = 'SELECT condition_id, display_unnecessary, color, condition_level FROM physical_condition_items P JOIN condition_levels C ON P.id = condition_id WHERE monitoring_id = ?';
                            $stmt10 = $dbh10 -> prepare($sql10);
                            $data10 = [];
                            $data10[] = $rec['id'];
                            $stmt10 -> execute($data10);

                            $dbh10 = null;

                            $roop_flag = false;

                            while(true) {
                                $rec10 = $stmt10->fetch(PDO::FETCH_ASSOC);
                                if($rec10==false){
                                    // $value == $rec10['id']がない場合は、空白が入るようにする。
                                    if($roop_flag == false) {
                                        ?> <td class="signal"> </td> <?php   
                                    }
                                    break;
                                }

                                // 必要とされていない項目は表示しない。
                                if($rec10['display_unnecessary'] == 1){
                                    continue;
                                }

                                // 追加赤のIDが一致するときに通る。
                                if($red == $rec10['condition_id'] ) {
                                    ?> <td class="signal"> <?php print $rec10['condition_level'] ?> </td>

                                    <!-- condition_levelsのデータベースにデータが記載されているか確認 -->
                                    <?php $roop_flag = true;
                                }
                            } 
                        }
                    }
                } ?>
                <td class="spirit"> <?php print $condition_list[$rec["spirit_signal"]]; ?> </td>
                <td class="event"> <?php print $rec["event1"]; ?> </td>
                <td class="event"> <?php print $rec["event2"]; ?> </td>
                <td class="event"> <?php print $rec["event3"]; ?> </td>
                <td class="notice"> <?php print $rec["notice"]; ?> </td>
            </tr>
            <?php } 
        } ?>
    </tbody>
</table>
</div>

</body>
</html>