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

<script>
    const delTask = async (taskID) => {
        const response = await fetch(`/?controller=tasks&delTask=${taskID}`);
        const answer = await response.json();
        document.getElementById(answer.id).remove();
    }
</script>

<?php include "menu.php" ?>

<div class="wrapper">

    <h1 class="page-header">
      <?= $pageHeader ?>
    </h1>

    <form method="post" class="task-create">
        <div class="task-create__line">
            <label class="form-label" for="title">
                заголовок
            </label>
            <input
                    class="base-input"
                    id="title"
                    name="title"
                    type="text"
            >
        </div>
        <div class="task-create__line task-create__line--textarea">
            <label class="form-label" for="description">
                описание
            </label>
            <textarea
                    class="base-input"
                    id="description"
                    name="description"
            >
            </textarea>
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
            >
              <?php foreach ($users as $key => $assignee): ?>
                  <option value="<?= $assignee->id ?>">
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
            >
                <option value="NEW">Новая</option>
                <option value="IN_PROGRESS">В работе</option>
                <option value="REVIEW">Ревью</option>
                <option value="TEST">Тестирование</option>
                <option value="RELEASE">Релиз</option>
            </select>
        </div>

        <input type="hidden" value="true" name="createTask">

        <div class="task-create__control">
            <button class="menu-button" type="submit">
                Создать
            </button>

        </div>
    </form>

</div>
