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
        if (response) {
            document.getElementById(taskID).remove();
        }
    }
</script>

<?php include "menu.php" ?>

<div class="wrapper">
    <h1 class="page-header-tasks">
      <?= $pageHeader ?>
        <a class="menu-button-sqr" href="/?controller=taskEdit">+</a>
    </h1>

    <!--    <form class="tasks__add" method="post">-->
    <!--        <div></div>-->
    <!--        <input class="auth-form__input" type="text" name="description" placeholder="описание задачи" required>-->
    <!--        <button class="menu-button" type="submit">-->
    <!--            Добавить-->
    <!--        </button>-->
    <!--        <div></div>-->
    <!--    </form>-->


    <div id="tasksWrapper" class="tasks">

      <?php foreach ($tasks as $key => $task): ?>
          <div class="tasks__item" id="<?= $task->getID() ?>">
              <div class="tasks__item-info tasks__item-info--status-wrapper">

                  <!--                  <div class="tasks__item-status -->
                <?php //= $task->getIsDone() ? 'tasks__item-status--done' : 'tasks__item-status--undone' ?><!--">-->
                  <!--                  </div>-->

                  <div class="tasks__item__line">
                      <div class="tasks__item-cell tasks__item-cell--column tasks__item-cell--column-left">
                          <label class="tasks__item-label">
                              статус
                          </label>
                          <div class="tasks__item__info">
                            <?= $task->getStatus() ?>
                          </div>
                      </div>

                      <div class="tasks__item-cell tasks__item-cell--column">
                          <label class="tasks__item-label">
                              дедлайн
                          </label>
                          <div class="tasks__item__info">
                            <?= $task->getDateTarget() ?>
                          </div>
                      </div>
                  </div>


                  <div class="tasks__item__line">
                      <div class="tasks__item-cell tasks__item-cell--column tasks__item-cell--column-left">
                          <label class="tasks__item-label">
                              исполнитель
                          </label>
                          <div class="tasks__item__info">
                            <?= $task->getAssignee()->getName() ?>
                          </div>

                      </div>

                      <div class="tasks__item-cell tasks__item-cell--column">
                          <label class="tasks__item-label">
                              заголовок
                          </label>
                          <div class="tasks__item__info">
                            <?= $task->title ?>
                          </div>

                      </div>
                  </div>


                  <div class="tasks__item__description">
                    <?= $task->description ?>
                  </div>


              </div>
              <div class="tasks__item-control">
                  <div class="tasks__item-cell tasks__item-cell--column">
                      <label class="tasks__item-label">
                          дата обновления
                      </label>
                      <div class="tasks__item__info">
                        <?= $task->getDateUpdate() ?>
                      </div>
                  </div>

                  <div class="tasks__item-buttons">
<!--                    --><?php //if (!$task->getIsDone()): ?>
<!--                        <a-->
<!--                                title="Выполнить задачу"-->
<!--                                class="menu-button"-->
<!--                                href="/?controller=tasks&changeTaskDone=-->
<!--                                          --><?php //= $key ?><!--&isDone=1">-->
<!--                            ✔-->
<!--                        </a>-->
<!--                    --><?php //else: ?>
<!--                        <a-->
<!--                                title="Продолжить задачу"-->
<!--                                class="menu-button menu-button--danger"-->
<!--                                href="/?controller=tasks&changeTaskDone=-->
<!--                                          --><?php //= $key ?><!--&isDone=0">-->
<!--                            &#8634;-->
<!--                        </a>-->
<!--                    --><?php //endif; ?>

                    <?php if ($task->getOwnerID() === $userID): ?>
                        <a
                                title="Редактировать задачу"
                                class="menu-button"
                                href="/?controller=taskEdit&id=<?= $task->getID() ?>"
                        >
                            &#128393;
                        </a>
                    <?php endif; ?>

                    <?php if ($task->getOwnerID() === $userID): ?>
                        <a
                                title="Удалить задачу"
                                class="menu-button menu-button--danger"
                                onclick="delTask(<?= $task->getID() ?>)"
                        >
                            &#10006;
                        </a>
                    <?php endif; ?>

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