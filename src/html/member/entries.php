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
    <button type="button" onclick="history.back()">元に戻る</button>
    <br><br>

    <?php
    if(!empty($_POST)){
    require_once('../common.php');
    $post = sanitize($_POST);
    }

    if(isset($post)) {
        $registration_date = $post['registration_date'];
        $sleep_start_time = $post['sleep_start_time'];
        $sleep_end_time = $post['sleep_end_time'];
        $sound_sleep = $post['sound_sleep'];
        $nap = $post['nap'];
        $nap_start_time = $post['nap_start_time'];
        $nap_end_time = $post['nap_end_time'];
        $weather = $post['weather'];
        $event1 = $post['event1'];
        $event2 = $post['event2'];
        $event3 = $post['event3'];
        $notice = $post['notice'];

        $ok_flag = true;

        if(strlen($event1) > 100 || strlen($event2) > 100 || strlen($event3) > 100) {
            print "✓　恐れ⼊りますが、出来事は100⽂字以内でご⼊⼒ください。<br>";
            $ok_flag = false;
        }

        if(strlen($notice) > 1000) {
            print "✓　恐れ⼊りますが、気づいたことは1000⽂字以内でご⼊⼒ください。<br>";
            $ok_flag = false;
        }

        // エラーがない場合、SQLに作業登録する
        if($ok_flag == true) {
            $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
            $user = 'root';
            $password = '';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'INSERT INTO monitoring(user_id, entries_date, sleep_start_time, sleep_end_time, sound_sleep, nap, nap_start_time, nap_end_time, weather, event1, event2, event3, notice) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $data[] = 0;
            $data[] = $registration_date;
            $data[] = $sleep_end_time;
            $data[] = $sleep_end_time;
            $data[] = $sound_sleep;
            $data[] = $nap;
            $data[] = $nap_start_time;
            $data[] = $nap_end_time;
            $data[] = $weather;
            $data[] = $event1;
            $data[] = $event2;
            $data[] = $event3;
            $data[] = $notice;

            $stmt -> execute($data);
        
            $dbh = null;

                $dsn6 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user6 = 'root';
                $password6 = '';
                $dbh6 = new PDO($dsn6, $user6, $password6);
                $dbh6->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $sql6 = "SELECT id FROM monitoring WHERE entries_date = ?";
                $data6 = [];
                $data6[] = $registration_date;
                $stmt6 = $dbh6 -> prepare($sql6);
                $stmt6 -> execute($data6);

                $dbh6 = null;

                $rec6 = $stmt6->fetch(PDO::FETCH_ASSOC);
                $condition_id = $rec6['id'];

                $dsn5 = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                $user5 = 'root';
                $password5 = '';
                $dbh5 = new PDO($dsn5, $user5, $password5);
                $dbh5->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                $sql5 = 'SELECT id, display_unnecessary FROM physical_condition_items WHERE 1';
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
                    $monitoring_id = $rec5['id'];
                    if(!empty($post[$monitoring_id])) {
                        
                        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
                        $user = 'root';
                        $password = '';
                        $dbh = new PDO($dsn, $user, $password);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql2 = 'INSERT INTO condition_levels(monitoring_id, condition_id, condition_level) VALUES(?,?,?)';
                        $stmt2 = $dbh -> prepare($sql2);
                        $data2 = [];
                        $data2[] = $rec5['id'];
                        $data2[] = $condition_id;
                        $data2[] = $post[$monitoring_id];

                        $stmt2 -> execute($data2);

                        $dbh = null;

                        // header('Location: condition.php');
                        // exit();
                    }
                }
            }
        }
    ?>

    <form method="post" action="entries.php">
    <br><br>

    <input type="date" name="registration_date" value="<?= date('Y-m-d') ?>">
    <input type="button" value="前日">

    <!-- 睡眠記入欄 -->
    <h2>睡眠</h2>
    <?php
    $date = new DateTime('now', new \DateTimeZone('Asia/Tokyo'));
    $days = $date->format('d');
    $year = $date->format('Y');
    $month = $date->format('m');
    $to = new DateTime();
    $daytime = $to -> format("{$year}-{$month}-{$days}");
    $sleep_start =  $daytime . "T00:00";
    $sleep_end = $daytime . "T08:00";
    ?>
    <div class="row">
        <div class="col-2"><label for="sleep_start_time">睡眠開始時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="sleep_start_time" id="sleep_start_time" value="<?= $sleep_start ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="sleep_end_time">睡眠終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="sleep_end_time" id="sleep_end_time" value="<?= $sleep_end ?>">
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">朝起きた時の熟睡感</div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="yes_sleep" value="0">
            <label for="yes_sleep">〇：ある</label>
        </div>
        <div class="col-2">
            <input type="radio" name="sound_sleep" id="no_sleep" value="1">
            <label for="no_sleep">✕：ない</label>
        </div>
        <div class="col-4">
            <input type="radio" name="sound_sleep" id="not_know_sleep" value="2">
            <label for="not_know_sleep">△：どちらともいえない</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2">昼寝した？？</div>
        <div class="col-2">
            <input type="radio" name="nap" id="yes_nap" value="0">
            <label for="yes_nap">〇：はい</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="no_nap" value="1">
            <label for="no_nap">✕：いいえ</label>
        </div>
        <div class="col-2">
            <input type="radio" name="nap" id="not_know_nap" value="2">
            <label for="not_know_nap">？：忘れた</label>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-2"><label for="nap_start_time">昼寝開始時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_start_time" id="nap_start_time">
        </div>
    </div>
    <div class="row">
        <div class="col-2"><label for="nap_end_time">昼寝終了時間</label></div>
        <div class="col-2">
            <input type="datetime-local" name="nap_end_time" id="nap_end_time">
        </div>
    </div>
    <br>
    <br>

    <h2>青信号</h2>
    <br>

    <?php
    // 信号リスト
    $signal_list = array(
        '1' => '0', '2' => '1', '3' => '2', '4' => '3', '5' => '4',
    );

    // 天気
    $weather = array(
        '0' => '晴れ', '1' => '晴れ時々曇り', '2' => '晴れ時々雨', '3' => '晴れのち曇り', '4' => '晴れのち雨',
        '5' => '雨', '6' => '雨時々晴れ', '7' => '雨時々曇り', '8' => '雨のち晴れ', '9' => '雨のち曇り',
        '10' => '曇り', '11' => '曇り時々晴れ', '12' => '曇り時々雨', '13' => '曇りのち晴れ', '14' => '曇りのち雨',  
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
        ?>
                <h5>
                <input type="hidden" name="id" value="<?= $rec['id']; ?>">
                <label for="<?= $rec['id']; ?>"><?php print $rec['item']; ?></label>
                </h5>
                <select name="<?= $rec['id']; ?>" id="<?= $rec['id']; ?>">
                    <option value="" selected>--選択して下さい--</option>
                    <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>"><?= $value ?></option>
                    <?php endforeach ?>
                    <option value="6">-</option>
                </select>
                <br>
                <br>
                <?php } 
            } ?>
            <br><br>

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
                    ?>
                    <h5>
                    <label for="<?= $rec2['id']; ?>"><?php print $rec2['item']; ?></label>
                    </h5>
                    <select name="<?= $rec2['id']; ?>" id="<?= $rec2['id']; ?>">
                        <option value="" selected>--選択して下さい--</option>
                    <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>"><?= $value ?></option>
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
        <option value="" selected>--選択して下さい--</option>
        <?php foreach ($weather as $v => $value) : ?>
            <option value="<?= $v ?>"><?= $value ?></option>
        <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>出来事</h5>
    <div class="row">
        <div class="col-3">
            <input type="text" name="event1">
        </div>
        <div class="col-3">
            <input type="text" name="event2">
        </div>
        <div class="col-3">
            <input type="text" name="event3">
        </div>
    </div>
    <br>
    <br>

    <h5>
    <label for="notice">気づいたこと</label>
    </h5>
    <textarea name="notice" id="notice"></textarea>
    <br>
    <br>

    <input type="submit" value="確定">
    <br>
    <br>
    </form>

</div>

</body>
</html>