<?php

$userName = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userName = $user->getUserName();
} else if ($_SERVER["REQUEST_URI"] != '/')  {
  strtok($_SERVER["REQUEST_URI"], '?');
  header("Location: /");
  die();
}
