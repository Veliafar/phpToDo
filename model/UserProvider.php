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
        'password' => md5($plainPassword)
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
    return $userFromDB ? new User($userFromDB->username, $userFromDB->id, $userFromDB->name,) : null;
  }

  public function getUserByID(int $id): ?User
  {
    $statement = $this->pdo->prepare(
      'SELECT * FROM users WHERE id = :id LIMIT 1'
    );

    $statement->execute([
      'id' => $id,
    ]);
    $userFromDB = $statement->fetchObject();
    return new User($userFromDB->username, $userFromDB->id, $userFromDB->name) ?: null;
  }

  public function getUsersList(): bool | array
  {
    $statement = $this->pdo->prepare(
      'SELECT * FROM users'
    );

    $statement->execute();
    $usersFromDB = $statement->fetchAll(PDO::FETCH_OBJ);
    return $usersFromDB;
  }
}