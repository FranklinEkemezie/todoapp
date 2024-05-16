<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Dashboard | ToDoApp</title>

  <link rel="shortcut icon" href="./../../assets/imgs/icons8-to-do-96(-xxxhdpi).png" type="image/x-icon">

  <link rel="stylesheet" href="./../../assets/css/header.css">
  <link rel="stylesheet" href="./../../assets/css/create_task_modal.css">

  <link rel="stylesheet" href="./../../assets/css/dashboard.css">

  <!-- Bootstrap JavaScript -->
  <script src="./../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>

  <!-- Main Script -->
  <script src="./../../assets/js/functions.js" defer></script>
  <script src="./../../assets/js/utils.js" defer></script>
  <script src="./../../assets/js/FontAwesomeIcons.js" defer></script>
  <script src="./../../assets/js/domain/Task.js" defer></script>
  <script src="./../../assets/js/dashboard.js" defer></script>

  <!-- Init Bootstrap Components -->
  <script src="./../../assets/js/bs_init.js" defer></script>

</head>
<body>
  <!-- Header -->
  <?php includePageHeader("My Dashboard", [
    "user" => $user,
    "no_of_tasks_in_progress" => count($tasks['in_progress'])
  ]) ?>

  <!-- Main -->
  <main>
    <!-- Side bar content -->
    <section id="sidebar-content" class="offcanvas offcanvas-start border-right" data-bs-backdrop="true" data-bs-scroll="true" tabindex="-1">      
      <!-- Offcanvas Header -->
      <div class="offcanvas-header d-lg-none text-muted border-bottom">
        <h1 class="fs-4 fw-bold">ToDoApp</h1>
        <button type="button" class="btn " data-bs-dismiss="offcanvas">
          <i class="fa fs-4 fa-close"></i>
        </button>
      </div>

      <!-- Offcanvas Body -->
      <div class="offcanvas-body">
        <div class="container-fluid profile-display-container py-2">
          <div class="profile-img-container my-2">
            <img class="img-fluid d-block mx-auto" src="./../../assets/vendor/fontawesome/svgs/solid/user.svg" alt="" width="80" height="80">
          </div>
          <div class="text-center my-2 py-2">
            <span class="fs-5 lh-1 mb-0 mt-2 fw-bold d-inline-block w-100 overflow-ellipsis"><?php insertc([$user, 'getUsername']); ?></span>
            <span class="fs-6 text-muted mt-0 mb-2 lh-1 d-inline-block w-100 overflow-ellipsis"><?php insertc([$user, 'getEmail']) ?></span>
          </div>
          <div class="text-center my-2">
            <a href="./settings" class="btn btn-secondary"><i class="fa fa-user me-1"></i> View Profile</a>
          </div>
        </div>
      </div>

      <!-- Offcanvas Footer -->
      <div class="offcanvas-footer py-2">
        <div class="list-group list-group-flush border-top border-bottom">
          <a href="./settings" class="list-group-item list-group-item-action"><i class="fa fa-gear me-1"></i> Settings</a>
          <a href="./settings?tab=manage_account" class="list-group-item list-group-item-action"><i class="fa fa-user-gear"></i> Manage Account</a>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section id="main-content">
      <div class="container-fluid">
        <!-- Add New Task Modal --> 
        <?php require __DIR__ . "/../../src/includes/create_task_modal.php"; ?>

        <!-- Profile Dashboard Banner -->
        <div class="dashboard-banner shadow p-4 my-3 bg-primary text-light">
          <div class="d-flex align-items-center justify-content-between">
            <div class="">
              <p>
                <span class="fs-4 fw-light"><?php insertc('greet') ?>, <i class="fa fa-beat d-none">👋</i></span> <br>
                <span class="username display-6 fw-bold"><?php insertc([$user, 'getUsername']); ?></span>
              </p>
              <div class="d-flex">
                <button type="button" class="btn ps-0 btn-sm text-white"><span class="badge rounded-pill text-white text-center bg-danger"><?php insert(count($tasks['not_started'])); ?></span> To Do</button>
                <button type="button" class="btn btn-sm text-white"><span class="badge rounded-pill text-white text-center bg-secondary"><?php insert(count($tasks['in_progress'])); ?></span> In Progress</button>
                <button type="button" class="btn btn-sm text-white"><span class="badge rounded-pill text-white text-center bg-success"><?php insert(count($tasks['completed'])); ?></span> Completed</button>
              </div>
            </div>

            <!-- Profile banner picture -->
            <div class="">
              <img src="/user/profile/photo" alt="" width="80px" height="80px" class="rounded-circle img-fluid" data-todoapp-update="profile-img" />
            </div>
          </div>
        </div>

        <!-- Search form -->
        <div class="container-fluid my-4">
          <form action="/search" id="search-form">
            <div class="container-fluid search-field px-3 py-2 shadow-sm">
              <label for="search-input"><i class="fa fa-search search-icon pe-2"></i></label>
              <input type="search" name="search-keywords" id="search-input" placeholder="Search your tasks..." list="search-input-datalist" required>
              <datalist id="search-input-datalist">
                <option value="Clean the house">
                <option value="Mop the kitchen">
                <option value="Boil the eggs">
                <option value="Complete my PHY 101 project">
                <option value="Fill Maths manual">
              </datalist>
            </div>
          </form>
        </div>

        <!-- Display Tasks here -->
        <section id="tasks">
          <!-- Switch View -->
          <div class="form-check form-switch switch-view d-flex align-items-center justify-content-end my-2">
            <input class="form-check-input me-2" type="checkbox" id="switch-view" onchange="switchTaskDisplayView()">
            <label class="form-check-label" for="switch-view">Switch View</label>
          </div>

          <!-- Loader -->
          <div class="loader d-flex align-items-center justify-content-center mt-5">
            <div class="spinner-grow text-dark me-2"></div>
            <span>Loading...</span>
            <a href="./../"></a>
          </div>

          <!-- TASK VIEW GOES HERE -->


        </section>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>

  </footer>
</body>
</html>