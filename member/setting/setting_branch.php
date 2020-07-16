<?php 

if(isset($_POST['signal'])) {
    header('Location: signal/signal.php');
    exit();
}

if(isset($_POST['act'])) {
    header('Location: activity.php');
    exit();
}

if(isset($_POST['short_name'])) {
    header('Location: short_name.php');
    exit();
}

if(isset($_POST['event'])) {
    header('Location: event.php');
    exit();
}

?>