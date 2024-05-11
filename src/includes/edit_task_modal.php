<?php
/**
 * -------------------------
 * Edit Task Modal
 * -------------------------
 */
?>

<section id="edit-task-section">
  <form action="" method="post" id="edit-task-form" data-tdp-task-id="<?php insertc([$task, 'getTaskId']); ?>">
    <div id="edit-task-static-backdrop" class="task-modal modal modal-fullscreen-sm-down fade" data-bs-backdrop="static" data-bs-keyword="false" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
          <!-- Modal header -->
            <div class="modal-header">
              <h5 class="modal-title">📝 Edit Task</h5>
              <button type="button" class="modal-close-btn btn" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-close fs-5"></i>
              </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
              <input type="hidden" class="d-none" name="task-id" value="<?php insertc([$task, 'getTaskId']); ?>">
              <!-- Modal Content goes here -->
              <!-- Ttile -->
              <div class="form-field title-field">
                <label for="task-title">Title <span class="text-danger">*</span></label>
                <input type="text" name="task-title" id="task-title" value="<?php insertc([$task, 'getTitle']); ?>">
              </div>
              <!-- Description -->
              <div class="form-field description-field">
                <label for="task-description">Description <span class="text-danger">*</span></label>
                <textarea name="task-description" id="task-description" cols="20" rows="4"><?php insertc([$task, 'getDescription']); ?></textarea>
              </div>
              <!-- Date due -->
              <div class="form-field date-due-field">
                <label for="date-due">Date Due</label>
                <div class="row">
                  <div class="col-md-6">
                    <input type="date" name="date-due" id="date-due" value="<?php insertc([$task, 'getDateDue'], ["Y-m-d"]); ?>">
                  </div>
                  <div class="col-md-6">
                    <input type="time" name="time-due" id="time-due" value="<?php insertc([$task, 'getDateDue'], ["h:i"]); ?>">
                  </div>
                </div>
              </div>
              <!-- Priority/ Category -->
              <div class="row">
                <div class="col-md-4">
                  <div class="form-field priority-field">
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority" class="py-1 d-block w-100">
                      <?php
                      // Priority level
                      $priority = (int) $task -> getPriority(true);
                      ?>
                      <option <?php mark_as_selected_if_value(5, $priority); ?>>5. Urgent - Important</option>
                      <option <?php mark_as_selected_if_value(4, $priority); ?>>4. Urgent - Not Important</option>
                      <option <?php mark_as_selected_if_value(3, $priority); ?>>3. Not Urgent - Important</option>
                      <option <?php mark_as_selected_if_value(2, $priority); ?>>2. Not Urgent - Not Important</option>
                      <option <?php mark_as_selected_if_value(1, $priority); ?>>1. Leisure</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-field category-field">
                    <label for="category">Category</label>
                    <select name="category" id="category" class="py-1 d-block w-100">
                      <?php
                      // Category
                      $category = (string) $task -> getCategory();
                      ?>
                      <option value="Personal" <?php mark_as_selected_if_value("Personal", $category); ?>>Personal</option>
                      <option value="Work" <?php mark_as_selected_if_value("Work", $category); ?>>Work</option>
                      <option value="School" <?php mark_as_selected_if_value("School", $category); ?>>School</option>
                      <option value="Business" <?php mark_as_selected_if_value("Business", $category); ?>>Business</option>
                      <option value="Others" <?php mark_as_selected_if_value("Others", $category); ?>>Others</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-field status-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="py-1 d-block w-100">
                      <?php
                      // Status
                      $status = $task -> getStatus(true);
                      ?>
                      <option <?php mark_as_selected_if_value(1, $status); ?>>To Do</option>
                      <option <?php mark_as_selected_if_value(2, $status); ?>>In Progress</option>
                      <option <?php mark_as_selected_if_value(3, $status); ?>>Completed</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer align-items-center justify-content-between">
              <button type="submit" id="edit-task-submit-btn" class="btn btn-primary rounded-3 loading">Edit Task</button>
              <button type="button" id="edit-task-cancel-btn" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            </div>
            
        </div>
      </div>
    </div>
  </form>

</section>