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

    <div id="tasksWrapper" class="tasks <?= count($tasks) ? 'tasks--onload' : '' ?>">

      <?php foreach ($tasks as $key => $task): ?>
        <?php $taskItemIndex++ ?>

          <div

                  style="animation-delay: calc(<?= $taskAnimationDelay . $taskItemIndex ?>)"
                  class="tasks__item <?= count($tasks) ? 'tasks-item-onload' : '' ?>"
                  id="<?= $task->getID() ?>">
              <div class="tasks__item-info tasks__item-info--status-wrapper">

                  <!--                  <div class="tasks__item-status -->
                <?php //= $task->getIsDone() ? 'tasks__item-status--done' : 'tasks__item-status--undone' ?><!--">-->
                  <!--                  </div>-->

                  <div class="tasks__item__line">

                      <div class="tasks__item-cell tasks__item-cell--column tasks__item-cell--column-left">
                          <label class="tasks__item-label">
                              статус
                          </label>
                          <div class="tasks__item__info tasks__item__info--status" <?= 'style="background-color:' . $taskStatusesColor[$task->getStatus()] . ';"' ?> >
                            <?= $taskStatusesTranslate[$task->getStatus()] ?>
                          </div>
                      </div>

                      <div class="tasks__item-cell tasks__item-cell--column <?= $task->isOutdated() ? 'tasks__item-cell--outdated' : '' ?>">
                          <label class="tasks__item-label">
                              дедлайн
                          </label>
                          <div class="tasks__item__info tasks__item__info--time">
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
                          <div class="tasks__item__info" title="<?= $task->title ?>">
                            <?= $task->title ?>
                          </div>

                      </div>
                  </div>


                  <div
                          style="animation-delay: calc(<?= $taskAnimationDelay . $taskItemIndex ?>)"
                          class="tasks__item__description <?= count($tasks) ? ' tasks__item__description--block-onload' : '' ?>">
                      <p
                              style="animation-delay: calc(<?= $taskAnimationDelay . $taskItemIndex ?>)"
                              class="tasks__item__description__text <?= count($tasks) ? ' tasks__item__description--onload' : '' ?>"
                              title="<?= $task->description ?>">
                        <?= $task->description ?>
                      </p>
                  </div>


              </div>
              <div
                      style="animation-delay: calc(<?= $taskAnimationDelay . $taskItemIndex ?>)"
                      class="tasks__item-control <?= count($tasks) ? ' tasks__item-control--onload' : '' ?>">

                  <div class="tasks__item__line">
                      <div></div>
                      <div class="tasks__item-cell tasks__item-cell--column">
                          <label class="tasks__item-label">
                              дата обновления
                          </label>
                          <div class="tasks__item__info">
                            <?= $task->getDateUpdate() ?>
                          </div>
                      </div>
                  </div>

                  <div class="tasks__item-buttons">
                    <?php if ($task->getOwnerID() === $userID): ?>
                        <a
                                title="Редактировать задачу"
                                class="menu-button menu-button--secondary"
                                href="/?controller=taskEdit&id=<?= $task->getID() ?>"
                        >
                            &#128393;
                            <!--                            &#128065;-->
                        </a>
                        <a
                                title="Удалить задачу"
                                class="menu-button menu-button--secondary menu-button--secondary-danger"
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