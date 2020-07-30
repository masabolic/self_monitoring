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

    <?php
        $week = array("日", "月", "火", "水", "木", "金", "土");
        $sound = array("", "〇", "✕", "△");
        $weather_list = array(
            '0' => '', '1' => '晴れ', '2' => '晴れ時々曇り', '3' => '晴れ時々雨', '4' => '晴れのち曇り',
            '5' => '晴れのち雨', '6' => '雨', '7' => '雨時々晴れ', '8' => '雨時々曇り', '9' => '雨のち晴れ', '10' => '雨のち曇り',
            '11' => '曇り', '12' => '曇り時々晴れ', '13' => '曇り時々雨', '14' => '曇りのち晴れ', '15' => '曇りのち雨',
        );
    ?>
    <h5>
    <label for="weekday">曜日</label>
    <select name="weekday" id="weekday">
    <option value="" selected>　　</option>
        <?php foreach($week as $w => $k) { ?>
            <option value=<?= $w ?> ><?php print $k ?></option>
        <?php } ?>
    </select>
    </h5><br>

    <h5>
        <label for="start_day">日付</label>
        <input type="date" name="start_day" id="start_day">
        ～
        <input type="date" name="end_day">
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
            <?php foreach($sound as $s => $d) { ?>
                <option value=<?= $s ?> ><?php print $d ?></option>
            <?php } ?>
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
    $signal_list = array(
        '1' => '1', '2' => '2', '3' => '3', '4' => '4',
    );

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
            <option value="0" <?php if(isset($post[$signal_list]) && is_numeric($post[$signal_list])) {  ?> selected <?php } ?> >0</option>
            <?php foreach ($signal_list as $v => $value) : ?>
                <option value="<?= $v ?>" <?php if(isset($post[$signal_list]) && $post[$signal_list] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
            <?php endforeach ?>
            <option value="5">-</option>
        </select>
    </h5>
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
    
            $item_id = 'signal' . $rec['id'];
            ?>
            <h5>
                <input type="hidden" name="id" value="<?= $rec['id']; ?>">
                <label for="<?= $item_id; ?>"><?php print $rec['item']; ?></label>
                <select name="<?= $item_id; ?>" id="<?= $item_id; ?>">
                    <option value="" selected >　　</option>
                    <option value="0" <?php if(isset($post[$item_id]) && is_numeric($post[$item_id])) {  ?> selected <?php } ?> >0</option>
                    <?php foreach ($signal_list as $v => $value) : ?>
                        <option value="<?= $v ?>" <?php if(isset($post[$item_id]) && $post[$item_id] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                    <?php endforeach ?>
                    <option value="5">-</option>
                </select>
            </h5>
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
    <br>

    <h5>
    <label for="yellow_signal">黄信号全体</label>
    <select name="yellow_signal" id="yellow_signal">
        <option value="" selected>　　</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <select name="yellow_up_down" id="yellow_up_down">
        <option value="" selected>　　</option>
        <option value="0">以下</option>
        <option value="1">以上</option>
    </select>
    </h5>
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
                $yellow_item_id = 'signal' . $rec['id'];
                $up_down = 'up_down' . $rec['id'];
            ?>
            <h5>
            <input type="hidden" name="id" value="<?= $rec['id']; ?>">
            <label for="<?= $yellow_item_id; ?>"><?php print $rec['item']; ?></label>
            <select name="<?= $yellow_item_id; ?>" id="<?= $yellow_item_id; ?>">
                <option value="" selected >　　</option>
                <option value="0" <?php if(isset($post[$yellow_item_id]) && is_numeric($post[$yellow_item_id])) {  ?> selected <?php } ?> >0</option>
                <?php foreach ($signal_list as $v => $value) : ?>
                    <option value="<?= $v ?>" <?php if(isset($post[$yellow_item_id]) && $post[$yellow_item_id] == $v ) { ?> selected <?php } ?> ><?= $value ?></option>
                <?php endforeach ?>
            </select>
            <select name="<?= $up_down ?>">
                <option value="" selected>　　</option>
                <option value="0">以下</option>
                <option value="1">以上</option>
            </select>
            </h5>
            <br>
            <?php }
        } ?>    
    
    <h5>
    <label for="condition">体調・精神信号</label>
    <select name="condition" id="condition">
        <option value="" selected>　</option>
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
        <?php foreach ($weather_list as $W => $r) : ?>
            <option value="<?= $w ?>"><?php print $r; ?></option>
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