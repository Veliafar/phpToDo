<?php
include_once "model/User.php";
include_once "model/UserProvider.php";

$pdo = include "db.php";

$pdo->exec('CREATE TABLE users (
  id INTEGER NOT NULL PRIMARY KEY,
  name VARCHAR(150),
  username VARCHAR(100),
  password VARCHAR(100)
)');

$user = new User('admin');
$user->setName('Geekbrains PHP');

$userProvider = new UserProvider($pdo);
$userProvider->registerUser($user, '123');


$pdo->exec('CREATE TABLE tasks (
  id INTEGER NOT NULL PRIMARY KEY,
  description VARCHAR(500) NOT NULL,
  isDone INTEGER NOT NULL,
  dateCreate VARCHAR(100),
  dateUpdate VARCHAR(100),
  userID INTEGER NOT NULL
)');