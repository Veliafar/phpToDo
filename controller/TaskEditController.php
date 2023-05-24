<?php
require_once "model/User.php";
require_once "model/Task.php";
require_once "model/TaskProvider.php";
require_once "model/UserProvider.php";
session_start();

//echo "<pre>";
//var_dump($_REQUEST);
//var_dump($_SESSION);
//echo "</pre>";

include_once "controller/SharedController.php";
$pdo = require "db.php";
$pageHeader = 'Создание задачи';
$pageTitle = $pageHeader . " | " . $commonPageTitle;

include_once "controller/LoginController.php";

$userID = $_SESSION['user']->getID();
$userProvider = new UserProvider($pdo);
$taskProvider = new TaskProvider($pdo, $userID, $userProvider);
$users = $userProvider->getUsersList();


if (isset($_POST['createTask'])) {
  $taskProvider->addTask(
    $userID,
    $_POST['assigneeID'],
    $_POST['title'],
    $_POST['description'],
    $_POST['dateTarget'],
    $_POST['status'],
  );

  strtok($_SERVER["REQUEST_URI"], '?');
  header("Location: /?controller=tasks");
  die();
}


include "view/taskEdit.php";