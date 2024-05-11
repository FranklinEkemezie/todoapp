<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 Not Found - ToDoApp</title>

  <style>
    @import url("/../assets/css/default.css");

    body {
      width: 100vw;
      height: 100vh;

      position: relative;
    }

    header {
      position: sticky;
      top: 0;
      text-align: center;
      padding: calc(2 * var(--spacing-3));
    }

    main {
      text-align: center;
      padding: var(--spacing-2);
    }

    footer {
      position: sticky;
      top: 100%;
      text-align: center;
      padding: var(--spacing-3);
      border-top: 1px solid var(--colour-dark-grey);
    }
  </style>
</head>
<body>
  <header>
    <h1 class="fw-bold">404 Not Found Error</h1>
  </header>

  <main>
    <p>The resource <b><?php insert($request_url); ?></b> was not found! <br> Check if you spelt it correctly and try again</p>
    <div>
      <img src="/../assets/imgs/404_Error.svg" alt="404 Not Found Image" width="240" height="240">
    </div>
  </main>

  <footer>
    <p>&copy; todoapp 2024 - All Rights Reserved.</p>
  </footer>
</body>
</html>