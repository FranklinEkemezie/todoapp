const taskDisplayEl = select("#tasks");
const loader = select(".loader", false, taskDisplayEl);
const switchViewControl = select(".switch-view", false, taskDisplayEl);

const TASK_STATUS = {
  1: ["to-do", "To Do"],
  2: ["in-progress", "In Progress"],
  3: ["completed", "Completed"]
}

const ID_LIST_VIEW = 'list-view';
const ID_TAB_VIEW = 'tab-view';

const statusIsValid = (status) => Object.keys(TASK_STATUS)
  .map(value => parseInt(value)).includes(status);

  /**
   * Display this when there is no task
   */
const noTaskTemplate = (status) => {
  let noTaskEl = document.createElement("div");
  noTaskEl.classList.add("no-tasks", "w-100", "my-3", "py-2");
  noTaskEl.innerHTML = `
  <p class="fst-italic text-center fs-6 text-secondary">
    ${FONTAWESOME_ICONS.NO_TASK_FOUND}
    No <b>${TASK_STATUS[status][1].split(" ").join("-")}</b> task found!
  </p>
  `;

  return noTaskEl;
}

/**
 * 
 * @param {object[]} taskObjs An array containing the objects holding information about a task to be filtered
 * @param {number} status The status of the task to be selected
 * @returns 
 */
const filterTasksByStatus = (taskObjs, status) => 
  taskObjs.filter(taskObjs => taskObjs.status === status);

// Select HTML elements here

/**
 * ---------------------------------
 * Add Event Listener
 * ---------------------------------
 */

on(document, "DOMContentLoaded", async function() {
  let toDoTasks = await Task.ToDo.fetchTasks() || [];
  let inProgressTasks = await Task.InProgress.fetchTasks() || [];
  let completedTasks = await Task.Completed.fetchTasks() || [];

  let tasks = [...toDoTasks, ...inProgressTasks, ...completedTasks];

  taskDisplayEl.replaceChild(
    displayTabView(tasks),
    loader
  );

  console.log(displayTabView(tasks));
});

async function switchTaskDisplayView() {
  let taskViewContainer = select(".task-view-container", false, taskDisplayEl);

  // Replace with loader
  taskDisplayEl.replaceChild(
    loader,
    taskViewContainer
  );

  // Fetch tasks
  let tasks = [
    ...await Task.ToDo.fetchTasks() || [],
    ...await Task.InProgress.fetchTasks() || [],
    ...await Task.Completed.fetchTasks() || []
  ];

  if(taskViewContainer.id === ID_LIST_VIEW) {
    taskDisplayEl.replaceChild(
      displayTabView(tasks),
      loader
    );
  } else if (taskViewContainer.id === ID_TAB_VIEW) {
    taskDisplayEl.replaceChild(
      displayListView(tasks),
      loader
    );
  }
}

/**
 * Display tasks in list view
 * 
 * @param {HTMLAnchorElement[]} tasks An array of task UI element to display in list view
 */
function displayListView(tasks) {
  const listDisplayTemplate = (status) => {
    if(!statusIsValid(status)) throw new Error("Invalid status: " + status);

    const template = document.createElement("div");
    template.id = TASK_STATUS[status][0];
    template.classList.add(
      "task-display",
      "my-3"
    );

    let taskDisplayTitle = document.createElement("h3");
    taskDisplayTitle.classList.add("fs-4", "fw-bold","my-2");
    taskDisplayTitle.innerHTML = `
      ${FONTAWESOME_ICONS.NOT_STARTED}
      ${TASK_STATUS[status][1]}
    `;

    const taskDisplayWrapper = document.createElement("div");
    taskDisplayWrapper.classList.add("task-display-wrapper");

    const taskDisplayContainer = document.createElement("div");
    taskDisplayContainer.classList.add("task-display-container");

    let tasksUI = filterTasksByStatus(tasks, status).map(
      taskObj => Task.createTaskFromObject(taskObj).display());
    
    taskDisplayContainer.append(
      ...(tasksUI.length > 0 ? tasksUI : [noTaskTemplate(status)])
    );
    taskDisplayWrapper.append(taskDisplayContainer);

    template.append(
      taskDisplayTitle,
      taskDisplayWrapper
    );

    return template;
  }

  const listViewContainer = document.createElement("div");
  listViewContainer.id = ID_LIST_VIEW;
  listViewContainer.classList.add("list-view-container", "task-view-container");

  listViewContainer.append(
    ...[1, 2, 3].map(e => listDisplayTemplate(e))
  );

  return listViewContainer;
}

