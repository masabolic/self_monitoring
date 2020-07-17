<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>選択画面</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>選択画面</h1>
    </div>
    <br>
    <form method="post" action="selected_branch.php">
        <div class="row">
            <div class="col-2">
                <input type="submit" name="entries" value="記入">
            </div>
            <div class="col-2">
                <input type="submit" name="edit" value="編集">
            </div>
            <div class="col-2">
                <input type="submit" name="list" value="一覧">
            </div>
            <div class="col-2">
                <input type="submit" name="search" value="検索">
            </div>
            <div class="col-2">
                <input type="submit" name="delete" value="削除">
            </div>
            <div class="col-2">
                <input type="submit" name="setting" value="設定">
            </div>
        </div>
    </form>
</div>
</body>
</html>