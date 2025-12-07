<?php
include_once('includes/header.php');

if (isset($_REQUEST['page'])) {
    if (empty($_REQUEST['page'])) {
        include_once('pages/home.php');
    } else {
        include_once('pages/' . $_REQUEST['page'] . '.php');
    }
} else {
    include_once('pages/home.php');
}

include_once('includes/footer.php');
?>