class Task {
  #task_id;
  #title;
  #description;
  #status;
  #priority;
  #date_created;
  #date_due;

  constructor(
    task_id,
    title,
    description,
    status,
    priority,
    date_created,
    date_due
  ) {
    this.#task_id = task_id;
    this.#title = title;
    this.#description = description;
    this.#status = status;
    this.#priority = priority;
    this.#date_created = date_created;
    this.#date_due = date_due;
  }

  static ToDo = {
    fetchTasks: async () => await Task.#fetchTasks(1)
  }

  static InProgress = {
    fetchTasks: async () => await Task.#fetchTasks(2)
  }

  static Completed = {
    fetchTasks: async () => await Task.#fetchTasks(3)
  }

  /**
   * A Task object from the given object
   * 
   * @param {object} task_object An object with properties of a task
   * 
   * @returns {Task} A Task object
   */
  static getTask(task_object) {
    return new Task(
      task_object.task_id,
      task_object.title,
      task_object.description,
      task_object.status,
      task_object.priority,
      task_object.date_created,
      task_object.date_due
    );
  }

  static async #fetchTasks(status=null) {
    let param = status !== null ? "status=" + status : "";
    let fetch_url = "./../../tasks?" + param;

    return await fetch(fetch_url)
      .then(response => {
        if(!response.ok)
          console.warn("Something went wrong");

        return response.json();
      })
      .then(data => data)
      .catch(error => {
        console.error("An error occured: " + error);
      });
  }

  /**
   * Edits a Task
   * 
   * @param {FormData} edit_task_form_data The form data of the task to edit
   * 
   */
  static async editTask(edit_task_form_data) {
    let task_id = edit_task_form_data.get('task-id');
    let fetch_url = "./../task/" + task_id + "/edit";

    return await fetch(fetch_url, {
      method: 'POST',
      body: edit_task_form_data,
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
      };
    })
    .catch(error => {
      console.log("An error occurred: " + error);
    });
  }


  /**
   * Display the task UI in the dashboard page
   * 
   * @returns {HTMLAnchorElement} Returns the task display HTML element
   */
  display() {
    // Task container
    let task_container = document.createElement("a");
    task_container.classList.add("task-container");
    task_container.href = `./../task/${this.#task_id}`;

    // Menu options
    let menu_options_dropdown = document.createElement("div");
    menu_options_dropdown.classList.add("task-options");
    menu_options_dropdown.innerHTML = `
    <div class="dropdown">
      <button class="btn btn-sm rounded-circle dropdown-toggle" type="button" id="task-display-dropdown-menu" data-bs-toggle="dropdown">
        <span class="i fa fa-ellipsis"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end small shadow">
        <li><a class="dropdown-item" href="./../task/${this.#task_id}/"><i class="fa fa-up-right-from-square"></i> View Task</a></li>
        ${this.#status != 3 ? 
          `<li><a class="dropdown-item" href="javascript:void(null)" onclick="markAsCompleted('${this.#task_id}', this, event)"><i class="fa fa-check-double"></i> Mark as Completed</a></li>` : 
          ""
        }
        <li><a class="dropdown-item" href="./../task/${this.#task_id}/bookmark"><i class="fa fa-bookmark"></i> Bookmark Task</a></li>
        <li><a class="dropdown-item" href="javascript:copyText('${this.#task_id}')"><i class="fa fa-copy"></i> Copy Task Link</a></li>
        <li><a class="dropdown-item" href="javascript:void('')"><i class="fa fa-trash-can"></i>Delete Task</a></li>
      </ul>
    `;

    // Description image container
    let description_img_container = document.createElement("div");
    description_img_container.classList.add("description-img-container");

    // Description image
    let description_img = document.createElement("img");
    description_img.src = "./../../assets/imgs/no_desc_img.png";
    description_img.alt = this.#title;
    description_img.classList.add("img-fluid");

    description_img_container.appendChild(description_img);

    // Task content
    let task_content = document.createElement("div");
    task_content.classList.add("task-content");
    task_content.innerHTML = `
    <div class="task-display-header px-2 py-1">
      <h5 class="task-title">${this.#title}</h5>
      <p class="task-description">${this.#description}</p>
    </div>
    <div class="task-display-content px-2 py-1 d-flex align-items-center justify-content-between">
      <span><i class="fa fa-chart-simple"></i> ${this.#priority}</span>
      <span class="task-status text-end"><i class="fa fa-clock"></i> ${this.#date_created}</span>
    </div>
    `;

    task_container.append(
      menu_options_dropdown,
      description_img_container,
      task_content
    );

    return task_container;
  }


}

