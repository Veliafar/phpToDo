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
$pageHeader = 'Задачи';
$pageTitle = $pageHeader . " | " . $commonPageTitle;

include_once "controller/LoginController.php";

$tasks = [];

$userID = $_SESSION['user']->getID();
$userProvider = new UserProvider($pdo);
$taskProvider = new TaskProvider($pdo, $userID, $userProvider);

$taskStatuses = TaskUtils::$taskStatuses;
$taskStatusesTranslate = TaskUtils::$taskStatusesTranslate;
$taskStatusesColor = TaskUtils::$taskStatusesColor;

$taskItemIndex = 0;
$taskAnimationDelay = '0.14s *';


//if (isset($_GET['changeTaskDone'])) {
//  $taskProvider->changeTaskDone($_GET['changeTaskDone'], $_GET['isDone']);
//  strtok($_SERVER["REQUEST_URI"], '?');
//  header("Location: /?controller=tasks");
//  die();
//}

if (isset($_GET['delTask'])) {

  $taskID = $taskProvider->delTask($_GET['delTask']);

  $response = [
    'status' => 'ok',
    'id' => $taskID
  ];

  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  die();
}

$tasks = $taskProvider->getTasks();

include "view/tasks.php";