<?php

include_once('includes/header.php');

if (!isset($_REQUEST['page']) || $_REQUEST['page'] != 'login' && $_REQUEST['page'] != 'registor' && $_REQUEST['page'] != 'forgot') {
  include_once('includes/navbar.php');
}

?>

<?php
 if (isset($_REQUEST['page'])) {
  if (empty($_REQUEST['page'])) {
      include_once('pages/home.php');
  } else {
      include_once('pages/' . $_REQUEST['page'] . '.php');
  }
} else {
  include_once('pages/home.php');
}

?>

<?php
include_once('includes/menu.php');
include_once('includes/footer.php');
?>