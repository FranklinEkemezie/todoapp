const edit_task_form = select("#edit-task-form");
const edit_task_submit_btn = select("#edit-task-submit-btn", false, edit_task_form);
const edit_task_cancel_btn = select("#edit-task-cancel-btn", false, edit_task_form);

on(edit_task_form, "submit", async function(e) {
  e.preventDefault();

  // Add loader and modify the edit task submit button to indicate loading
  let spinner = document.createElement("i");
  spinner.classList.add("spinner-border", "spinner-border-sm", "ms-1");

  edit_task_submit_btn.innerHTML = "Editing Task...";
  edit_task_submit_btn.appendChild(spinner);
  edit_task_submit_btn.disabled = true;

  // Display loader
  let loader = new initLoader("Editing Task...");
  loader.show();
  
  // Submit form data for editing
  let edit_task_promise = new Promise(async function(resolve, reject) {
    let edit_task_form_data = new FormData(edit_task_form);
    let result = await Task.editTask(edit_task_form_data);

    if(result.success) {
      setTimeout(() => resolve(result), 2 * 1000);
    } else {
      setTimeout(() => reject(result), 2 * 1000);
    }

    // Return the Edit Task button back to normal
    edit_task_submit_btn.innerHTML = "Edit Task";
    edit_task_submit_btn.disabled = false;

    edit_task_cancel_btn.click();
  });

  edit_task_promise.then(
    // Display success message
    response => displayNotificationMessage(response.message, "success"),
    reason => displayNotificationMessage(reason.message, reason.success)
  )
  .then(notification_msg_el => {
    // Add event listener to reload
    on(notification_msg_el, "hidden.bs.toast", () => window.location.reload());
  })
  .catch(reason => {
    console.log("Something went wrong");
    displayNotificationMessage("Something went wrong " + reason, "success");
  })
  .finally(() => {
    loader.hide(); // remove the loader
  });
});
