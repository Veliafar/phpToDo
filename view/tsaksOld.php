<?php if (count($tasks)): ?>

  <div id="tasksFilter" class="tasks-filter">
    <div class="tasks-filter__block">
      <label class="tasks-filter__block__label" for="taskText">
        текст
      </label>
      <input
        class="base-input filter-input"
        id="taskText"
        name="taskText"
        type="text"
        value="<?= $taskText ?>"


      >
    </div>


    <div class="tasks-filter__block">
      <label class="tasks-filter__block__label" for="status">
        статус
      </label>
      <select
        class="base-input filter-input"
        id="status"
        name="status"
        value="<?= $statusValue ?>"


      >
        <option
          value="<?= '' ?>"
          <?= $statusValue === '' ? 'selected' : '' ?>>
          <?= '' ?>
        </option>
        <?php foreach ($taskStatuses as $key => $status): ?>
          <option
            value="<?= $status ?>"
            <?= $status === $statusValue ? 'selected' : '' ?>>
            <?= $taskStatusesTranslate[$status] ?>
          </option>
        <?php endforeach; ?>

      </select>
    </div>

    <div class="tasks-filter__block">
      <label class="tasks-filter__block__label" for="status">
        исполнитель
      </label>
      <select
        class="base-input filter-input"
        id="assigneeID"
        name="assigneeID"
        value="<?= $assigneeIDValue ?>"


      >
        <option
          value="<?= '' ?>"
          <?= $assigneeIDValue === '' ? 'selected' : '' ?>>
          <?= '' ?>
        </option>
        <?php foreach ($users as $key => $assignee): ?>
          <option
            value="<?= $assignee->id ?>"
            <?= $assignee->id === $assigneeIDValue ? 'selected' : '' ?>>
            <?= $assignee->name ?>
          </option>
        <?php endforeach; ?>

      </select>
    </div>


    <div class="tasks-filter__block">
      <label class="tasks-filter__block__label" for="taskText">
        дедлайн
      </label>
      <input
        class="base-input filter-input"
        id="dateTarget"
        name="dateTarget"
        type="date"
        value="<?= $taskTargetDate ?>"

      >
    </div>

    <div class="tasks-filter__block tasks-filter__block--control">
      <button
        onclick="filterTasks();"
        title="Фильтр"
        class="menu-button menu-button--search"

      >
        S

      </button>
    </div>
  </div>
<?php endif; ?>

<div id="tasksWrapper" class="tasks">
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