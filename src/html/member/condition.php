<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>体調・精神信号</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>体調・精神信号、行動指針、(追加項目)</h1>
    </div>
    <br>
    <h5>
    体調・精神信号
    </h5>
    <br>

    <h5>
    行動指針
    </h5>
    <br>

    <h5>
    追加項目
    </h5>
    <form method="post" action="selected_screen.php">
    <button onclick="location.href='./selected_screen.php'">キャンセル</button>
    <p>※追加項目を記入するのがしんどい時にキャンセル出来ます。</p>
    <br>
    <br>

    <p>0：体調異常なし</p>
    <p>1：変化はあるけど、体調に関わるほどではない</p>
    <p>2：体調にちょっと関わる</p>
    <p>3：体調に関わる</p>
    <p>4：ひどいほど出てる</p>
    <br>
    <br>

    <h3>
    追加黄
    </h3>
    <br>

    <?php
    // 信号リスト
    $signal_list = [
        0 => '0', 1 => '1', 2 => '2', 3 => '3', 4 => '4',
    ];
    ?>

    <h5>
    <label for="sweat">頭に冷や汗をかく</label>
    </h5>
    <select name="sweat" id="sweat">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="poverty">貧乏ゆすり</label>
    </h5>
    <select name="poverty" id="poverty">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="hair">髪の毛のセットが面倒くなる</label>
    </h5>
    <select name="hair" id="hair">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>
    <br>
    <br>
    <br>


    <h3>
    追加赤
    </h3>
    <br>
    
    <h5>
    <label for="headache">頭痛</label>
    </h5>
    <select name="headache" id="headache">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="nausea">吐き気</label>
    </h5>
    <select name="nausea" id="nausea">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="bow">ボーとする</label>
    </h5>
    <select name="bow" id="bow">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <h5>
    <label for="heart">心臓が痛い</label>
    </h5>
    <select name="heart" id="heart">
        <option value="" selected>--選択して下さい--</option>
    <?php foreach ($signal_list as $v) : ?>
        <option value="<?= $v ?>"><?= $v ?></option>
    <?php endforeach ?>
    </select>
    <br>
    <br>

    <input type="button" value="一括(0)">
    <br><br><br><br>

    <input type="submit" value="確定">
    <br>
    <br>
    </form>






</div>

</body>
</html>