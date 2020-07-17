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
    <form method="post" action="condition.php">
    <button type="button" onclick="history.back()">元に戻る</button>
    <br><br>

    <input type="date" name="registration_date" value="<?= $_GET["date"] ?>">

    <!-- 睡眠記入欄 -->
    <h2>睡眠</h2>
    <?php
    $sleep_start =  $_GET["date"] . "T00:00";
    $sleep_end = $_GET["date"] . "T08:00";
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