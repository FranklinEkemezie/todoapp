// Select HTML elements here

/**
 * ---------------------------------
 * Add Event Listener
 * ---------------------------------
 */
// Display the task
on(document, "DOMContentLoaded", async function() {
  const TASK_DISPLAY_CONTAINER = {
    to_do: select("#not-started .task-display-container"),
    in_progress: select("#in-progress .task-display-container"),
    completed: select("#completed .task-display-container")
  }

  // To Do tasks
  let to_do_tasks = await Task.ToDo.fetchTasks() || [];
  if(to_do_tasks.length !== 0) {
    to_do_tasks.forEach(task => TASK_DISPLAY_CONTAINER.to_do.append(Task.getTask(task).display()));
  } else {
    TASK_DISPLAY_CONTAINER.to_do.style.display = "block";
    TASK_DISPLAY_CONTAINER.to_do.innerHTML = "<p class='text-center fst-italic'><i class='fa fa-ban'></i> No <b>To Do</b> Tasks</p>";
  }
  // In progress
  let in_progress_tasks = await Task.InProgress.fetchTasks() || [];
  if(in_progress_tasks.length !== 0) {
    in_progress_tasks.forEach(task => TASK_DISPLAY_CONTAINER.in_progress.append(Task.getTask(task).display()))
  } else {
    TASK_DISPLAY_CONTAINER.in_progress.style.display = "block";
    TASK_DISPLAY_CONTAINER.in_progress.innerHTML = "<p class='text-center fst-italic'><i class='fa fa-ban'></i> No Tasks <b>In Progress</b></p>";
  }

  // Completed
  let completed_tasks = await Task.Completed.fetchTasks() || [];
  if(completed_tasks.length !== 0) {
    completed_tasks.forEach(task => TASK_DISPLAY_CONTAINER.completed.append(Task.getTask(task).display()));
  } else {
    TASK_DISPLAY_CONTAINER.completed.style.display = "block";
    TASK_DISPLAY_CONTAINER.completed.innerHTML = "<p class='text-center fst-italic'><i class='fa fa-ban'></i> No <b>Completed</b> Tasks</p>";
  }

  // Remove the loader
  let loader = select("#tasks .loader");
  loader.remove();
});

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
  console.log(formData.get('task-id'), formData.get('status'));
  let res = await Task.editTask(formData);

  let msg = displayNotificationMessage(
    res.message,
    res.success ? "success" : "danger"
  );

  on(msg, "hidden.bs.toast", () => window.location.reload())
}