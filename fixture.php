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
$user->setName('admin');

$userProvider = new UserProvider($pdo);
$userProvider->registerUser($user, '123');


$pdo->exec('CREATE TABLE tasks (
                       "id" INTEGER NOT NULL PRIMARY KEY,
                       "title" VARCHAR(500) NOT NULL,
                       "description" VARCHAR(500) NOT NULL,
                       "status" VARCHAR(55) NULL,
                       "dateCreate" VARCHAR(100) NOT NULL,
                       "dateTarget" VARCHAR(100) NOT NULL,
                       "dateUpdate" VARCHAR(100) NOT NULL,
                       "ownerID" INTEGER NOT NULL,
                       "assigneeID" INTEGER NOT NULL,
                       "attachedFile" BLOB
)');