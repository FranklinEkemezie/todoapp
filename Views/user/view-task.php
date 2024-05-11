<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php insertc([$task, 'getTitle']); ?> | ToDoApp</title>

  <link rel="stylesheet" href="./../../assets/css/header.css">
  <link rel="stylesheet" href="./../../assets/css/create_task_modal.css">

  <link rel="stylesheet" href="./../../assets/css/task.css">

  <!-- Bootstrap JS -->
  <script src="./../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>

  <!-- Main script -->
  <script src="./../../assets/js/functions.js" defer></script>
  <script src="./../../assets/js/utils.js" defer></script>
  <script src="./../../assets/js/domain/Task.js" defer></script>

  <script src="./../../assets/js/edit_task_modal.js" defer></script>

  <!-- Initiate BS component -->
  <script src="./../../assets/js/bs_init.js" defer></script>
</head>
<body>
  <!-- Header -->
  <?php includePageHeader("Task", [
    "user" => $user,
    "task" => $task,
    "no_of_tasks_in_progress" => $no_of_tasks_in_progress
  ]); ?> 

  <main class="m-2 m-md-4">
    <!-- Create Task Modal -->
    <?php require_once __DIR__ . "/../../src/includes/create_task_modal.php";  ?>

    <!-- Edit Task Modal -->
    <?php require_once __DIR__ . "/../../src/includes/edit_task_modal.php"; ?>

    <section class="border rounded-3">
      <div class="title pt-3 px-3 border-bottom bg-light">
        <div class="row">
          <div class="col-11">
            <h2 class="fw-bold"><?php insertc([$task, 'getTitle']); ?></h2>
            <p class=""><?php insertc([$task, 'getDescription']); ?></p>
          </div>

          <div class="col-md-1 d-none d-md-block">
            <i class="fa fa-4x"><i class="fa fa-list"></i></i>
          </div>
        
          <div class="d-flex align-items-center justify-content-end">
            <p class="small me-2"><i class="fa fa-clock"></i> Created: <i class="fw-bold"><?php insertc([$task, 'getDateCreated'], [" D, j M 'y"]); ?></i></p>
            <p class="small ms-2"><i class="fa fa-calendar"></i> Deadline: <i class="fw-bold"><?php insertc([$task, 'getDateDue'], ["D, j M 'y"]) ?></i></p>
          </div>
        </div>
      </div>

      <div class="status pt-3 px-3 border-bottom">
        <h3 class="fw-bold">Status</h3>
        <p><?php insertc([$task, 'getStatus']) ?></p>
      </div>

      <div class="category pt-3 px-3 border-bottom">
        <h3 class="fw-bold">Category</h3>
        <p><?php insertc([$task, 'getCategory']); ?></p>
      </div>

      <div class="priority pt-3 px-3 border-bottom">
        <h3 class="fw-bold">Priority</h3>
        <p><?php insertc([$task, 'getPriority']); ?></p>
      </div>

      <div class="attachements pt-3 px-3 border-bottom">
        <h3 class="fw-bold">Attachments</h3>
        <p><i class="fa fa-clock"></i></p>
      </div>

      <div class="tags pt-3 px-3">
        <h3 class="fw-bold">Tags</h3>
        <p><?php insertc([$task, 'getCategory']); ?></p>
      </div>

    </section>

  </main>

  <footer class="m-2 m-md-4 border px-3 py-3 bg-light position-sticky bottom-0">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <!-- <a href="./<--?php insertc([$task, 'getTaskId']); ?>/edit" class="btn btn-primary"><i class="fa fa-pen-to-square me-1"></i> Edit Task</a> -->
        <button class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#edit-task-static-backdrop"><i class="fa fa-pen-to-square me-1"></i> Edit Task</button>

      </div>

      <div>
        <a href="./<?php insertc([$task, 'getTaskId']); ?>/delete" class="btn btn-danger"><i class="fa fa-trash me-1"></i> Delete Task</a>
      </div>
    </div>
  </footer>
</body>

</html>