<?php
require_once "model/User.php";
require_once "model/Task.php";
require_once "model/TaskProvider.php";
require_once "model/UserProvider.php";

session_start();
//
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
$users = $userProvider->getUsersList();

$taskStatuses = TaskUtils::$taskStatuses;
$taskStatusesTranslate = TaskUtils::$taskStatusesTranslate;
$taskStatusesColor = TaskUtils::$taskStatusesColor;

$taskItemIndex = 0;
$taskAnimationDelay = '0.14s *';

$statusValue = '';
$taskText = '';
$taskTargetDate = '';
$assigneeIDValue = '';

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

if (isset($_GET['filterTasks'])) {

  $tasks = $taskProvider->filterTask(
    $userID,
    $_GET['taskText'],
    $_GET['status']
  );

  $response = [
    'status' => 'ok',
    'filter!' => true,
    'get' => $_GET,
    '$tasks' => $taskProvider->getTasks(),
    '$resTasks' => $tasks
  ];

  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  die();
}

if (isset($_GET['refresh'])) {

  $taskProvider->getAllTasks($userID);

  $preparedTasks = [];

  foreach ($taskProvider->getTasks() as &$value) {
    $obj = new stdClass();
    $obj->id = $value->getID();
    $obj->title = $value->getTitle();
    $obj->description = $value->getDescription();
    $obj->ownerID = $value->getOwnerID();
    $obj->dateTarget = $value->getDateTarget();
    $obj->status = $value->getStatus();
    $obj->dateCreate = $value->getDateCreate();
    $obj->dateUpdate = $value->getDateUpdate();

    $obj->assigneeID = $value->getAssignee()->getID();
    $obj->assigneeName = $value->getAssignee()->getName();
    $obj->assigneeUserName = $value->getAssignee()->getUserName();

    $preparedTasks[] = $obj;
  }

  $response = [
    'tasks' => $preparedTasks,
  ];

  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  die();
}

//$tasks = $taskProvider->getTasks();

include "view/tasks.php";