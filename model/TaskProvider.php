<?php
require_once "Task.php";
require_once "User.php";
require_once 'model/UserProvider.php';

class TaskProvider
{
  private PDO $pdo;
  private array $tasks;

  private int $userID;

  private UserProvider $userProvider;

  public function __construct(PDO $pdo, int $userID, UserProvider $userProvider)
  {
    $this->pdo = $pdo;
    $this->userID = $userID;
    $this->userProvider = $userProvider;

    $statement = $this->pdo->prepare(
      'SELECT * FROM tasks WHERE ownerID = :ownerID'
    );

    $statement->execute([
      'ownerID' => $userID,
    ]);

    $tasksFromDB = $statement->fetchAll(PDO::FETCH_OBJ);

    foreach ($tasksFromDB as $taskDB) {

      $assignee = $this->userProvider->getUserByID($taskDB->assigneeID);

      $taskFromBack = new Task(
        $taskDB->ownerID,
        $assignee,
        $taskDB->title,
        $taskDB->description,
        $taskDB->dateTarget,
        $taskDB->status,
        $taskDB->id,
        $taskDB->dateCreate,
        $taskDB->dateUpdate,
      );

      $this->tasks[] = $taskFromBack;
      uasort($this->tasks, function ($a, $b) {
        return strtotime($a->getDateTarget()) <=> strtotime($b->getDateTarget());
      });
    }
    if (!count($tasksFromDB)) {
      $this->tasks = [];
    }
  }

  public function getTasks(): array
  {
    return $this->tasks;
  }

  public function addTask(
    int    $ownerID,
    int    $assigneeID,
    string $title,
    string $description,
    string $dateTarget,
    string $status,
  ): void
  {

    $assignee = $this->userProvider->getUserByID($assigneeID);
    $newTask = new Task(
      $ownerID,
      $assignee,
      $title,
      $description,
      $dateTarget,
      $status,
      $this->pdo->lastInsertId(),
    );

    $statement = $this->pdo->prepare(
      'INSERT INTO tasks (
                    ownerID, assigneeID, title, description, dateTarget, status, dateUpdate, dateCreate
                   ) VALUES (
                              :ownerID, :assigneeID, :title, :description, :dateTarget, :status, :dateUpdate, :dateCreate
                             )'
    );

    $statement->execute([
      'ownerID' => $newTask->getOwnerID(),
      'assigneeID' => $newTask->getAssignee()->getID(),
      'title' => $newTask->getTitle(),
      'description' => $newTask->getDescription(),
      'dateTarget' => $newTask->getDateTarget(),
      'status' => $newTask->getStatus(),
      'dateUpdate' => $newTask->getDateUpdate(),
      'dateCreate' => $newTask->getDateCreate(),
    ]);

    $this->tasks[] = $newTask;
  }


  public function editTask(
    int    $id,
    int    $ownerID,
    int    $assigneeID,
    string $title,
    string $description,
    string $dateTarget,
    string $status,
    string $dateCreate,
  ): void
  {

    $assignee = $this->userProvider->getUserByID($assigneeID);



    $newTask = new Task(
      $ownerID,
      $assignee,
      $title,
      $description,
      $dateTarget,
      $status,
      $id,
      $dateCreate
    );

    $statement = $this->pdo->prepare(
      'UPDATE tasks SET ownerID = :ownerID, assigneeID = :assigneeID, title = :title, description = :description, dateTarget = :dateTarget, status = :status, dateUpdate = :dateUpdate, dateCreate = :dateCreate WHERE id = :id'
    )->execute([
      'id' => $id,
      'ownerID' => $newTask->getOwnerID(),
      'assigneeID' => $newTask->getAssignee()->getID(),
      'title' => $newTask->getTitle(),
      'description' => $newTask->getDescription(),
      'dateTarget' => $newTask->getDateTargetForHTMLValue(),
      'status' => $newTask->getStatus(),
      'dateUpdate' => $newTask->getDateUpdate(),
      'dateCreate' => $newTask->getDateCreateForDB(),
    ]);
  }

  public function getTaskByID(
    int $taskID,
  ): ?Task
  {
    $statement = $this->pdo->prepare(
      'SELECT * FROM tasks WHERE id = :id LIMIT 1'
    );
    $statement->execute([
      'id' => $taskID,
    ]);
    $taskDB = $statement->fetchObject();
    $assignee = $this->userProvider->getUserByID($taskDB->assigneeID);

    $editTask = new Task(
      $taskDB->ownerID,
      $assignee,
      $taskDB->title,
      $taskDB->description,
      $taskDB->dateTarget,
      $taskDB->status,
      $taskDB->id,
      $taskDB->dateCreate,
      $taskDB->dateUpdate,
    );

    return $editTask;
  }

  public function delTask(int $taskID): int
  {

    $statement = $this->pdo->prepare(
      'DELETE FROM tasks WHERE id = :id AND ownerID = :ownerID'
    );
    $statement->execute([
      'id' => $taskID,
      'ownerID' => $this->userID
    ]);

    $deleteKey = null;
    foreach ($this->tasks as $key => $value) {
      if ($value->getUserID() === $this->userID && $value->getID() === $taskID) {
        $deleteKey = $key;
      }
    }
    unset($this->tasks[$deleteKey]);
    return $taskID;
  }

//  public function changeTaskDone(int $taskKey, int $isDone): int
//  {
//    $taskID = $this->tasks[$taskKey]->getID();
//    $userID = $this->tasks[$taskKey]->getUserID();
//
//    $statement = $this->pdo->prepare(
//      'UPDATE tasks SET isDone=:isDone WHERE id = :id AND userID = :userID'
//    );
//    $statement->execute([
//      'isDone' => $isDone,
//      'id' => $taskID,
//      'userID' => $userID
//    ]);
//    $makeDoneKey = null;
//    foreach ($this->tasks as $key => $value) {
//      if ($value->getUserID() === $userID && $value->getID() === $taskID) {
//        $makeDoneKey = $key;
//      }
//    }
//    $this->tasks[$makeDoneKey]->changeTaskReady($isDone);
//
//    return $taskID;
//  }

}