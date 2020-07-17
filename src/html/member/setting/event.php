<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出来事</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/monitor.css">
</head>
<body>
<div class="container">
    <div class="col-8">
        <h1>週間の出来事</h1>
    </div>
    <br>
    <form method="post" action="../selected_screen.php">
    <table border="1">
        <tr>
            <th>月</th>
            <td><input type="text" name="monday1"></td>
            <td><input type="text" name="monday2"></td>
            <td><input type="text" name="monday3"></td>
        </tr>
        <tr>
            <th>火</th>
            <td><input type="text" name="tuesday1"></td>
            <td><input type="text" name="tuesday2"></td>
            <td><input type="text" name="tuesday3"></td>
        </tr>
        <tr>
            <th>水</th>
            <td><input type="text" name="wednesday1"></td>
            <td><input type="text" name="wednesday2"></td>
            <td><input type="text" name="wednesday3"></td>
        </tr>
        <tr>
            <th>木</th>
            <td><input type="text" name="thursday1"></td>
            <td><input type="text" name="thursday2"></td>
            <td><input type="text" name="thursday3"></td>
        </tr>
        <tr>
            <th>金</th>
            <td><input type="text" name="friday1"></td>
            <td><input type="text" name="friday2"></td>
            <td><input type="text" name="friday3"></td>
        </tr>
        <tr>
            <th>土</th>
            <td><input type="text" name="saturday1"></td>
            <td><input type="text" name="saturday2"></td>
            <td><input type="text" name="saturday3"></td>
        </tr>
        <tr>
            <th>日</th>
            <td><input type="text" name="sunday1"></td>
            <td><input type="text" name="sunday2"></td>
            <td><input type="text" name="sunday3"></td>
        </tr>
    </table>
</div>

</body>
</html>