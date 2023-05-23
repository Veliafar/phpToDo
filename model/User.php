<?php

class User
{
  private int $id;
  private string $userName;
  private string $name;

  public function __construct(string $userName, int $id = 0, string $name = '')
  {
    $this->userName = $userName;
    $this->id = $id;
    $this->name = $name;
  }

  public function getID(): int
  {
    return $this->id;
  }

  public function setID($id): void
  {
    $this->id = $id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getUserName(): string
  {
    return $this->userName;
  }

  public function setUserName(string $userName): self
  {
    $this->userName = $userName;

    return $this;
  }
}