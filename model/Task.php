<?php
require_once "User.php";

class Task
{
  private int $id;

  public string $title;
  public string $description;

  private string $status;

  private DateTime $dateCreate;

  private DateTime $dateTarget;

  private DateTime $dateUpdate;

  private int $ownerID;

  private User $assignee;

  public function __construct(
    int    $ownerID,
    User    $assignee,
    string $title,
    string $description,
    string $dateTarget,
    string $status = 'NEW',
    int    $id = 0,
    string $dateUpdate = '',
    string $dateCreate = '',
  )
  {
    $this->ownerID = $ownerID;
    $this->assignee = $assignee;
    $this->title = $title;
    $this->description = $description;
    $this->dateTarget = $this->prepareStringDate($dateTarget);
    $this->status = $status;
    $this->id = $id;
    $this->dateCreate = !$dateCreate ? new DateTime() : $this->prepareStringDate($dateCreate);
    $this->dateUpdate = !$dateUpdate ? new DateTime() : $this->prepareStringDate($dateUpdate);
  }

  public function prepareStringDate(string $dateString): DateTime
  {
    $datePrepare = new DateTime();
    $datePrepare->setTimestamp(strtotime($dateString));
    return $datePrepare;
  }

  public function getID(): int
  {
    return $this->id;
  }

  public function getOwnerID(): int
  {
    return $this->ownerID;
  }

  public function getAssignee(): User
  {
    return $this->assignee;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function getDateTarget(): string
  {
    return $this->dateTarget->format('d.m.Y');
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function getDateCreate(): string
  {
    return $this->dateCreate->format('d.m.Y H:i');
  }

  public function getDateUpdate(): string
  {
    return $this->dateUpdate->format('d.m.Y H:m');
  }



  //  public function changeTaskReady(int $isDone): void
//  {
//    $this->dateUpdate = new DateTime();
//    $this->isDone = boolval($isDone);
//  }

}