// Constants

const TASK_DISPLAY_MODE_TAB = 'T567';
const TASK_DISPLAY_MODE_LIST = 'T568';

const CLASS_NAME_TASK_CONTAINER = 'task-container';
const CLASS_NAME_TASK_OPTIONS = 'task-options';
const CLASS_NAME_TASK_DESC_IMG_CONTAINER = 'description-img-container';
const CLASS_NAME_TASK_CONTENT = 'task-content';
const CLASS_NAME_TASK_TITLE = 'task-title';
const CLASS_NAME_TASK_DESCRIPTION = 'task-description';
const CLASS_NAME_TASK_STATUS = 'task-status';
const CLASS_NAME_TASK_DISPLAY_CONTENT = 'task-display-content';
const CLASS_NAME_TASK_DISPLAY_HEADER = 'task-display-header';

const ID_TASK_OPTIONS_DROPDOWN_TOGGLE_BTN = 'task-options-dropdown-toggle';

const CLASS_NAME_BS_DROPDOWN = 'dropdown';
const CLASS_NAME_BS_DROPDOWN_MENU = 'dropdown-menu';
const CLASS_NAME_BS_DROPDOWN_ITEM = 'dropdown-item';
const CLASS_NAME_BS_DROPDOWN_TOGGLE_BTN = 'dropdown-toggle';


class Task {
  constructor(
    taskId, title, description,
    status, priority, dateCreated, dateDue
  ) {
    this._taskId = taskId;
    this._title = title;
    this._description = description;
    this._status = Task._validateStatus(status);
    this._priority = priority;
    this._dateCreated = dateCreated;
    this._dateDue = dateDue;
  }

  // Static

  // Private

  /**
   * Checks whether the status is valid or not
   * 
   * @param {number} status 
   * @returns {number} The status nuumber if valid; otherwise throws an error
   */
  static _validateStatus(status) {
    if(![1, 2, 3].includes(status)) {
      throw new Error(`Status ${status} not valid`);
    }
    return status;
  }

  /**
   * Fetch tasks with the given status
   * 
   * @param {number} status A valid status number to indicate the status of task to fetch.
   * If this value is not given, it defaults to NULL which fetches all the tasks
   * 
   * @return {Task[]} Returns an array containing the tasks with the
   * given status
   */
  static async _fetchTasks(status=null) {
    status = this._validateStatus(status);

    let param = status !== null ? "status=" + status : "";
    let fetch_url = "./../../tasks?" + param;

    return await fetch(fetch_url)
      .then(response => {
        if(!response.ok) {
          console.warn("Something went wrong");
        }
        return response.json();
      })
      .catch(error => {
        console.error("An error occurred while fetching task: " + error);
      });
  }

  // Public

  /**
   * Get a Task object from a given object
   * 
   * @param {object} object An object with properties: task_id, title, description,
   * status, priority, date_created, date_due
   * 
   * @returns {Task} Returns the Task object
   */
  static createTaskFromObject(object) {
    return new Task(
      object.task_id, object.title,
      object.description, object.status, object.priority,
      object.date_created, object.date_due
    );
  }

  // Fetch Tasks

  static ToDo = {
    fetchTasks: async () => await Task._fetchTasks(1)
  }

  static InProgress = {
    fetchTasks: async () => await Task._fetchTasks(2)
  }

  static Completed = {
    fetchTasks: async () => await Task._fetchTasks(3)
  }

