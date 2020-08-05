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

    <?php
    session_start();
    if(!empty($_POST)){
        require_once('../common.php');
        $post = sanitize($_POST);
    }

    if(isset($post)) {
        $entries_date = $_SESSION['date'];
        $date5 = new DateTime($entries_date);
        $weekday = (int)$date5->format('w');

        $monitoring_id = $post['monitoring_id'];
        $sleep_start_time = $post['sleep_start_time'];
        $sleep_end_time = $post['sleep_end_time'];

        $date = new DateTime($sleep_start_time);
        $date2 = new DateTime($sleep_end_time);
        $interval = date_diff($date, $date2);
        $sleep_sum = $interval->format('%H:%I');

        $sound_sleep = $post['sound_sleep'];
        $nap = $post['nap'];
        $nap_start_time = $post['nap_start_time'];
        $nap_end_time = $post['nap_end_time'];

        $date3 = new DateTime($nap_start_time);
        $date4 = new DateTime($nap_end_time);
        $interval2 = date_diff($date3, $date4);
        $nap_sum = $interval2->format('%H:%I');

        $weather = $post['weather'];
        $event1 = $post['event1'];
        $event2 = $post['event2'];
        $event3 = $post['event3'];
        $notice = $post['notice'];
        $spirit_signal_yellow = 0;

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
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

            $dsn5 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user5 = 'root';
            $password5 = '';
            $dbh5 = new PDO($dsn5, $user5, $password5);
            $dbh5->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql5 = 'SELECT id, display_unnecessary, color FROM physical_condition_items WHERE color = ? OR color = ?';
            $data = [];
            $data[] = 0;
            $data[] = 2;
            $stmt5 = $dbh5 -> prepare($sql5);
            $stmt5 -> execute($data);

            $dbh5 = null;

            while(true) {
                $rec5 = $stmt5->fetch(PDO::FETCH_ASSOC);

                //　記入後、体調信号と行動指針の度合を決める。
                if($rec5==false){
                    $dsn7 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user7 = 'root';
                    $password7 = '';
                    $dbh7 = new PDO($dsn7, $user7, $password7);
                    $dbh7->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql7 = 'UPDATE monitoring SET spirit_signal=? WHERE id = ?';
                    $stmt7 = $dbh7 -> prepare($sql7);
                    $data7 = [];
                    if($spirit_signal_yellow == 0) {
                        $data7[] = 0;
                        $spirit_signal = 0;
                    }elseif($spirit_signal_yellow == 1) {
                        $data7[] = 1;
                        $spirit_signal = 1;
                    }else{
                        $data7[] = 2;
                        $spirit_signal = 2;
                    }
                    $data7[] = $monitoring_id;

                    $stmt7 -> execute($data7);

                    $dbh7 = null;
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
                    $dsn8 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user8 = 'root';
                    $password8 = '';
                    $dbh8 = new PDO($dsn8, $user8, $password8);
                    $dbh8->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                    $sql8 = "SELECT id FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?";
                    $data8 = [];
                    $data8[] = $monitoring_id;
                    $data8[] = $condition_id;
                    $stmt8 = $dbh8 -> prepare($sql8);
                    $stmt8 -> execute($data8);

                    $dbh8 = null;

                    $rec8 = $stmt8->fetch(PDO::FETCH_ASSOC);
                    $id = "id" . $rec8['id'];
                    $level_id = $post[$id];
                    
                    if(is_numeric($level_id)) {
                    // 体調レベルのSQLをアップデートする。
                    $dsn6 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                    $user6 = 'root';
                    $password6 = '';
                    $dbh6 = new PDO($dsn6, $user6, $password6);
                    $dbh6->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql6 = 'UPDATE condition_levels SET condition_level=? WHERE id = ?';
                    $stmt6 = $dbh6 -> prepare($sql6);
                    $data6 = [];
                    $data6[] = $condition_level;
                    $data6[] = $level_id;

                    $stmt6 -> execute($data6);

                    $dbh6 = null;
                    } else {
                        if(isset($condition_level)) {
                            $dsn9 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                            $user9 = 'root';
                            $password9 = '';
                            $dbh9 = new PDO($dsn9, $user9, $password9);
                            $dbh9->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $sql9 = 'INSERT INTO condition_levels(monitoring_id, condition_id, condition_level) VALUES(?,?,?)';
                            $stmt9 = $dbh9 -> prepare($sql9);
                            $data9 = [];
                            $data9[] = $monitoring_id;
                            $data9[] = $condition_id;
                            $data9[] = $condition_level;

                            $stmt9 -> execute($data9);

                            $dbh9 = null;
                        }
                    }
                }
            }
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
        print $_GET["date"];
        $_SESSION['date'] = $_GET["date"];
    } ?></h4>
    <br>
    
    <?php
    $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
    $user2 = 'root';
    $password2 = '';
    $dbh2 = new PDO($dsn2, $user2, $password2);
    $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql2 = "SELECT id, sleep_start_time, sleep_end_time, sound_sleep, nap, nap_start_time, nap_end_time, weather, event1, event2, event3, notice FROM monitoring WHERE entries_date = ? AND is_deleted = ?";
    $data2 = [];
    $data2[] = $_SESSION['date'];
    $data2[] = 0;
    $stmt2 = $dbh2 -> prepare($sql2);
    $stmt2 -> execute($data2);

    $dbh2 = null;

    $rec2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $monitoring_id = $rec2['id'];

    $sleep_start =  $rec2["sleep_start_time"];
    $date_start = new DateTime($sleep_start);
    $sleep_start_time_default = $date_start->format('Y-m-d') . 'T' . $date_start->format('H:i');
    $sleep_end = $rec2["sleep_end_time"];
    $date_end = new DateTime($sleep_end);
    $sleep_end_time_default = $date_end->format('Y-m-d') . 'T' . $date_end->format('H:i');
    $sound_sleep_default = $rec2["sound_sleep"];
    $nap_default = $rec2["nap"];
    $nap_start = $rec2["nap_start_time"];
    $date_nap_start = new DateTime($nap_start);
    $nap_start_time_default = $date_nap_start->format('Y-m-d') . 'T' . $date_nap_start->format('H:i');
    $nap_end = $rec2["nap_end_time"];
    $date_nap_end = new DateTime($nap_end);
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
            <input type="datetime-local" name="sleep_start_time" id="sleep_start_time" value="<?= $sleep_start_time_default ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="sleep_end_time">睡眠終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="sleep_end_time" id="sleep_end_time" value="<?= $sleep_end_time_default ?>">
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">朝起きた時の熟睡感</div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="no_answer" value="0" <?php if($sound_sleep_default == 0) { ?> checked='checked' <?php } ?> >
            <label for="no_answer">未回答</label>
        </div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="yes_sleep" value="1" <?php if($sound_sleep_default == 1) { ?> checked='checked' <?php } ?> >
            <label for="yes_sleep">〇：ある</label>
        </div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="no_sleep" value="2" <?php if($sound_sleep_default == 2) { ?> checked='checked' <?php } ?> >
            <label for="no_sleep">✕：ない</label>
        </div>
        <div class="col-4">
            <input type="radio" name="sound_sleep" id="not_know_sleep" value="3" <?php if($sound_sleep_default == 3) { ?> checked='checked' <?php } ?> >
            <label for="not_know_sleep">△：どちらともいえない</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">昼寝した？？</div>
        <div class="col-2">
            <input type="radio" name="nap" id="no_answer_nap" value="0" <?php if($nap_default == 0) { ?> checked='checked' <?php } ?> >
            <label for="no_answer">未回答</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="yes_nap" value="1" <?php if($nap_default == 1) { ?> checked='checked' <?php } ?> >
            <label for="yes_nap">〇：はい</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="no_nap" value="2" <?php if($nap_default == 2) { ?> checked='checked' <?php } ?> >
            <label for="no_nap">✕：いいえ</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="not_know_nap" value="3" <?php if($nap_default == 3) { ?> checked='checked' <?php } ?> >
            <label for="not_know_nap">？：忘れた</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2"><label for="nap_start_time">昼寝開始時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_start_time" id="nap_start_time" value="<?php if(!empty($nap_start_time_default)) { print $nap_start_time_default; }?>" >
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="nap_end_time">昼寝終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_end_time" id="nap_end_time" value="<?php if(!empty($nap_end_time_default)) { print $nap_end_time_default; }?>">
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
    ?>

    <!-- 青信号 -->
    <p>0：できていない</p>
    <p>1：少しできてない</p>
    <p>2：普通</p>
    <p>3：少し出来てる</p>
    <p>4：出来てる</p>
    <p>ー:やってない(判定できない)</p>

    <?php
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

            if($rec['color'] == 0){
                $dsn3 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user3 = 'root';
                $password3 = '';
                $dbh3 = new PDO($dsn3, $user3, $password3);
                $dbh3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $sql3 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
                $data3 = [];
                $data3[] = $monitoring_id;
                $data3[] = $rec['id'];
                $stmt3 = $dbh3 -> prepare($sql3);
                $stmt3 -> execute($data3);

                $dbh3 = null;
                $rec3 = $stmt3->fetch(PDO::FETCH_ASSOC);

                $blue_id = "";
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
                <option value="" <?php if(!isset($rec3['condition_level']) || is_null($rec3['condition_level'])){ ?> selected <?php } ?> >--選択して下さい--</option>
                <option value="0" <?php if(isset($rec3['condition_level'])){ ?> selected <?php } ?>>0</option>
                <option value="1" <?php if(isset($rec3['condition_level']) && $rec3['condition_level'] == 1){ ?> selected <?php } ?>>1</option>
                <option value="2" <?php if(isset($rec3['condition_level']) && $rec3['condition_level'] == 2){ ?> selected <?php } ?>>2</option>
                <option value="3" <?php if(isset($rec3['condition_level']) && $rec3['condition_level'] == 3){ ?> selected <?php } ?>>3</option>
                <option value="4" <?php if(isset($rec3['condition_level']) && $rec3['condition_level'] == 4){ ?> selected <?php } ?>>4</option>
                <option value="5" <?php if(isset($rec3['condition_level']) && $rec3['condition_level'] == 5){ ?> selected <?php } ?>>-</option>
            </select>
            <br>
            <br>
            <?php } 
        } ?>
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
                $dsn4 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user4 = 'root';
                $password4 = '';
                $dbh4 = new PDO($dsn4, $user4, $password4);
                $dbh4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                $sql4 = 'SELECT id, condition_level FROM condition_levels WHERE monitoring_id = ? AND condition_id = ?';
                $data4 = [];
                $data4[] = $monitoring_id;
                $data4[] = $rec['id'];
                $stmt4 = $dbh4 -> prepare($sql4);
                $stmt4 -> execute($data4);

                $dbh4 = null;
                $rec4 = $stmt4->fetch(PDO::FETCH_ASSOC);
            
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
                <option value="" <?php if(!isset($rec4['condition_level']) || is_null($rec4['condition_level'])){ ?> selected <?php } ?> >--選択して下さい--</option>
                <option value="0" <?php if(isset($rec4['condition_level'])){ ?> selected <?php } ?>>0</option>
                <option value="1" <?php if(isset($rec4['condition_level']) && $rec4['condition_level'] == 1){ ?> selected <?php } ?>>1</option>
                <option value="2" <?php if(isset($rec4['condition_level']) && $rec4['condition_level'] == 2){ ?> selected <?php } ?>>2</option>
                <option value="3" <?php if(isset($rec4['condition_level']) && $rec4['condition_level'] == 3){ ?> selected <?php } ?>>3</option>
                <option value="4" <?php if(isset($rec4['condition_level']) && $rec4['condition_level'] == 4){ ?> selected <?php } ?>>4</option>
            </select>
            <br>
            <br>
            <?php }
        }
    ?>

    <input type="button" value="一括(0)">
    <br><br><br><br>

    <h5>
    <label for="weather">天気</label>
    </h5> 
    <select name="weather" id="weather">
        <?php foreach ($weather_list as $v => $value) : ?>
            <option value="<?= $v ?>" <?php if($weather_default == $v){ ?> selected <?php } ?> ><?= $value ?></option>
        <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>出来事</h5>
    <div class="row">
        <div class="col-3">
            <input type="text" name="event1" value=<?= $event1_default ?> >
        </div>
        <div class="col-3">
            <input type="text" name="event2" value=<?= $event2_default ?>>
        </div>
        <div class="col-3">
            <input type="text" name="event3" value=<?= $event3_default ?>>
        </div>
    </div>
    <br>
    <br>

    <h5>
    <label for="notice">気づいたこと</label>
    </h5>
    <textarea name="notice" id="notice"><?php print $notice_default; ?></textarea>
    <br>
    <br>

    <input type="submit" value="確定">
    <br>
    <br>
    </form>

</div>

</body>
</html>