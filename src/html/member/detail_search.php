<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細検索</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>詳細検索</h1>
    </div>
    <br>
    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面へ</button>

    <form method="post" action="search_result.php">
    <br><br>
    
    <h5>
    <label for="weekday">曜日</label>
    <select name="weekday" id="weekday">
        <option value="" selected>　　</option>
        <option value="0">月</option>
        <option value="1">火</option>
        <option value="2">水</option>
        <option value="3">木</option>
        <option value="4">金</option>
        <option value="5">土</option>
        <option value="6">日</option>
    </select>
    </h5><br>

    <h5>
        <label for="start_day">日付</label>
        <input type="datetime-local" name="start_day" id="start_day">
        ～
        <input type="datetime-local" name="start_day" id="start_day">
    </h5><br><br>
    
    <h5>
        <label for="start_to_sleep">睡眠開始時間</label>
        <input type="time" name="start_to_sleep" id="start_to_sleep">
    </h5><br>

    <h5>
        <label for="end_to_sleep">睡眠終了時間</label>
        <input type="time" name="end_to_sleep" id="end_to_sleep">
    </h5><br>

    <h5>
        <label for="sleep_total">睡眠合計時間</label>
        <input type="time" name="sleep_total" id="sleep_total">
        <select name="sleep_up_down" id="sleep_up_down">
            <option value="" selected>　　</option>
            <option value="0">以下</option>
            <option value="1">以上</option>
        </select>
    </h5><br>

    <h5>
        <label for="sound_sleep">朝起きた時の熟睡度</label>
        <select name="sound_sleep" id="sound_sleep">
            <option value="" selected>　　</option>
            <option value="0">〇</option>
            <option value="1">✕</option>
            <option value="2">△</option>
        </select>
    </h5><br>
    
    <h5>
        <label for="nap_total">昼寝合計時間</label>
        <input type="time" name="nap_total" id="nap_total">
        <select name="nap_up_down" id="nap_up_down">
            <option value="" selected>　　</option>
            <option value="0">以下</option>
            <option value="1">以上</option>
        </select>
    </h5><br><br>

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
    
    <h2>青信号</h2>
    <br>

    <!-- 青信号 -->
    <p>0：できていない</p>
    <p>1：少しできてない</p>
    <p>2：普通</p>
    <p>3：少し出来てる</p>
    <p>4：出来てる</p>
    <p>ー:やってない(判定できない)</p>

    <h5>
    <label for="blue_signal">青信号全体</label>
    <select name="blue_signal" id="blue_signal">
        <option value="" selected>　　</option>
        <option value="-">-</option>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="TV">TV(漫画)が楽しめている</label>
    <select name="TV" id="TV">
        <option value="" selected>　　</option>
        <option value="-">-</option>
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="book">本が３０分読める</label>
    <select name="book" id="book">
        <option value="" selected>　　</option>
        <option value="-">-</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    </h5>
    <br>
    <br>


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
    <label for="yellow_signal">黄信号全体</label>
    <select name="yellow_signal" id="yellow_signal">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="yellow_up_down" id="yellow_up_down    ">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>
    
    <h5>
    <label for="sleepy">眠い。欠伸する。</label>
    <select name="sleepy" id="sleepy">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="sleepy_up_down" id="sleepy_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="motivation">やる気がない</label>
    <select name="motivation" id="motivation">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="motivation_up_down" id="motivation_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="loose">金遣いがルーズになる</label>
    <select name="loose" id="loose">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="loose_up_down" id="loose_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="appetite">食欲が異常に湧く</label>
    <select name="appetite" id="appetite">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="appetite_up_down" id="appetite_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="smile">ニヤニヤが止まらない</label>
    <select name="smile" id="smile">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="smile_up_down" id="smile_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="frustration">イライラ・モヤモヤ</label>
    <select name="frustration" id="frustration">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="frustration_up_down" id="frustration_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>

    <h5>
    <label for="auditory_hallucination">幻聴、首のそわそわ</label>
    <select name="auditory_hallucination" id="auditory_hallucination">
        <option value="" selected>　　</option>
        <?php foreach ($signal_list as $v) : ?>
            <option value="<?= $v ?>"><?= $v ?></option>
        <?php endforeach ?>
    </select>
    <select name="hallucination_up_down" id="hallucination_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br>
    
    <h5>
    <label for="condition">体調・精神信号</label>
    <select name="condition" id="condition">
        <option value="" selected>　　</option>
        <option value="0">青</option>
        <option value="1">緑</option>
        <option value="2">黄</option>
        <option value="3">橙</option>
        <option value="4">赤</option>
        <option value="5">黒</option>
    </select>
    <select name="condition_up_down" id="condition_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
    <br><br>

    <h5>
    <label for="weather">天気</label>
    <select name="weather" id="weather">
        <option value="" selected>　　</option>
        <?php foreach ($weather as $v) : ?>
            <option value="<?= $v ?>"><?= $v ?></option>
        <?php endforeach ?>
    </select>
    </h5>
    <br>

    <h5>
    <label for="event">出来事</label>
    <input type="text" name="event">
    </h5>
    <br>

    <input type="submit" value="検索">
    <br><br><br>
    </form>

</div>

</body>
</html>