/**
 * Display task in tab view
 * 
 * @param {HTMLAnchorElement[]} tasks An array of task UI element to display in tab view
 */
function displayTabView(tasks) {
  const taskTabTemplate = (status) => {
    if(!statusIsValid(status)) throw new Error("Invalid status: " + status);

    let statusTasks = filterTasksByStatus(tasks, status);

    let tabTemplate = document.createElement("li");
    tabTemplate.classList.add("nav-item", "p-0", "m-0");

    let tabTemplateLink = document.createElement("a");
    tabTemplateLink.href = `#${TASK_STATUS[status][0]}-tab-pane`;
    tabTemplateLink.classList.add("nav-link", "overflow-ellipsis", "text-muted", "fw-bold");
    if(status === 1) tabTemplateLink.classList.add("active");
    tabTemplateLink.setAttribute("data-bs-toggle", "tab");
    tabTemplateLink.innerHTML = `
      ${ statusTasks.length > 0 ? 
        `<span class="badge bg-primary rounded-pill">${statusTasks.length}</span>` 
        : "" 
      }
      ${TASK_STATUS[status][1]}
    `;

    tabTemplate.append(tabTemplateLink);

    return tabTemplate;
  }

  const taskTabPaneTemplate = (status) => {
    if(!statusIsValid(status)) throw new Error("Invalid status: " + status);

    const tabPaneTemplate = document.createElement("div");
    tabPaneTemplate.id = `${TASK_STATUS[status][0]}-tab-pane`;
    tabPaneTemplate.classList.add("tab-pane");
    if(status === 1) tabPaneTemplate.classList.add("active");

    const tabTaskList = document.createElement("div");
    tabTaskList.classList.add("list-group");

    let tasksUI = filterTasksByStatus(tasks, status).map(
      taskObj => Task.createTaskFromObject(taskObj).display(TASK_DISPLAY_MODE_TAB));

    // Add the tasks as list group items
    tabTaskList.append(
      ...(tasksUI.length > 0 ? tasksUI : [noTaskTemplate(status)])
    );

    tabPaneTemplate.append(tabTaskList);

    return tabPaneTemplate;
  }

  const tabViewContainer = document.createElement("div");
  tabViewContainer.id = ID_TAB_VIEW;
  tabViewContainer.classList.add("tab-view-container", "task-view-container");

  // Nav Tabs
  const taskNavTab = document.createElement("ul");
  taskNavTab.classList.add("nav", "nav-tabs", "nav-fill", "font-primary");

  taskNavTab.append(
    ...[1, 2, 3].map(e => taskTabTemplate(e))
  );

  // Tab panes
  const taskNavTabPane = document.createElement("div");
  taskNavTabPane.classList.add("tab-content");

  taskNavTabPane.append(
    ...[1, 2, 3].map(e => taskTabPaneTemplate(e))
  );

  tabViewContainer.append(
    taskNavTab,
    taskNavTabPane
  );

  return tabViewContainer;
}

// Mark a task as completed
async function markAsCompleted(task_id, el) {

  // Create a dummy form to hold our data
  let mark_as_completed_form = document.createElement("form");
  let task_id_input = document.createElement("input")
  let mark_as_completed_input = document.createElement("input");

  mark_as_completed_input.name = "status";
  mark_as_completed_input.value = 3;

  mark_as_completed_form.append(mark_as_completed_input);

  task_id_input.name = "task-id";
  task_id_input.value = task_id;

  mark_as_completed_form.append(task_id_input);

  let formData = new FormData(mark_as_completed_form);
  let res = await Task.editTask(formData);

  let msg = displayNotificationMessage(
    res.message,
    res.success ? "success" : "danger"
  );

  on(msg, "hidden.bs.toast", () => window.location.reload())
}