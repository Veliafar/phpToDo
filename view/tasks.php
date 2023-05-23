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
    <h1 class="page-header"><?= $pageHeader ?></h1>

    <form class="tasks__add" method="post">
        <div></div>
        <input class="auth-form__input" type="text" name="description" placeholder="описание задачи" required>
        <button class="menu-button" type="submit">
            Добавить
        </button>
        <div></div>
    </form>


    <div class="tasks">

      <?php foreach ($tasks as $key => $task): ?>
          <div class="tasks__item" id="<?= $task->getID() ?>">
              <div class="tasks__item-info tasks__item-info--status-wrapper">

                  <div class="tasks__item-status <?= $task->getIsDone() ? 'tasks__item-status--done' : 'tasks__item-status--undone' ?>">
                  </div>

                  <div class="tasks__item-cell tasks__item-cell--column">
                      <label class="tasks__item-label">
                          дата создания
                      </label>
                    <?= $task->getDateCreate() ?>
                  </div>
                  <div class="tasks__item-cell tasks__item-cell--center">
                    <?= $task->description ?>
                  </div>
              </div>
              <div class="tasks__item-control">
                  <div class="tasks__item-cell tasks__item-cell--column">
                      <label class="tasks__item-label">
                          дата обновления
                      </label>
                    <?= $task->getDateUpdate() ?>
                  </div>

                  <div class="tasks__item-buttons">
                    <?php if (!$task->getIsDone()): ?>
                        <a
                                title="Выполнить задачу"
                                class="menu-button"
                                href="/?controller=tasks&changeTaskDone=<?= $key ?>&isDone=1">
                            ✔
                        </a>
                    <?php else: ?>
                        <a
                                title="Продолжить задачу"
                                class="menu-button menu-button--danger"
                                href="/?controller=tasks&changeTaskDone=<?= $key ?>&isDone=0">
                            &#8634;
                        </a>
                    <?php endif; ?>
                      <a
                              title="Удалить задачу"
                              class="menu-button menu-button--danger"
                              onclick="delTask(<?= $task->getID() ?>)"
                              >
                          &#10006;
                      </a>
                  </div>


              </div>
          </div>
      <?php endforeach; ?>
    </div>

  <?php if (!count($tasks)): ?>
      <h2 class="page-header">
          Все задачи выполнены!
      </h2>
  <?php endif; ?>
</div>

</body>
</html>