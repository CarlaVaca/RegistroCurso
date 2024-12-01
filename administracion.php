<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 1) { // El rol de administrador es 1
    // Si no está iniciado o no es administrador, redirigir a login.php
    echo "Acceso denegado. Necesitas permisos de administrador.";
    exit; // Detener ejecución si no tiene permisos
}

// Si el usuario tiene rol de administrador, muestra la página de administración
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <!-- Menú de navegación -->
  <header>
    <nav class="navbar">
      <div class="logo">Desarrollo de aplicaciones web</div>
      <ul class="nav-links">
        <li><a href="index.html">Inicio</a></li>
        <li><a href="cursos.php">Cursos</a></li>
        <li><a href="tutores.php">Tutores</a></li>
        <li><a href="estudiantes.php">Estudiantes</a></li>
          <li><a href="cursoTutor.php">Cursos y tutores</a></li>
        <li><a href="logout.php">Cerrar sesión</a></li>
      </ul>
    </nav>
  </header>

  <!-- Cuerpo de la página -->
  <main class="content-container">
    <h1>Panel de Administración</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    <p>Aquí podrás gestionar usuarios, contenido y más.</p>

  </main>

  <!-- Pie de página -->
  <footer id="contact">
    <div class="footer-container">
      <p>Dirección: Calle Principal #123, Ciudad, País</p>
      <p>Email: contacto@ejemplo.com | Teléfono: +123 456 7890</p>
      <p>&copy; 2024 Mi Aplicación. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script>

</body>
</html>


