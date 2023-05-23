<?php
require_once "Task.php";

class TaskProvider
{
  private PDO $pdo;
  private array $tasks;

  private int $userID;

  public function __construct(PDO $pdo, int $userID)
  {
    $this->pdo = $pdo;
    $this->userID = $userID;

    $statement = $this->pdo->prepare(
      'SELECT * FROM tasks WHERE userID = :userID'
    );

    $statement->execute([
      'userID' => $userID,
    ]);

    $tasksFromDB = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach ($tasksFromDB as $taskDB) {

      $taskFromBack = new Task(
        $taskDB->description,
        $taskDB->userID,
        $taskDB->id,
        $taskDB->isDone,
        $taskDB->dateCreate,
        $taskDB->dateUpdate,
      );

      $this->tasks[] = $taskFromBack;
    }
    if (!count($tasksFromDB)) {
      $this->tasks = [];
    }
  }

  public function getTasks(): array
  {
    return $this->tasks;
  }

  public function addTask(string $description, int $userID): void
  {
    $newTask = new Task(
      $description,
      $userID,
      $this->pdo->lastInsertId(),
    );

    $statement = $this->pdo->prepare(
      'INSERT INTO tasks (
                    description, isDone, dateCreate, dateUpdate, userID
                   ) VALUES (
                              :description, :isDone, :dateCreate, :dateUpdate, :userID
                             )'
    );

    $statement->execute([
      'description' => $newTask->description,
      'isDone' => (int)$newTask->getIsDone(),
      'dateCreate' => $newTask->getDateCreate(),
      'dateUpdate' => $newTask->getDateUpdate(),
      'userID' => $newTask->getUserID(),
    ]);

    $this->tasks[] = $newTask;
  }

  public function delTask(int $taskID): int
  {

    $statement = $this->pdo->prepare(
      'DELETE FROM tasks WHERE id = :id AND userID = :userID'
    );
    $statement->execute([
      'id' => $taskID,
      'userID' => $this->userID
    ]);

    $deleteKey = null;
    foreach($this->tasks as $key=>$value) {
      if ($value->getUserID() === $this->userID && $value->getID() === $taskID) {
        $deleteKey = $key;
      }
    }
    unset($this->tasks[$deleteKey]);

    return $taskID;
  }

  public function changeTaskDone(int $taskKey, int $isDone): int
  {
    $taskID = $this->tasks[$taskKey]->getID();
    $userID = $this->tasks[$taskKey]->getUserID();

    $statement = $this->pdo->prepare(
      'UPDATE tasks SET isDone=:isDone WHERE id = :id AND userID = :userID'
    );
    $statement->execute([
      'isDone' => $isDone,
      'id' => $taskID,
      'userID' => $userID
    ]);
    $makeDoneKey = null;
    foreach($this->tasks as $key=>$value) {
      if ($value->getUserID() === $userID && $value->getID() === $taskID) {
        $makeDoneKey = $key;
      }
    }
    $this->tasks[$makeDoneKey]->changeTaskReady($isDone);

    return $taskID;
  }

}