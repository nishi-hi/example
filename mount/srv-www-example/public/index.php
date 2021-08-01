<?php
  // If file or directory does not exist, all requests arrive this script.
  require_once('../script/controller.php');
  // Controller reads request uri, then call suitable functions.
  $controller = new Controller();
  $controller->route();
?>
