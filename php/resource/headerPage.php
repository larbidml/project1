<!DOCTYPE html>
<html lang="es-ES">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="../../node_modules/bootstrap-icons/icons/ubuntu.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubuntu-Server</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../../css/styles.css">

  <!-- Scripts necesarios -->
  <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" defer></script>

  <!-- Si necesitas jQuery y datepicker -->
  <script src="../../node_modules/jquery/dist/jquery.min.js" defer></script>
  <script src="../../node_modules/bootstrap-datepicker/js/bootstrap-datepicker.js" defer></script>
  <script src="../../node_modules/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js" defer></script>
</head>

<body>
<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <i class="bi bi-house-door-fill text-light" style="font-size: 2rem;"></i>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
      aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar"
      aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menú Principal</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>

      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">


          <li class="nav-item"><a class="nav-link text-danger" href="../socios/buscar.php">Contactos2</a></li>

        </ul>


      </div>
    </div>
  </div>
</nav>
