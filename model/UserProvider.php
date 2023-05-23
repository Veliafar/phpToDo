<?php

class UserProvider
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function registerUser(User $user, string $plainPassword): bool
  {

    $isUserExist = $this->pdo->prepare('SELECT id from users WHERE username = ?');
    $isUserExist->execute([$user->getUserName()]);
    if ($isUserExist->fetch()) {
      throw new UserExistException('Пользователь с таким именем уже есть');
    }

    $statement = $this->pdo->prepare(
      'INSERT INTO users (name, username, password) VALUES (:name, :username, :password)'
    );

    $statement->execute(
      [
        'name' => $user->getName(),
        'username' => $user->getUserName(),
        'password' => $plainPassword
      ]
    );

    return $this->pdo->lastInsertId();
  }

  public function getUserByNameAndPass(string $userName, string $password): ?User
  {
    $statement = $this->pdo->prepare(
      'SELECT id, name, username FROM users WHERE username = :username AND  password = :password LIMIT 1'
    );

    $statement->execute([
      'username' => $userName,
      'password' => md5($password)
    ]);
    $userFromDB = $statement->fetchObject();
    return new User($userFromDB->username, $userFromDB->id, $userFromDB->name,) ?: null;
  }
}