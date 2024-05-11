<?php
/**
 * -----------------------
 * Create Task Modal
 * -------------------------
 */
?>

<section id="add-new-task-section">
  <form action="/task/create" method="post" id="add-new-task-form">
    <div id="add-new-task-static-backdrop" class="task-modal modal modal-fullscreen-sm-down fade" data-bs-backdrop="static" data-bs-keyword="false" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
          <!-- Modal header -->
            <div class="modal-header">
              <h5 class="modal-title">📌 Add New Task</h5>
              <button type="button" class="modal-close-btn btn" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-close fs-5"></i>
              </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
              <!-- Modal Content goes here -->
              <div class="form-field title-field">
                <label for="task-title">Title <span class="text-danger">*</span></label>
                <input type="text" name="task-title" id="task-title">
              </div>
              <div class="form-field description-field">
                <label for="task-description">Description <span class="text-danger">*</span></label>
                <textarea name="task-description" id="task-description" cols="20" rows="4"></textarea>
              </div>
              <div class="form-field date-due-field">
                <label for="date-due">Date Due</label>
                <div class="row">
                  <div class="col-md-6">
                    <input type="date" name="date-due" id="time-due">
                  </div>
                  <div class="col-md-6">
                    <input type="time" name="time-due" id="time-due">
                  </div>
                </div>
              </div>
              <!-- Priority/ Category -->
              <div class="row">
                <div class="col-md-4">
                  <div class="form-field priority-field">
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority" class="py-1 d-block w-100">
                      <option value="5">5. Urgent - Important</option>
                      <option value="4">4. Urgent - Not Important</option>
                      <option value="3">3. Not Urgent - Important</option>
                      <option value="2">2. Not Urgent - Not Important</option>
                      <option value="1" selected>1. Leisure</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-field category-field">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="py-1 d-block w-100">
                      <option value="Personal">Personal</option>
                      <option value="Work">Work</option>
                      <option value="School">School</option>
                      <option value="Business">Business</option>
                      <option value="Others">Others</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-field status-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="py-1 d-block w-100">
                      <option value="1">To Do</option>
                      <option value="2">In Progress</option>
                      <option value="3">Completed</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer align-items-center justify-content-between">
              <button type="submit" class="btn btn-primary rounded-3">Create Task</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>


        </div>
      </div>
    </div>
  </form>

</section>