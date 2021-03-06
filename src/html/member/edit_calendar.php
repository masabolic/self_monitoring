<?php
                $M = 0;
                // 今月から比べて、月の違いがあるかを送ってくる。
                if(isset($_GET["tsuki"]) && is_numeric($_GET["tsuki"]) && is_int( (int) $_GET["tsuki"])) {
                    $M = (int) $_GET["tsuki"];
                }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編集カレンダー</title>
    <link rel="stylesheet" href="../css/monitor.css">
    <link rel="stylesheet" href="../css/monitor_calender.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

</head>
<body>
<div class="container col-md-4 offset-md-4">
    <div class="col-8">
        <h1>編集カレンダー</h1>
    </div>
    <br>
    <button type="button" onclick="location.href='./selected_screen.php'">最初の画面へ</button>
    <br><br>
    <table border="1">
        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>
        <tr>
                <?php
                require_once('../common.php');
                $date = new DateTime('now', new \DateTimeZone('Asia/Tokyo'));
                // 送られてた月の差分によって今月からの差を出す。
                if($M >= 0) {
                    $date -> add(new DateInterval("P{$M}M"));
                }else{
                    $minus = 0 - $M;
                    $date -> sub(new DateInterval("P{$minus}M"));
                }
                $days = $date->format('t');
                $year = $date->format('Y');
                $month = $date->format('m');
                print $date -> format('Y年n月');
                // その月の１日目を出し、カレンダーを作る。
                $month1day = new DateTime("{$year}/{$month}/01");
                $to = new DateTime();
                // 日付にハイパーリンクをつける為のもの
                $yearmonth = $to -> format("{$year}-{$month}-");
                // その月の一日目の曜日を出す。
                $weekDay01= $month1day->format('w');
                // 一日目までの配列に空白を入れる。
                for($i = 0; $i < $weekDay01; $i++){
                    ?> <td></td> <?php
                }

                // その月の最終日とその曜日を出す。
                $monthLastDay = new DateTime("{$year}/{$month}/{$days}");
                $weekLastDay = $monthLastDay->format('w');

                for($i = 1; $i <= 8; $i++){
                    if(($weekDay01 + $i) == 8){
                    $hi = $i;
                    break;
                    }
                    // 日付が二桁じゃないとentries_dateに対応しない為
                    $special_date = $yearmonth."0".$i;

                    $dbh = dbconnect();
            
                    $sql = 'SELECT entries_date, spirit_signal, is_deleted FROM monitoring WHERE entries_date = ?';
                    $data = [];
                    $data[] = $special_date;
                    $stmt = $dbh -> prepare($sql);
                    $stmt -> execute($data);

                    $dbh = null;
                    $special_roop = 0;

                    // is_deletedで消したものがある場合の為のループ
                    while(true) {
                        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($rec==false){
                            // ハイパーリンクを作ってない場合
                            if($special_roop == 0) {
                                ?> <td> <?php print $i ?></td><?php
                            }
                            break;
                        }
                        if($rec['is_deleted'] == 1){
                            continue;
                        }

                        ?> <td <?php if($rec['spirit_signal'] == 0){ ?> style="background-color:#00FFFF;" <?php }elseif($rec['spirit_signal'] == 1){ ?> style="background-color:#00FF00;" <?php }elseif($rec['spirit_signal'] == 2){ ?> style="background-color:#FFFF00;" <?php }elseif($rec['spirit_signal'] == 3){ ?> style="background-color:#FFA500;" <?php }elseif($rec['spirit_signal'] == 4){ ?> style="background-color:#FF6347;" <?php }elseif($rec['spirit_signal'] == 5){ ?> style="background-color:#BBBBBB;" <?php } ?> >
                        <a href="./edit.php?date=<?= $special_date; ?>"><?php print $i ?></a></td> <?php
                        $special_roop++;
                    }
                }
                ?> </tr>
                <tr> <?php
                // 7日たったら、次の行にいくようにする為、カウントする。初期値。
                $count = 0;
                for($i = $hi; $i <= $days; $i++){
                    $count++;
                    // 日付が二桁じゃないとentries_dateに対応しない為
                    if($i < 10){
                        $special_date = $yearmonth."0".$i;
                    }else{
                        $special_date = $yearmonth.$i;
                    }

                    $dbh = dbconnect();
            
                    $sql = 'SELECT entries_date, spirit_signal, is_deleted FROM monitoring WHERE entries_date = ?';
                    $data = [];
                    $data[] = $special_date;
                    $stmt = $dbh -> prepare($sql);
                    $stmt -> execute($data);

                    $dbh = null;
                    $special_roop = 0;

                    // is_deletedで消したものがある場合の為のループ
                    while(true) {
                        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($rec==false){
                            // ハイパーリンクを作ってない場合
                            if($special_roop == 0) {
                                ?> <td> <?php print $i ?></td><?php
                            }
                            break;
                        }
                        if($rec['is_deleted'] == 1){
                            continue;
                        }

                    ?> <td <?php if($rec['spirit_signal'] == 0){ ?> style="background-color:#00FFFF;" <?php }elseif($rec['spirit_signal'] == 1){ ?> style="background-color:#00FF00;" <?php }elseif($rec['spirit_signal'] == 2){ ?> style="background-color:#FFFF00;" <?php }elseif($rec['spirit_signal'] == 3){ ?> style="background-color:#FFA500;" <?php }elseif($rec['spirit_signal'] == 4){ ?> style="background-color:#FF6347;" <?php }elseif($rec['spirit_signal'] == 5){ ?> style="background-color:#BBBBBB;" <?php } ?> >
                        <a href="./edit.php?date=<?= $special_date; ?>"><?php print $i?></a></td> <?php
                        $special_roop++;
                    }

                    // 7日たったら、次の行にいくようにする
                    if($count == 7){
                        $count = 0;
                        ?> </tr> 
                        <tr> <?php
                    }
                }
                for($i = 0; $i < (6-$weekLastDay); $i++){
                    ?> <td></td> 
                    <?php
                } ?>
                </tr>
    </table>
    <div class = "navi offset-md-1">
    <p><a href="./edit_calendar.php?tsuki=<?= $M - 1; ?>">
    &lt;&lt;前の月へ</a></p>
    <p><a href="./edit_calendar.php">今月へ</a></p>
    <p><a href="./edit_calendar.php?tsuki=<?= $M + 1; ?>">
    次の月へ&gt;&gt;</a></p>

    </div>
</div>
</body>
</html>