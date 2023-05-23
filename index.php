

<?php
$controller = $_GET['controller'] ?? 'index';
$globalError = null;
$routes = require 'router.php';

try {
  include_once $routes[$controller] ?? die('404');
} catch (PDOException $exception) {
  echo "Проблемы с БД";
} catch (Throwable $exception) {
  $globalError = $exception->getMessage();
}







