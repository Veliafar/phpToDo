<?php
require_once 'model/User.php';
require_once 'model/UserProvider.php';

session_start();
$pdo = require "db.php";
include_once "controller/SharedController.php";
include_once "exceptions/UserExistException.php";
$pageHeader = 'Регистрация';
$pageTitle = $pageHeader . " | " . $commonPageTitle;
$error = null;


if (isset($_POST['username'], $_POST['password'], $_POST['name'])) {
  ['name' => $name, 'username' => $userName, 'password' => $userPass] = $_POST;
  $userProvider = new UserProvider($pdo);
  $user = new User($userName);
  $user->setName($name);

  try {
    $user->setID($userProvider->registerUser($user, $userPass));
    $_SESSION['user'] = $user;
    header("Location: index.php");
    die();
  } catch (UserExistException $exception) {
    $error = $exception->getMessage();
  }
}

include "view/signin.php";