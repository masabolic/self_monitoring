<?php

if(isset($_POST['item_change'])) {
    header('Location: existing_item_change.php');
    exit();
}

if(isset($_POST['new'])) {
    header('Location: new_item_addition.php');
    exit();
}

?>