  /**
   * Edits a task
   * 
   * @param {FormData} editTaskFormData The form data of the task to edit
   * 
   * @return {{success: bool, message: string}} Returns an info (object - {success, message}) whether the operation was successful.
   */
  static async editTask(editTaskFormData) {
    let taskId = editTaskFormData.get('task-id');
    let fetchUrl = "./../task/" + taskId + "/edit";

    return await fetch(fetchUrl, {
      method: 'POST',
      body: editTaskFormData,
      headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(response => {
      if(!response.ok) {
        console.warn("Something went wrong!");
      }
      return response.json();
    })
    .then(data => {
      return {
        success: data[0],
        message: data[1]
      }
    })
  }

  // Member methods

  // Private

  /**
   * Creates task option dropdown menu for a task
   * 
   * @returns {HTMLDivElement} Returns the task option UI HTML element
   */
  _getTaskOptionDropdown() {
    let menuOptionsDropdown = document.createElement("div");
    menuOptionsDropdown.classList.add(
      CLASS_NAME_TASK_OPTIONS,
      ..."position-absolute end-0 m-1".split(" ") // position the dropdown to the top-right
    );
    menuOptionsDropdown.innerHTML = `
    <div class="${CLASS_NAME_BS_DROPDOWN}">
      <!-- Menu option Dropdown toggle button -->
      <button class="btn btn-sm rounded-circle ${CLASS_NAME_BS_DROPDOWN_TOGGLE_BTN}" type="button" id="${ID_TASK_OPTIONS_DROPDOWN_TOGGLE_BTN}" data-bs-toggle="dropdown">
        ${FONTAWESOME_ICONS.SEE_MORE_ELLIPSIS}
      </button>

      <!--- Menu dropdown -->
      <ul class="${CLASS_NAME_BS_DROPDOWN_MENU} shadow">
        <li>
          <a class="${CLASS_NAME_BS_DROPDOWN_ITEM}" href="./../task/${this._taskId}">
            ${FONTAWESOME_ICONS.GO_TO_LINK}
            View Task
          </a>
        </li>

        ${this._status != 3 ?
          // Mark as complete
        `<li>
          <a class="${CLASS_NAME_BS_DROPDOWN_ITEM}" href="javascript:void(null)" onclick="markAsCompleted('${this._taskId}', this, event); ">
            ${FONTAWESOME_ICONS.MARK_AS_COMPLETED}
            Mark as Completed
          </a>
        </li>` :
        ""
        }

        <li>
          <a class="${CLASS_NAME_BS_DROPDOWN_ITEM}" href="./../task/${this._taskId}/bookmark">
            ${FONTAWESOME_ICONS.BOOKMARK}
            Bookmark Task
          </a>
        </li>

        <li>
          <a class="${CLASS_NAME_BS_DROPDOWN_ITEM}" href="javascript:copyText('${this._taskId}');">
            ${FONTAWESOME_ICONS.COPY}
            Copy Task ID
          </a>
        </li>

        <li>
          <a class="${CLASS_NAME_BS_DROPDOWN_ITEM}" href="javascript:void(''); ">
            ${FONTAWESOME_ICONS.DELETE}
            Delete Task
          </a>
        </li>
      </ul>
    </div>
    `;

    return menuOptionsDropdown;
  }

  /**
   * Create the task UI for task list view
   *
   * @returns {HTMLAnchorElement} The task UI HTML element
   */
  _displayTaskInListView() {
    // Task container
    let taskContainer = document.createElement("a");
    taskContainer.classList.add(CLASS_NAME_TASK_CONTAINER);
    taskContainer.href = `./../task/${this._taskId}`;

    // Menu options
    let menuOptionsDropdown = this._getTaskOptionDropdown();

    // Description image container
    let descriptionImgContainer = document.createElement("div");
    descriptionImgContainer.classList.add(CLASS_NAME_TASK_DESC_IMG_CONTAINER);

    // Description image
    let descriptionImg = document.createElement("img");
    descriptionImg.src = "./../../assets/imgs/no_desc_img.png"; // default for task without description image
    descriptionImg.alt = this._title;
    descriptionImg.classList.add("img-fluid");

    descriptionImgContainer.appendChild(descriptionImg);

    // Task content
    let taskContent = document.createElement("div");
    taskContent.classList.add(CLASS_NAME_TASK_CONTENT);
    taskContent.innerHTML = `
    <div class="${CLASS_NAME_TASK_DISPLAY_HEADER} px-2 py-2">
      <h5 class="${CLASS_NAME_TASK_TITLE} overflow-ellipsis">${this._title}</h5>
      <p class="${CLASS_NAME_TASK_DESCRIPTION} overflow-ellipsis">${this._description}</p>
    </div>
    <div class="${CLASS_NAME_TASK_DISPLAY_CONTENT} px-2 py-1 d-flex align-items-center justify-content-between">
      <span>${FONTAWESOME_ICONS.CHART_SIMPLE} ${this._priority}</span>
      <span class="${CLASS_NAME_TASK_STATUS} text-end">${FONTAWESOME_ICONS.CLOCK} ${this._dateCreated}</span>
    </div>
    `;

    taskContainer.append(menuOptionsDropdown, descriptionImgContainer, taskContent);

    return taskContainer;
  }

  /**
   * Create the task UI for task tab view
   * 
   * @returns {HTMLAnchorElement} The task UI HTML element
   */
  _displayTaskinTabView() {
    // Task container
    let taskContainer = document.createElement("a");
    taskContainer.href = `./../task/${this._taskId}`;
    taskContainer.classList.add(
      CLASS_NAME_TASK_CONTAINER,
      ..."d-flex align-items-center".split(" "),
      ..."list-group-item list-group-item-action".split(" ")
    );

    // Task description container
    let descriptionImgContainer = document.createElement("div");
    descriptionImgContainer.classList.add(
      CLASS_NAME_TASK_DESC_IMG_CONTAINER,
      ..."pe-1 rounded-circle".split(" ")
    );

    // Description image
    let descriptionImg = document.createElement("img");
    descriptionImg.src = "./../../assets/imgs/no_desc_img.png"; // default for task without description image
    descriptionImg.alt = this._title;
    descriptionImg.classList.add("img-fluid", "rounded-circle");
    descriptionImg.width = descriptionImg.height = 48;

    descriptionImgContainer.append(descriptionImg);

    // Task Display content
    let taskContent = document.createElement("div");
    taskContent.classList.add(CLASS_NAME_TASK_CONTENT);
    taskContent.innerHTML = `
    <div class="${CLASS_NAME_TASK_DISPLAY_HEADER} ps-1">
      <h5 class="${CLASS_NAME_TASK_TITLE} overflow-ellipsis">${this._title}</h5>
      <p class="${CLASS_NAME_TASK_DESCRIPTION} overflow-ellipsis">${this._description}</p>
    </div>
    `;

    // Task Option list
    let menuOptionsDropdown = this._getTaskOptionDropdown();

    taskContainer.append(
      descriptionImgContainer,
      taskContent,
      menuOptionsDropdown
    );

    return taskContainer;
  }

  // Display Task

  // Public

  /**
   * Creates the task UI
   * 
   * @param {string} mode Mode of display. Valid values are the constants:
   * TASK_DISPLAY_MODE_LIST and TASK_DISPLAY_MODE_TAB.
   * 
   * @return {HTMLAnchorElement} Returns the task UI display element
   */
  display(mode=TASK_DISPLAY_MODE_LIST) {
    switch(mode) {
      case TASK_DISPLAY_MODE_LIST:
        return this._displayTaskInListView();
      case TASK_DISPLAY_MODE_TAB:
        return this._displayTaskinTabView();
      default:
        throw new Error("Invalid display mode: " + mode);
    }
  }
  
}