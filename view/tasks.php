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
    class TaskUtils {
        static taskStatuses = {
            "NEW": "NEW",
            "IN_PROGRESS": "IN_PROGRESS",
            "REVIEW": "REVIEW",
            "TEST": "TEST",
            "RELEASE": "RELEASE",
            "DONE": "DONE",
        }
        static taskStatusesTranslate = {
            "NEW": "Новая",
            "IN_PROGRESS": "В работе",
            "REVIEW": "Ревью",
            "TEST": "Тест",
            "RELEASE": "Релиз",
            "DONE": "Завершена",
        }
        static taskStatusesColor = {
            "NEW": "#3399BF",
            "IN_PROGRESS": "#76C63D",
            "REVIEW": "#D54525",
            "TEST": "orange",
            "RELEASE": "#F5B202",
            "DONE": "darkgray",
        }
    }

    class Task {

        id;
        title;
        description;
        ownerID;
        dateTarget;
        status;
        dateCreate;
        dateUpdate;
        assigneeID;
        assigneeName;
        assigneeUserName;

        constructor(
            task
        ) {
            Object.assign(this, task)
        }

        isOutDated() {
            const day = this.dateTarget.toString().slice(0, 2);
            const month = this.dateTarget.toString().slice(3, 5);
            const year = this.dateTarget.toString().slice(6, 10);
            return new Date(+year, +month-1, +day).getTime() <= new Date().getTime();
        }
    }

    let tasks = [];
    const taskAnimationDelay = 0.14;


    const filterTasks = async () => {
        const taskText = document.getElementById('taskText').value;
        const status = document.getElementById('status').value;
        const assigneeID = document.getElementById('assigneeID').value;
        const dateTarget = document.getElementById('dateTarget').value;

        // const response = await fetch(`/?controller=tasks&filterTasks=true&taskText=${taskText}&status=${status}&assigneeID=${assigneeID}&dateTarget=${dateTarget}`);
        //
        // if (response) {
        // }
    }

    const getTasksOnRefresh = async () => {
        const response = await fetch(`/?controller=tasks&refresh=true`);

        if (response) {
            const reqTasks = await response.json();

            const tasks = [];
            console.log('reqTasks', reqTasks);

            const backTasks = reqTasks?.['tasks']?.length
                ? JSON.parse(JSON.stringify(reqTasks?.['tasks']))
                : [];

            for (const backTask of backTasks) {
                tasks.push(new Task(backTask))
            }

            const tasksList = document.getElementById('tasksWrapper');

            for (const [index, task] of tasks.entries()) {
                const taskItem = document.createElement('div');
                taskItem.style.animationDelay = `${(index * taskAnimationDelay).toString()}s`
                taskItem.classList.add('tasks__item');
                taskItem.classList.add('tasks-item-onload');
                taskItem.id = task.id.toString();
                taskItem.innerHTML = `
      <div class="tasks__item-info tasks__item-info--status-wrapper">
        <div class="tasks__item__line">

          <div class="tasks__item-cell tasks__item-cell--column tasks__item-cell--column-left">
            <label class="tasks__item-label">
              статус
            </label>
            <div class="tasks__item__info tasks__item__info--status" style="background-color: ${TaskUtils.taskStatusesColor[task.status]};" >
              ${TaskUtils.taskStatusesTranslate[task.status]}
            </div>
          </div>

          <div class="tasks__item-cell tasks__item-cell--column  ${task.isOutDated() ? 'tasks__item-cell--outdated' : ''}">
            <label class="tasks__item-label">
              дедлайн
            </label>
            <div class="tasks__item__info tasks__item__info--time">
              ${task.dateTarget}

            </div>
          </div>
        </div>


        <div class="tasks__item__line">
          <div class="tasks__item-cell tasks__item-cell--column tasks__item-cell--column-left">
            <label class="tasks__item-label">
              исполнитель
            </label>
            <div class="tasks__item__info">
              ${task.assigneeName}
            </div>

          </div>

          <div class="tasks__item-cell tasks__item-cell--column">
            <label class="tasks__item-label">
              заголовок
            </label>
            <div class="tasks__item__info" title="${task.title}">
              ${task.title}
            </div>

          </div>
        </div>
        <div
          style="animation-delay: ${(index * taskAnimationDelay).toString()}s"
          class="tasks__item__description tasks__item__description--block-onload">
          <p
            style="animation-delay: ${(index * taskAnimationDelay).toString()}s"
            class="tasks__item__description__text tasks__item__description--onload"
            title="${task.description}">
            ${task.description}
          </p>
        </div>
      </div>
      <div
        style="animation-delay: ${(index * taskAnimationDelay).toString()}s"
        class="tasks__item-control tasks__item-control--onload">

        <div class="tasks__item__line">
          <div></div>
          <div class="tasks__item-cell tasks__item-cell--column">
            <label class="tasks__item-label">
              дата обновления
            </label>
            <div class="tasks__item__info">
              ${task.dateUpdate}
            </div>
          </div>
        </div>

        <div class="tasks__item-buttons">
          <a
            title="Редактировать задачу"
            class="menu-button menu-button--secondary"
            href="/?controller=taskEdit&id=${task.id}"
          >
            &#128393;
          </a>
          <a
            title="Удалить задачу"
            class="menu-button menu-button--secondary menu-button--secondary-danger"
            onclick="delTask(${task.id})"
          >
            &#10006;
          </a>
        </div>
      </div>
                `;
                tasksList.appendChild(taskItem);
            }

        }
    }

    const delTask = async (taskID) => {
        console.log(taskID);
        console.log(document.getElementById(taskID.toString()));
        const response = await fetch(`/?controller=tasks&delTask=${taskID}`);
        if (response) {
            document.getElementById(taskID).remove();
        }
    }

    window.onload = getTasksOnRefresh;

</script>

<?php include "menu.php" ?>

<div class="wrapper">
    <h1 class="page-header-tasks">
      <?= $pageHeader ?>
        <a class="menu-button-sqr" href="/?controller=taskEdit">+</a>
    </h1>

    <div id="tasksWrapper" class="tasks">

    </div>


</div>


</body>
</html>