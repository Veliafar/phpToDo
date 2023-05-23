<?php
require_once "model/User.php";
session_start();

include_once "controller/SharedController.php";

$pageTitle = $commonPageTitle;
$pageHeader = 'Добро пожаловать';

include_once "controller/LoginController.php";


include "view/index.php";