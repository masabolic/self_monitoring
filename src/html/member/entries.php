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
        $TV = $post['TV'];
        $book = $post['book'];
        $sleepy = $post['sleepy'];
        $motivation = $post['motivation'];
        $loose = $post['loose'];
        $appetite = $post['appetite'];
        $smile = $post['smile'];
        $frustration = $post['frustration'];
        $auditory_hallucination = $post['auditory_hallucination'];
        $weather = $post['weather'];
        $event1 = $post['event1'];
        $event2 = $post['event2'];
        $event3 = $post['event3'];
        $notice = $post['notice'];

        $ok_flag = true;

        if(strlen($event1) > 100　|| strlen($event2) > 100 || strlen($event3) > 100) {
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

            $sql2 = 'INSERT INTO condition_levels(monitoring_id, condition_id, condition_level) VALUES(?,?,?)';
            $stmt2 = $dbh -> prepare($sql2);
            $data2[] = 0;
            $data2[] = $registration_date;
            $data2[] = $sleep_end_time;
            $data2[] = $sleep_end_time;

            $stmt2 -> execute($data2);

            $dbh = null;

            header('Location: condition.php');
            exit();
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
    $signal_list = [
        0 => '0', 1 => '1', 2 => '2', 3 => '3', 4 => '4',
    ];

    // 天気
    $weather = [
        0 => '晴れ', 1 => '晴れ時々曇り', 2 => '晴れ時々雨', 3 => '晴れのち曇り', 4 => '晴れのち雨',
        5 => '雨', 6 => '雨時々晴れ', 7 => '雨時々曇り', 8 => '雨のち晴れ', 9 => '雨のち曇り',
        10 => '曇り', 11 => '曇り時々晴れ', 12 => '曇り時々雨', 13 => '曇りのち晴れ', 14 => '曇りのち雨',  
    ];
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
    
    
            $sql = 'SELECT id, item, display_unnecessary, color, FROM physical_condition_items WHERE 1';
            $stmt = $dbh -> prepare($sql);
            $stmt -> execute();
        ?>


    <h5>
    <label for="TV">TV(漫画)が楽しめている</label>
    </h5>
    <select name="TV" id="TV">
        <option value="" selected>--選択して下さい--</option>
        <option value="-">-</option>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>
    <br>
    <br>

    <h5>
    <label for="book">本が３０分読める</label>
    </h5>
    <select name="book" id="book">
        <option value="" selected>--選択して下さい--</option>
        <option value="-">-</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>
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
    
    <h5>
    <label for="sleepy">眠い。欠伸する。</label>
    </h5>
    <select name="sleepy" id="sleepy">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="motivation">やる気がない</label>
    </h5>
    <select name="motivation" id="motivation">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="loose">金遣いがルーズになる</label>
    </h5>
    <select name="loose" id="loose">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="appetite">食欲が異常に湧く</label>
    </h5>
    <select name="appetite" id="appetite">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="smile">ニヤニヤが止まらない</label>
    </h5>
    <select name="smile" id="smile">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="frustration">イライラ・モヤモヤ</label>
    </h5>
    <select name="frustration" id="frustration">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="auditory_hallucination">幻聴、首のそわそわ</label>
    </h5>
    <select name="auditory_hallucination" id="auditory_hallucination">
        <option value="" selected>--選択して下さい--</option>
        <?php foreach ($signal_list as $v) : ?>
            <option value="<?= $v ?>"><?= $v ?></option>
        <?php endforeach ?>
    </select>
    <br>
    <br>
    <input type="button" value="一括(0)">
    <br><br><br><br>

    <h5>
    <label for="weather">天気</label>
    </h5>
    <select name="weather" id="weather">
        <option value="" selected>--選択して下さい--</option>
        <?php foreach ($weather as $v) : ?>
            <option value="<?= $v ?>"><?= $v ?></option>
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