<?php

if(isset($_POST['entries'])) {
    header('Location: entries.php');
    exit();
}

if(isset($_POST['edit'])) {
    header('Location: edit_calendar.php');
    exit();
}

if(isset($_POST['list'])) {
    header('Location: list.php');
    exit();
}

if(isset($_POST['search'])) {
    header('Location: detail_search.php');
    exit();
}

if(isset($_POST['delete'])) {
    header('Location: deleting_calendar.php');
    exit();
}

if(isset($_POST['setting'])) {
    header('Location: setting/set.php');
    exit();
}

?>



