<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>記入</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>記入</h1>
    </div>
    <br>
    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    <br><br>

    <?php
    if(!empty($_POST)){
    require_once('../common.php');
    $post = sanitize($_POST);
    }

    if(isset($post)) {
        $registration_date = $post['registration_date'];
        $date = new DateTime($post['registration_date']);
        $weekday = (int)$date->format('w');

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
        $spirit_signal_red = 0;
        $spirit_signal_black = 0;

        $ok_flag = true;

        $dsn8 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user8 = 'root';
        $password8 = '';
        $dbh8 = new PDO($dsn8, $user8, $password8);
        $dbh8->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql8 = "SELECT entries_date, is_deleted FROM monitoring WHERE entries_date = ?";
        $data8 = [];
        $data8[] = $registration_date;
        $stmt8 = $dbh8 -> prepare($sql8);
        $stmt8 -> execute($data8);

        $dbh8 = null;
        
        // エラー
        while(true) {
            $rec8 = $stmt8->fetch(PDO::FETCH_ASSOC);
            if($rec8==false){
                break;
            }
            if(isset($rec8['entries_date']) && $rec8['is_deleted'] == 0) {
                print "✓　恐れ⼊りますが、その日は記録されています。編集で記入ください。<br>";
                $ok_flag = false;
            }
        }

            
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
            // monitoringにその日初めての記入
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'INSERT INTO monitoring(user_id, entries_date, weekday, sleep_start_time, sleep_end_time, sleep_sum, sound_sleep, nap, nap_start_time, nap_end_time, nap_sum, weather, event1, event2, event3, notice) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $data[] = 0;
            $data[] = $registration_date;
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

            $stmt -> execute($data);
        
            $dbh = null;
                
                // monitoring記入後にidを取り出す。
                $dsn6 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user6 = 'root';
                $password6 = '';
                $dbh6 = new PDO($dsn6, $user6, $password6);
                $dbh6->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $sql6 = "SELECT id FROM monitoring WHERE entries_date = ? AND is_deleted = ?";
                $data6 = [];
                $data6[] = $registration_date;
                $data6[] = 0;
                $stmt6 = $dbh6 -> prepare($sql6);
                $stmt6 -> execute($data6);

                $dbh6 = null;

                $rec6 = $stmt6->fetch(PDO::FETCH_ASSOC);
                $monitoring_id = $rec6['id'];

                //一週間前の日付を取得
                $date = new DateTime($registration_date, new \DateTimeZone('Asia/Tokyo'));
                $date -> sub(new DateInterval("P1W"));
                $before_week = $date -> format("Y-m-d");

                //一週間分の体調信号を取得し、橙以上の体調を判定
                $dsn9 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user9 = 'root';
                $password9 = '';
                $dbh9 = new PDO($dsn9, $user9, $password9);
                $dbh9->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $sql9 = "SELECT spirit_signal FROM monitoring WHERE entries_date >= ? AND entries_date <= ? AND is_deleted = ?";
                $data9 = [];
                $data9[] = $before_week
                $data9[] = $registration_date;
                $data9[] = 0;
                $stmt9 = $dbh9 -> prepare($sql9);
                $stmt9 -> execute($data9);

                $dbh9 = null;
                while(true) {
                    $rec9 = $stmt9->fetch(PDO::FETCH_ASSOC);
                    if($rec9==false){
                        break;
                    }
                    if($rec9['spirit_signal'] >= 4) {
                        $spirit_signal_black++;
                    }
                    if($rec9['spirit_signal'] >= 3) {
                        $spirit_signal_red += 2;
                    }
                    if($rec9['spirit_signal'] >= 2) {
                        $spirit_signal_red++;
                    }

                }

                // 必要不要と色をループを回して取り出す。
                $dsn5 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user5 = 'root';
                $password5 = '';
                $dbh5 = new PDO($dsn5, $user5, $password5);
                $dbh5->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                $sql5 = 'SELECT id, display_unnecessary, color FROM physical_condition_items WHERE 1';
                $stmt5 = $dbh5 -> prepare($sql5);
                $stmt5 -> execute();

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


                        // ここから！！








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
                            
                        // 体調レベルのSQLを記入する。
                        $dsn2 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                        $user2 = 'root';
                        $password2 = '';
                        $dbh2 = new PDO($dsn2, $user2, $password2);
                        $dbh2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql2 = 'INSERT INTO condition_levels(monitoring_id, condition_id, condition_level) VALUES(?,?,?)';
                        $stmt2 = $dbh2 -> prepare($sql2);
                        $data2 = [];
                        $data2[] = $monitoring_id;
                        $data2[] = $condition_id;
                        $data2[] = $condition_level;

                        $stmt2 -> execute($data2);

                        $dbh2 = null;
                    }
                }
            session_start();
            $_SESSION['spirit_signal'] = $spirit_signal;
            $_SESSION['monitoring_id'] = $monitoring_id;

            header('Location: condition.php');
            exit();
            }
        }
    ?>

    <form method="post" action="entries.php">
    <br><br>

    <input type="date" name="registration_date" value="<?= date('Y-m-d') ?>">
    <input type="button" value="変更">

    <!-- 睡眠記入欄 -->
    <h2>睡眠</h2>
    <?php
    $date = new DateTime('now', new \DateTimeZone('Asia/Tokyo'));
    $daytime = $date -> format("Y-m-d");
    $sleep_start =  $daytime . "T00:00";
    $sleep_end = $daytime . "T08:00";
    ?>
    <div class="row">
        <div class="col-2"><label for="sleep_start_time">睡眠開始時間</label></div>
        <div class="col-2">
    <input type="datetime-local" name="sleep_start_time" id="sleep_start_time" <?php if(isset($sleep_start_time)) { ?> value="<?= $sleep_start_time ?>" <?php }else{ ?> value="<?= $sleep_start ?>" <?php }?> >
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="sleep_end_time">睡眠終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="sleep_end_time" id="sleep_end_time" <?php if(isset($sleep_end_time)) { ?> value="<?= $sleep_end_time ?>" <?php }else{ ?> value="<?= $sleep_end ?>" <?php }?> >
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">朝起きた時の熟睡感</div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="no_answer" value="0" checked="checked">
            <label for="no_answer">未回答</label>
        </div>
        <div class="col-2">
    <input type="radio" name="sound_sleep" id="yes_sleep" value="1" <?php if(isset($sound_sleep) && $sound_sleep == 1) { ?> checked="checked" <?php } ?> >
            <label for="yes_sleep">〇：ある</label>
        </div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="no_sleep" value="2" <?php if(isset($sound_sleep) && $sound_sleep == 2) { ?> checked="checked" <?php } ?> >
            <label for="no_sleep">✕：ない</label>
        </div>
        <div class="col-4">
            <input type="radio" name="sound_sleep" id="not_know_sleep" value="3" <?php if(isset($sound_sleep) && $sound_sleep == 3) { ?> checked="checked" <?php } ?> >
            <label for="not_know_sleep">△：どちらともいえない</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">昼寝した？？</div>
        <div class="col-2">
            <input type="radio" name="nap" id="no_answer_nap" value="0" checked="checked">
            <label for="no_answer">未回答</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="yes_nap" value="1"  <?php if(isset($nap) && $nap == 1) { ?> checked="checked" <?php } ?> >
            <label for="yes_nap">〇：はい</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="no_nap" value="2" <?php if(isset($nap) && $nap == 2) { ?> checked="checked" <?php } ?> >
            <label for="no_nap">✕：いいえ</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="not_know_nap" value="3" <?php if(isset($nap) && $nap == 3) { ?> checked="checked" <?php } ?>>
            <label for="not_know_nap">？：忘れた</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2"><label for="nap_start_time">昼寝開始時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_start_time" id="nap_start_time" <?php if(isset($nap_start_time)) { ?> value="<?= $nap_start_time ?>" <?php } ?>>
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="nap_end_time">昼寝終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_end_time" id="nap_end_time" <?php if(isset($nap_end_time)) { ?> value="<?= $nap_end_time ?>" <?php } ?>>
        </div>
    </div>
    <br>
    <br>

    <h2>青信号</h2>
    <br>

    <?php
    // 信号リスト
    $signal_list = array(
        '1' => '1', '2' => '2', '3' => '3', '4' => '4',
    );

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
    <br>

    <?php
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql3 = 'SELECT id, item, display_unnecessary, color FROM physical_condition_items WHERE 1';
        $stmt3 = $dbh -> prepare($sql3);
        $stmt3 -> execute();

        $dbh = null;

        while(true) {
            $rec = $stmt3->fetch(PDO::FETCH_ASSOC);
            if($rec==false){
                break;
            }
            if($rec['display_unnecessary'] == 1){
                continue;
            }

            if($rec['color'] == 0){
    
            $item_id = $rec['id']
            ?>
            <h5>
            <input type="hidden" name="id" value="<?= $rec['id']; ?>">
            <label for="<?= $item_id; ?>"><?php print $rec['item']; ?></label>
            </h5>
            <select name="<?= $item_id; ?>" id="<?= $item_id; ?>">
                <option value="" selected >--選択して下さい--</option>
                <option value="0" <?php if(isset($post[$item_id]) && is_numeric($post[$item_id])) {  ?> selected <?php } ?> >0</option>
                <?php foreach ($signal_list as $v => $value) : ?>
                    <option value="<?= $v ?>" <?php if(isset($post[$item_id]) && $post[$item_id] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                <?php endforeach ?>
                <option value="5">-</option>
            </select>
            <br>
            <br>
            <?php } 
        } ?>
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
        
        <?php 
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
            $sql4 = 'SELECT id, item, display_unnecessary, color FROM physical_condition_items WHERE 1';
            $stmt4 = $dbh -> prepare($sql4);
            $stmt4 -> execute();

            $dbh = null;
            
            while(true) {
                $rec2 = $stmt4->fetch(PDO::FETCH_ASSOC);
                if($rec2==false){
                    break;
                }
                if($rec2['display_unnecessary'] == 1){
                    continue;
                }

                if($rec2['color'] == 2) {
                    $yellow_item_id = $rec2['id'];
                ?>
                <h5>
                <label for="<?= $yellow_item_id; ?>"><?php print $rec2['item']; ?></label>
                </h5>
                <select name="<?= $yellow_item_id; ?>" id="<?= $yellow_item_id; ?>">
                    <option value="" selected >--選択して下さい--</option>
                    <option value="0" <?php if(isset($post[$yellow_item_id]) && is_numeric($post[$yellow_item_id])) {  ?> selected <?php } ?> >0</option>
                    <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>" <?php if(isset($post[$yellow_item_id]) && $post[$yellow_item_id] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                    <?php endforeach ?>
                </select>
                <br>
                <br>
                <?php }
            } ?>
 
    <input type="button" value="一括(0)">
    <br><br><br><br>

    <h5>
    <label for="weather">天気</label>
    </h5>
    <select name="weather" id="weather">
        <?php foreach ($weather_list as $v => $value) : ?>
            <option value="<?= $v ?>" <?php if(isset($weather)) { if($weather == $v){ ?> selected <?php } } ?> ><?= $value ?></option>
        <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>出来事</h5>
    <div class="row">
        <div class="col-3">
            <input type="text" name="event1" <?php if(isset($event1)) { ?> value=<?= $event1 ?> <?php } ?> >
        </div>
        <div class="col-3">
            <input type="text" name="event2" <?php if(isset($event2)) { ?> value=<?= $event2 ?> <?php } ?> >
        </div>
        <div class="col-3">
            <input type="text" name="event3" <?php if(isset($event3)) { ?> value=<?= $event3 ?> <?php } ?> >
        </div>
    </div>
    <br>
    <br>

    <h5>
    <label for="notice">気づいたこと</label>
    </h5>
    <textarea name="notice" id="notice"><?php if(isset($notice)) { print $notice; } ?></textarea>
    <br>
    <br>

    <input type="submit" value="確定">
    <br>
    <br>
    </form>

</div>

</body>
</html>