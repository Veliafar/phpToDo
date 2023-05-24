<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap');

    <?php include __DIR__ . "/../styles.css";?>
</style>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $pageTitle ?></title>
</head>
<body>


<?php include "menu.php" ?>

<div class="wrapper">

    <h1 class="page-header">
      <?= $pageHeader ?>
    </h1>

    <form id="taskEdit" method="post" class="task-create">
        <div class="task-create__line">
            <label class="form-label" for="title">
                заголовок
            </label>
            <input
                    class="base-input"
                    id="title"
                    name="title"
                    type="text"
                    value="<?= $titleValue ?>"
                    required
            >
        </div>
        <div class="task-create__line">
            <label class="form-label" for="description">
                описание
            </label>
            <input
                    style="word-break: break-word;"
                    class="base-input"
                    id="description"
                    name="description"
                    type="text"
                    value="<?= $descriptionValue ?>"
                    required
            >
        </div>
        <div class="task-create__line">
            <label class="form-label" for="dateTarget">
                дедлайн
            </label>
            <input
                    class="base-input"
                    id="dateTarget"
                    name="dateTarget"
                    type="date"
                    value="<?= $dateTargetValue ?>"
                    required
            >
        </div>
        <div class="task-create__line">
            <label class="form-label" for="assigneeID">
                исполнитель
            </label>
            <select
                    class="base-input"
                    id="assigneeID"
                    name="assigneeID"
                    value="<?= $assigneeIDValue ?>"
                    required

            >
              <?php foreach ($users as $key => $assignee): ?>
                  <option
                          value="<?= $assignee->id ?>"
                    <?= $assignee->id === $assigneeIDValue ? 'selected' : '' ?>>
                    <?= $assignee->name ?>
                  </option>
              <?php endforeach; ?>
            </select>
        </div>
        <div class="task-create__line">
            <label class="form-label" for="status">
                статус
            </label>
            <select
                    class="base-input"
                    id="status"
                    name="status"
                    value="<?= $statusValue ?>"
                    required
            >
                <option value="NEW"
                  <?= "NEW" === $statusValue ? 'selected' : '' ?> >
                    Новая
                </option>
                <option value="IN_PROGRESS"
                  <?= "IN_PROGRESS" === $statusValue ? 'selected' : '' ?> >
                    В работе
                </option>
                <option value="REVIEW"
                  <?= "REVIEW" === $statusValue ? 'selected' : '' ?> >
                    Ревью
                </option>
                <option value="TEST"
                  <?= "TEST" === $statusValue ? 'selected' : '' ?> >
                    Тестирование
                </option>
                <option value="RELEASE"
                  <?= "RELEASE" === $statusValue ? 'selected' : '' ?> >
                    Релиз
                </option>
            </select>
        </div>

      <?php if (!$isEdit): ?>
          <input type="hidden" value="true" name="createTask">
      <?php else: ?>
          <input type="hidden" value="true" name="editTask">
      <?php endif; ?>

        <div class="task-create__control">
          <?php if (!$isEdit): ?>
              <button class="menu-button"
                      type="submit"
                      id="submit-btn">
                  Создать
              </button>
          <?php else: ?>
              <button class="menu-button"
                      type="submit"
                      id="submit-btn">
                  Изменить
              </button>
          <?php endif; ?>
            <a class="menu-button menu-button--secondary" href="/?controller=tasks">Отмена</a>
        </div>
    </form>

</div>

<script>
    const form = document.getElementById("taskEdit");
    const submitBtn = document.getElementById("submit-btn");

    submitBtn.disabled = !form.checkValidity();

    form.addEventListener("input", () => {
        const isFormValid = form.checkValidity();
        submitBtn.disabled = !isFormValid;
    });
</script>
