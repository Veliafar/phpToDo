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
            return new Date(+year, +month - 1, +day).getTime() <= new Date().getTime();
        }
    }

    const taskTextValue = '';
    const statusValue = '';
    const assigneeIDValue = '';
    const dateTargetValue = '';

    let tasks = [];
    let tasksCache = [];
    const taskAnimationDelay = 0.14;
    let users = [];

    const clearFilter = async () => {
        document.getElementById('taskText').value = '';
        document.getElementById('status').value = '';
        document.getElementById('assigneeID').value = '';
        document.getElementById('dateTarget').value = '';

        tasks = [];
        const backTasks = JSON.parse(JSON.stringify(tasksCache));
        console.log('backTasks', backTasks)
        for (const backTask of backTasks) {
            tasks.push(new Task(backTask))
        }
        document.getElementById('tasksWrapper').innerHTML = "";
        setTasksList(tasks);
    }


    const filterTasks = async () => {
        let taskText = document.getElementById('taskText').value;
        let status = document.getElementById('status').value;
        let assigneeID = document.getElementById('assigneeID').value;
        let dateTarget = document.getElementById('dateTarget').value;

        tasks = [];
        const backTasks = JSON.parse(JSON.stringify(tasksCache));
        console.log('backTasks', backTasks)
        for (const backTask of backTasks) {
            tasks.push(new Task(backTask))
        }

        if (taskText && taskText?.length && taskText != '') {
            console.log('TEXT')
            tasks = tasks.filter((el) => el.title.includes(taskText) || el.description.includes(taskText));
        }
        if (Object.values(TaskUtils.taskStatuses).includes(status)) {
            console.log('STATUS')
            tasks = tasks.filter((el) => el.status === status);
        }
        if (users.find(el => +el.id === +assigneeID)) {
            console.log('USER')
            console.log(+assigneeID)
            tasks = tasks.filter((el) => +el.assigneeID === +assigneeID);
        }
        if (dateTarget) {
            console.log('DATE')
            const year = dateTarget.slice(0, 4);
            const month = dateTarget.slice(5, 7);
            const day = dateTarget.slice(8, 10);
            const searched = `${day}.${month}.${year}`;
            tasks = tasks.filter((el) => el.dateTarget === searched);
        }

        console.log('tasks', tasks)

        document.getElementById('tasksWrapper').innerHTML = "";
        setTasksList(tasks);

    }

    const getTasksOnRefresh = async () => {
        const response = await fetch(`/?controller=tasks&refresh=true`);

        if (!response) {
            return;
        }
        const reqTasks = await response.json();

        const tasks = [];
        console.log('reqTasks', reqTasks);

        users = reqTasks?.['users']?.length
            ? JSON.parse(JSON.stringify(reqTasks?.['users']))
            : [];

        const backTasks = reqTasks?.['tasks']?.length
            ? JSON.parse(JSON.stringify(reqTasks?.['tasks']))
            : [];
        for (const backTask of backTasks) {
            tasks.push(new Task(backTask))
        }

        if (!tasks.length) {
            return;
        }

        tasksCache = JSON.parse(JSON.stringify(tasks));

        const tasksFilter = document.createElement('div');
        tasksFilter.id = 'tasksFilter';
        tasksFilter.classList.add('tasks-filter');
        tasksFilter.innerHTML = `
            <div class="tasks-filter__block">
      <label class="tasks-filter__block__label" for="taskText">
        текст
      </label>
      <input
        class="base-input filter-input"
        id="taskText"
        name="taskText"
        type="text"
        value="${taskTextValue}"


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
        value="${statusValue}"


      >
        <option
          value="''"
          ${statusValue === '' ? 'selected' : ''}
          >
        </option>

        ${(function taskStatusOptions() {
            let options = '';

            for (const status of Object.values(TaskUtils.taskStatuses)) {
                options += `
                        <option
                            value="${status}"
                            ${status === statusValue ? 'selected' : ''}>
                            ${TaskUtils.taskStatusesTranslate[status]}
                      </option>
                    `
            }
            return options;
        })()}
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
        value="${assigneeIDValue}"


      >
        <option
          value="''"
          ${assigneeIDValue === '' ? 'selected' : ''}
          >
        </option>
        ${(function assigneeIDOptions() {
            let options = '';

            for (const user of users) {
                options += `
                        <option
                            value="${user.id}"
                            ${user.id === statusValue ? 'selected' : ''}>
                            ${user.name}
                      </option>
                    `
            }
            return options;
        })()}
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
        value="${dateTargetValue}"

      >
    </div>

    <div class="tasks-filter__block tasks-filter__block--control">
      <button
        onclick="filterTasks()"
        title="Фильтр"
        class="menu-button menu-button--search menu-button--secondary"

      >
        &#128269;

      </button>

    </div>
    <div class="tasks-filter__block tasks-filter__block--control">
      <button
        onclick="clearFilter()"
        title="Сбросить поиск"
        class="menu-button menu-button--search menu-button--secondary"

      >
        &#10006;
      </button>
    </div>


            `
        const wrapper = document.getElementById('filterWrap');
        wrapper.appendChild(tasksFilter);

        setTasksList(tasks);
    }

    function setTasksList(tasks) {
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

<div id="wrapper" class="wrapper">
    <h1 class="page-header-tasks">
      <?= $pageHeader ?>
        <a class="menu-button-sqr" href="/?controller=taskEdit">+</a>
    </h1>

    <div id="filterWrap">

    </div>

    <div id="tasksWrapper" class="tasks">

    </div>


</div>


</body>
</html>