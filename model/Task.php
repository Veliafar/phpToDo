<?php
require_once "User.php";

class TaskUtils
{
  static array $taskStatuses = array(
    "NEW" => "NEW",
    "IN_PROGRESS" => "IN_PROGRESS",
    "REVIEW" => "REVIEW",
    "TEST" => "TEST",
    "RELEASE" => "RELEASE",
    "DONE" => "DONE",
  );
  static array $taskStatusesTranslate = array(
    "NEW" => "Новая",
    "IN_PROGRESS" => "В работе",
    "REVIEW" => "Ревью",
    "TEST" => "Тест",
    "RELEASE" => "Релиз",
    "DONE" => "Завершена",
  );
  static array $taskStatusesColor = array(
    "NEW" => "#3399BF",
    "IN_PROGRESS" => "#76C63D",
    "REVIEW" => "#D54525",
    "TEST" => "orange",
    "RELEASE" => "#F5B202",
    "DONE" => "darkgray",
  );
}

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
    User   $assignee,
    string $title,
    string $description,
    string $dateTarget,
    string $status = 'NEW',
    int    $id = 0,
    string $dateCreate = '',
    string $dateUpdate = '',
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

  public function getDateTargetForHTMLValue(): string
  {
    return $this->dateTarget->format('Y-m-d');
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function getDateCreate(): string
  {
    return $this->dateCreate->format('d.m.Y H:i');
  }

  public function getDateCreateForDB(): string
  {
    return $this->dateTarget->format('d.m.Y H:m');
  }

  public function getDateUpdate(): string
  {
    return $this->dateUpdate->format('d.m.Y H:m');
  }

  public function isOutdated(): bool
  {
    return strtotime($this->dateTarget->format('d.m.Y')) <= time();
  }
}