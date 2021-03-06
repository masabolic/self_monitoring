<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>編集</h1>
    </div>
    <br>
    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    <button type="button" onclick="history.back()">元に戻る</button>
    <br>

    <?php
    session_start();
    require_once('../common.php');
    $get = sanitize($_GET);
    // サニタイジング
    if(!empty($_POST)){
        $post = sanitize($_POST);
    }

    if(isset($post)) {
        // postで送ってきたものを代入
        $entries_date = $_SESSION['date'];
        $date5 = new DateTime($entries_date);
        // 曜日を入れる
        $weekday = (int)$date5->format('w');

        $monitoring_id = $post['monitoring_id'];
        $sleep_start_time = $post['sleep_start_time'];
        $sleep_end_time = $post['sleep_end_time'];
        // 睡眠時間の差をだし、時間だけ取り出す。   
        $date = new DateTime($sleep_start_time);
        $date2 = new DateTime($sleep_end_time);
        $interval = date_diff($date, $date2);
        $sleep_sum = $interval->format('%H:%I');

        $sound_sleep = $post['sound_sleep'];
        $nap = $post['nap'];
        $nap_start_time = $post['nap_start_time'];
        $nap_end_time = $post['nap_end_time'];

        // 昼寝時間の差をだし、時間だけ取り出す。
        $date3 = new DateTime($nap_start_time);
        $date4 = new DateTime($nap_end_time);
        $interval2 = date_diff($date3, $date4);
        $nap_sum = $interval2->format('%H:%I');

        $weather = $post['weather'];
        $event1 = $post['event1'];
        $event2 = $post['event2'];
        $event3 = $post['event3'];
        $notice = $post['notice'];

        // 体調信号を割り出す為の、カウンタ
        $spirit_signal_yellow = 0;
        $spirit_signal_orenge = 0;
        $spirit_signal_red = 0;
        $spirit_signal_black = 0;

        // SQLに登録してよいか判断する為のフラグ
        $ok_flag = true;

        // エラー
        if(strlen($event1) > 100 || strlen($event2) > 100 || strlen($event3) > 100) {
            print "✓　恐れ⼊りますが、出来事は100⽂字以内でご⼊⼒ください。<br>";
            $ok_flag = false;
        }

        if(strlen($notice) > 1000 ) {
            print "✓　恐れ⼊りますが、気づいたことは1000⽂字以内でご⼊⼒ください。<br>";
            $ok_flag = false;
        }

        if($sleep_start_time > $sleep_end_time) {
            print "✓　恐れ⼊りますが、睡眠開始時間が睡眠終了時間より遅いです。<br>";
            $ok_flag = false;
        }

        if($nap_start_time > $nap_end_time) {
            print "✓　恐れ⼊りますが、昼寝開始時間が昼寝終了時間より遅いです。<br>";
            $ok_flag = false;
        }

        // SQLに登録    
        if($ok_flag == true) {
            // monitoringをアップデート
            $dbh = dbconnect();

            $sql = 'UPDATE monitoring SET weekday=?, sleep_start_time=?, sleep_end_time=?, sleep_sum=?, sound_sleep=?, nap=?, nap_start_time=?, nap_end_time=?, nap_sum=?, weather=?, event1=?, event2=?, event3=?, notice=? WHERE id = ? ';
            $stmt = $dbh -> prepare($sql);
            $data = [];
            $data[] = $weekday;
            $data[] = $sleep_start_time;
            $data[] = $sleep_end_time;
            $data[] = $sleep_sum;
            $data[] = $sound_sleep;
            $data[] = $nap;
            $data[] = $nap_start_time;
            $data[] = $nap_end_time;
            $data[] = $nap_sum;
            $data[] = $weather;
            $data[] = $event1;
            $data[] = $event2;
            $data[] = $event3;
            $data[] = $notice;
            $data[] = $monitoring_id;

            $stmt -> execute($data);
        
            $dbh = null;

            //一週間前の日付を取得
            $date = new DateTime($entries_date, new \DateTimeZone('Asia/Tokyo'));
            $date -> sub(new DateInterval("P1W"));
            $before_week = $date -> format("Y-m-d");

            //一週間分の体調信号を取得し、橙以上の体調を判定
            $dbh = dbconnect();

            $sql9 = "SELECT spirit_signal FROM monitoring WHERE entries_date >= ? AND entries_date <= ? AND is_deleted = ?";
            $data9 = [];
            $data9[] = $before_week;
            $data9[] = $entries_date;
            $data9[] = 0;
            $stmt9 = $dbh -> prepare($sql9);
            $stmt9 -> execute($data9);

            $dbh = null;
            while(true) {
                $rec9 = $stmt9->fetch(PDO::FETCH_ASSOC);
                if($rec9==false){
                    break;
                }
                // 体調信号が赤以上場合
                if($rec9['spirit_signal'] >= 4) {
                    $spirit_signal_black++;
                }
                // 体調信号が橙以上の場合
                if($rec9['spirit_signal'] >= 3) {
                    $spirit_signal_red++;
                }
                // 体調信号が黄以上の場合
                if($rec9['spirit_signal'] >= 2) {
                    $spirit_signal_orenge++;
                }

            }

            

            $dbh = dbconnect();

            $sql5 = 'SELECT id, display_unnecessary, color FROM physical_condition_items WHERE color = ? OR color = ?';
            $data = [];
            $data[] = 0;
            $data[] = 2;
            $stmt5 = $dbh -> prepare($sql5);
            $stmt5 -> execute($data);

            $dbh = null;

            while(true) {
                $rec5 = $stmt5->fetch(PDO::FETCH_ASSOC);

                //　記入後、体調信号と行動指針の度合を決める。
                if($rec5==false){
                    $dbh = dbconnect();

                    $sql7 = 'UPDATE monitoring SET spirit_signal=? WHERE id = ?';
                    $stmt7 = $dbh -> prepare($sql7);
                    $data7 = [];
                    // その日が青や緑だった場合、前の日は関係なくその体調信号になる
                    if($spirit_signal_yellow == 0) {
                        $data7[] = 0;
                        $spirit_signal = 0;
                    }elseif($spirit_signal_yellow == 1) {
                        $data7[] = 1;
                        $spirit_signal = 1;
                    // その日の体調信号が黄と判定された場合、一週間前までのデータを掛け合わせる                    
                    }else{
                        // 赤以上が1週間の間で5回以上あった場合、黒になる
                        if($spirit_signal_black >= 4) {
                            $data7[] = 5;
                            $spirit_signal = 5;
                        // 橙以上が1週間の間で３回以上か黄以上が５回以上あった場合、赤になる
                        }elseif($spirit_signal_red >= 3 || $spirit_signal_orenge >= 5){
                            $data7[] = 4;
                            $spirit_signal = 4;
                        // 黄以上が1週間の間で３回以上があった場合、橙になる
                        }elseif($spirit_signal_orenge >= 3){
                            $data7[] = 3;
                            $spirit_signal = 3;
                        // 上記の条件に当てはまらない場合、黄になる
                        }else{
                            $data7[] = 2;
                            $spirit_signal = 2;
                        }
                    }
                    $data7[] = $monitoring_id;

                    $stmt7 -> execute($data7);

                    $dbh = null;
                    break;
                }

                //不必要となったら、記録しない
                if($rec5['display_unnecessary'] == 1){
                    continue;
                }

                $condition_id = $rec5['id'];
                $condition_level = $post[$condition_id];

                // 体調レベルによって、体調・精神信号を決める
                if(is_numeric($condition_level)) {
                    if($rec5['color'] == 2) {
                        if($condition_level == 4) {
                            $spirit_signal_yellow += 2;
                        }elseif($condition_level >= 2) {
                            $spirit_signal_yellow++;
                        }
                    }
                    // monitoring記入後にidを取り出す。
                    $dbh = dbconnect();

                    $sql8 = "SELECT id FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?";
                    $data8 = [];
                    $data8[] = $monitoring_id;
                    $data8[] = $condition_id;
                    $stmt8 = $dbh -> prepare($sql8);
                    $stmt8 -> execute($data8);

                    $dbh = null;

                    $rec8 = $stmt8->fetch(PDO::FETCH_ASSOC);
                    // condition_levelsのidを"id"につけてpostで送った。あれば受け取る。
                    $id = "id" . $rec8['id'];
                    $level_id = $post[$id];
                    
                    if(is_numeric($level_id)) {
                    // 体調レベルのSQLをアップデートする。
                    $dbh = dbconnect();

                    $sql6 = 'UPDATE condition_levels SET condition_level=? WHERE id = ?';
                    $stmt6 = $dbh -> prepare($sql6);
                    $data6 = [];
                    $data6[] = $condition_level;
                    $data6[] = $level_id;

                    $stmt6 -> execute($data6);

                    $dbh = null;
                    } else {
                        // condition_levelsに登録されていなくて、新規に登録する場合
                        if(isset($condition_level)) {
                            $dbh = dbconnect();

                            $sql9 = 'INSERT INTO condition_levels(monitoring_id, condition_id, condition_level) VALUES(?,?,?)';
                            $stmt9 = $dbh -> prepare($sql9);
                            $data9 = [];
                            $data9[] = $monitoring_id;
                            $data9[] = $condition_id;
                            $data9[] = $condition_level;

                            $stmt9 -> execute($data9);

                            $dbh = null;
                        }
                    }
                }
            }
            // conditionに繋げる為にSESSIONに入れる
            $_SESSION['spirit_signal'] = $spirit_signal;
            $_SESSION['monitoring_id'] = $monitoring_id;

            header('Location: condition.php');
            exit();
        }
    }


    ?>

    <form method="post" action="edit.php">
    <br><br>

    <h2>日付</h2>
    <h4><?php 
    if(isset($post)) {
        print $entries_date;
    }else{
        print $get["date"];
        $_SESSION['date'] = $get["date"];
    } ?></h4>
    <br>
    
    <?php
    $dbh = dbconnect();

    $sql2 = "SELECT id, sleep_start_time, sleep_end_time, sound_sleep, nap, nap_start_time, nap_end_time, weather, event1, event2, event3, notice FROM monitoring WHERE entries_date = ? AND is_deleted = ?";
    $data2 = [];
    $data2[] = $_SESSION['date'];
    $data2[] = 0;
    $stmt2 = $dbh -> prepare($sql2);
    $stmt2 -> execute($data2);

    $dbh = null;

    $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $monitoring_id = $rec2['id'];

    // 記入蘭に初期値を入れる。
    $sleep_start =  $rec2["sleep_start_time"];
    $date_start = new DateTime($sleep_start);
    // 日付と時間の間にTを入れないと初期値に反映されないので、入れている。
    $sleep_start_time_default = $date_start->format('Y-m-d') . 'T' . $date_start->format('H:i');
    $sleep_end = $rec2["sleep_end_time"];
    $date_end = new DateTime($sleep_end);
    // 日付と時間の間にTを入れないと初期値に反映されないので、入れている。
    $sleep_end_time_default = $date_end->format('Y-m-d') . 'T' . $date_end->format('H:i');
    $sound_sleep_default = $rec2["sound_sleep"];
    $nap_default = $rec2["nap"];
    $nap_start = $rec2["nap_start_time"];
    $date_nap_start = new DateTime($nap_start);
    // 日付と時間の間にTを入れないと初期値に反映されないので、入れている。
    $nap_start_time_default = $date_nap_start->format('Y-m-d') . 'T' . $date_nap_start->format('H:i');
    $nap_end = $rec2["nap_end_time"];
    $date_nap_end = new DateTime($nap_end);
    // 日付と時間の間にTを入れないと初期値に反映されないので、入れている。
    $nap_end_time_default = $date_nap_end->format('Y-m-d') . 'T' . $date_nap_end->format('H:i');
    $weather_default = $rec2["weather"];
    $event1_default = $rec2["event1"];
    $event2_default = $rec2["event2"];
    $event3_default = $rec2["event3"];
    $notice_default = $rec2["notice"];
    ?>

    <!-- 睡眠記入欄 -->
    <h2>睡眠</h2>
    <div class="row">
        <div class="col-2"><label for="sleep_start_time">睡眠開始時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="sleep_start_time" id="sleep_start_time" <?php if(isset($sleep_start_time)) { ?> value="<?= $sleep_start_time ?>" <?php }else{ ?> value="<?= $sleep_start_time_default ?>"  <?php }?> >
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="sleep_end_time">睡眠終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="sleep_end_time" id="sleep_end_time" <?php if(isset($sleep_end_time)) { ?> value="<?= $sleep_end_time ?>" <?php }else{ ?> value="<?= $sleep_end_time_default ?>" <?php }?> >
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">朝起きた時の熟睡感</div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="no_answer" value="0" <?php if(isset($sound_sleep)){ if($sound_sleep == 0) { ?> checked="checked" <?php } }elseif($sound_sleep_default == 0) { ?> checked='checked' <?php } ?> >
            <label for="no_answer">未回答</label>
        </div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="yes_sleep" value="1" <?php if(isset($sound_sleep)){ if($sound_sleep == 1) { ?> checked="checked" <?php } }elseif($sound_sleep_default == 1) { ?>  checked='checked' <?php } ?> >
            <label for="yes_sleep">〇：ある</label>
        </div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="no_sleep" value="2" <?php if(isset($sound_sleep)){ if($sound_sleep == 2) { ?> checked="checked" <?php } }elseif($sound_sleep_default == 2) { ?> checked='checked' <?php } ?> >
            <label for="no_sleep">✕：ない</label>
        </div>
        <div class="col-4">
            <input type="radio" name="sound_sleep" id="not_know_sleep" value="3" <?php if(isset($sound_sleep)){ if($sound_sleep == 3) { ?> checked="checked" <?php } }elseif($sound_sleep_default == 3) { ?> checked='checked' <?php } ?> >
            <label for="not_know_sleep">△：どちらともいえない</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">昼寝した？？</div>
        <div class="col-2">
            <input type="radio" name="nap" id="no_answer_nap" value="0" <?php if(isset($nap)){ if($nap == 0) { ?> checked="checked" <?php } }elseif($nap_default == 0) { ?> checked='checked' <?php } ?> >
            <label for="no_answer">未回答</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="yes_nap" value="1" <?php if(isset($nap)){ if($nap == 1) { ?> checked="checked" <?php } }elseif($nap_default == 1) { ?> checked='checked' <?php } ?> >
            <label for="yes_nap">〇：はい</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="no_nap" value="2" <?php if(isset($nap)){ if($nap == 2) { ?> checked="checked" <?php } }elseif($nap_default == 2) { ?> checked='checked' <?php } ?> >
            <label for="no_nap">✕：いいえ</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="not_know_nap" value="3" <?php if(isset($nap)){ if($nap == 3) { ?> checked="checked" <?php } }elseif($nap_default == 3) { ?> checked='checked' <?php } ?> >
            <label for="not_know_nap">？：忘れた</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2"><label for="nap_start_time">昼寝開始時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_start_time" id="nap_start_time" value="<?php if(isset($nap_start_time)) { print $nap_start_time; }elseif(!empty($nap_start_time_default)) { print $nap_start_time_default; }?>" >
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="nap_end_time">昼寝終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_end_time" id="nap_end_time" value="<?php if(isset($nap_end_time)) { print $nap_end_time; }elseif(!empty($nap_end_time_default)) { print $nap_end_time_default; }?>">
        </div>
    </div>
    <br>
    <br>

    <h2>青信号</h2>
    <br>

    <?php

    // 天気
    $weather_list = array(
        '0' => '--選択して下さい--', '1' => '晴れ', '2' => '晴れ時々曇り', '3' => '晴れ時々雨', '4' => '晴れのち曇り',
        '5' => '晴れのち雨', '6' => '雨', '7' => '雨時々晴れ', '8' => '雨時々曇り', '9' => '雨のち晴れ', '10' => '雨のち曇り',
        '11' => '曇り', '12' => '曇り時々晴れ', '13' => '曇り時々雨', '14' => '曇りのち晴れ', '15' => '曇りのち雨',
    );
    $signal_list = array('1' => '1', '2' => '2', '3' => '3', '4' => '4');
    ?>

    <!-- 青信号 -->
    <p>0：できていない</p>
    <p>1：少しできてない</p>
    <p>2：普通</p>
    <p>3：少し出来てる</p>
    <p>4：出来てる</p>
    <p>ー:やってない(判定できない)</p>

    <?php
        $dbh = dbconnect();                        

        $sql = 'SELECT id, item, display_unnecessary FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 0;
        $stmt -> execute($data);

        $dbh = null;

        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            }
            if($rec['display_unnecessary'] == 1){
                continue;
            }

            // condition_levelsにすでに記載してある体調レベルを呼び出す。初期値に使う。
            $dbh = dbconnect();

            $sql3 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
            $data3 = [];
            $data3[] = $monitoring_id;
            $data3[] = $rec['id'];
            $stmt3 = $dbh -> prepare($sql3);
            $stmt3 -> execute($data3);

            $dbh = null;
            $rec3 = $stmt3->fetch(PDO::FETCH_ASSOC);

            $blue_id = "";
            // idをつけて、postで送る。condition_levelsに存在しているかを後で確認するため。
            if(isset($rec3['id'])) {
                $blue_id = "id" . $rec3['id'];
            }
            ?>
            <h5>
            <input type="hidden" name="monitoring_id" value="<?= $monitoring_id; ?>">
            <input type="hidden" name="<?= $blue_id; ?>" value="<?= $rec3['id']; ?>">
            <label for="<?= $rec['id']; ?>"><?php print $rec['item']; ?></label>
            </h5>
            <select name="<?= $rec['id']; ?>" id="<?= $rec['id']; ?>">
                <option value="" <?php if(isset($post[$rec['id']])){ if(!is_numeric($post[$rec['id']])) {  ?> selected <?php } }elseif(!isset($rec3['condition_level']) || is_null($rec3['condition_level'])){ ?> selected <?php } ?> >--選択して下さい--</option>
                <option value="0" <?php if(isset($post[$rec['id']])){ if(is_numeric($post[$rec['id']])) {  ?> selected <?php } }elseif(isset($rec3['condition_level'])){ ?> selected <?php } ?>>0</option>
                <?php foreach ($signal_list as $v => $value) : ?>
                    <option value="<?= $v ?>" <?php if(isset($post[$rec['id']])){ if( $post[$rec['id']] == $v ) {  ?> selected <?php } }elseif(isset($rec3['condition_level']) && $rec3['condition_level'] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                <?php endforeach ?>
                <option value="5" <?php if(isset($post[$rec['id']])){ if( $post[$rec['id']] == 5 ) {  ?> selected <?php } }elseif(isset($rec3['condition_level']) && $rec3['condition_level'] == 5){ ?> selected <?php } ?>>-</option>
            </select>
            <br>
            <br>
        <?php } ?>
        <br><br>

    <input type="button" value="一括(-)">
    <br><br><br><br>

    <h2>黄信号</h2>
    <br>

    <p>0：体調異常なし</p>
    <p>1：変化はあるけど、体調に関わるほどではない</p>
    <p>2：体調にちょっと関わる</p>
    <p>3：体調に関わる</p>
    <p>4：ひどいほど出てる</p>
    <br>
    <br>

    <?php
        // condition_levelsにすでに記載してある体調レベルを呼び出す。初期値に使う。
        $dbh = dbconnect();

        $sql = 'SELECT id, item, display_unnecessary FROM physical_condition_items WHERE color = ?';
        $stmt = $dbh -> prepare($sql);
        $data = [];
        $data[] = 2; 
        $stmt -> execute($data);

        $dbh = null;
        
        while(true) {
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            }
            if($rec['display_unnecessary'] == 1){
                continue;
            }

            $dbh = dbconnect();

            $sql4 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
            $data4 = [];
            $data4[] = $monitoring_id;
            $data4[] = $rec['id'];
            $stmt4 = $dbh -> prepare($sql4);
            $stmt4 -> execute($data4);

            $dbh = null;
            $rec4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        
            // idをつけて、postで送る。condition_levelsに存在しているかを後で確認するため。
            $yellow_id = "";
            if(isset($rec4['id'])) {
                $yellow_id = "id" . $rec4['id'];
            }
            ?>
            <input type="hidden" name="<?= $yellow_id; ?>" value="<?= $rec4['id']; ?>">
            <h5>
            <label for="<?= $rec['id']; ?>"><?php print $rec['item']; ?></label>
            </h5>
            <select name="<?= $rec['id']; ?>" id="<?= $rec['id']; ?>">
                <option value="" <?php if(isset($post[$rec['id']])){ if(!is_numeric($post[$rec['id']])) {  ?> selected <?php } }elseif(!isset($rec4['condition_level']) || is_null($rec4['condition_level'])){ ?> selected <?php } ?> >--選択して下さい--</option>
                <option value="0" <?php if(isset($post[$rec['id']])){ if(is_numeric($post[$rec['id']])) {  ?> selected <?php } }elseif(isset($rec4['condition_level'])){ ?> selected <?php } ?>>0</option>
                <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>" <?php if(isset($post[$rec['id']])){ if( $post[$rec['id']] == $v ) {  ?> selected <?php } }elseif(isset($rec4['condition_level']) && $rec4['condition_level'] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <br>
        <?php } ?>
    
    <input type="button" value="一括(0)">
    <br><br><br><br>

    <h5>
    <label for="weather">天気</label>
    </h5> 
    <select name="weather" id="weather">
        <?php foreach ($weather_list as $v => $value) : ?>
            <option value="<?= $v ?>" <?php if(isset($weather)) { if($weather == $v){ ?> selected <?php } }elseif($weather_default == $v){ ?> selected <?php } ?> > <?= $value ?></option>
        <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>出来事</h5>
    <div class="row">
        <div class="col-3">
        <input type="text" name="event1" <?php if(isset($event1)) { ?> value=<?= $event1 ?> <?php }else{ ?>value=<?= $event1_default ?> <?php } ?> >
        </div>
        <div class="col-3">
            <input type="text" name="event2" <?php if(isset($event2)) { ?> value=<?= $event2 ?> <?php }else{ ?>value=<?= $event2_default ?> <?php } ?>>
        </div>
        <div class="col-3">
            <input type="text" name="event3" <?php if(isset($event3)) { ?> value=<?= $event3 ?> <?php }else{ ?>value=<?= $event3_default ?> <?php } ?>>
        </div>
    </div>
    <br>
    <br>

    <h5>
    <label for="notice">気づいたこと</label>
    </h5>
    <textarea name="notice" id="notice" style="width:80%; height:100px;"><?php if(isset($notice)) { print $notice; }else{ print $notice_default; } ?></textarea>
    <br>
    <br>

    <input type="submit" value="確定">
    <br>
    <br>
    </form>

</div>

</body>
</html>