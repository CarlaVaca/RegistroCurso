<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <!-- Menú de navegación -->
  <header>
    <nav class="navbar">
      <div class="logo">Desarrollo de Aplicaciones Web</div>
      <ul class="nav-links">
        <li><a href="index.html">Inicio</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="registro.php">Registro</a></li>
      </ul>
    </nav>
  </header>

  <!-- Contenedor de inicio de sesión -->
  <div class="content-container login-container">
    <h1>Iniciar Sesión</h1>
    <!-- Mostrar mensaje de error -->
    <?php if (!empty($error_message)) : ?>
      <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="contrasena" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn">Ingresar</button>
      <p class="register-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </form>
  </div>

  <!-- Pie de página -->
  <footer>
    <p>&copy; 2024 Desarrollo de Aplicaciones Web. Todos los derechos reservados.</p>
    <p><a href="privacidad.html">Política de privacidad</a></p>
  </footer>
</body>
</html>
