<?php
// Incluir el archivo de conexión a la base de datos
require_once 'db_connection.php';

// Incluir el archivo de sesión (sesion.php) para manejar el inicio de sesión
require_once 'sesion.php';

// Si el usuario ya está logueado, redirigir a la página de inicio
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: administracion.php");
    exit;
}

// Variables para manejo de errores
$error = "";

// Verificar si el formulario de login fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar si los campos no están vacíos
    if (empty($email) || empty($password)) {
        $error = "Por favor, ingrese ambos campos.";
    } else {
        // Consulta SQL para verificar si el usuario existe
        $sql = "SELECT id_Empleado, nombre, email, password, roles_id_Rol FROM empleados WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Verificar si el email existe
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $nombre, $email_db, $password_db, $role);
            $stmt->fetch();

            // Verificar la contraseña
            if (password_verify($password, $password_db)) {
                // Guardar información del usuario en la sesión
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['user_name'] = $nombre;
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;

                // Redirigir al usuario según el rol
                if ($role == 4) { // Estudiante
                    header("Location: administracion.php");
                } else {
                    header("Location: otros_roles.php"); // Redirigir a otra página según el rol
                }
                exit;
            } else {
                $error = "La contraseña es incorrecta.";
            }
        } else {
            $error = "No se encuentra una cuenta registrada con ese correo electrónico.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/body_styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">Desarrollo de aplicaciones web</div>
            <ul class="nav-links">
                <li><a href="index.html">Inicio</a></li>
                <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false): ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                    <li><a href="registro.php">Registro</a></li>
                <?php else: ?>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="content-container">
        <h1>Iniciar sesión</h1>

        <!-- Mostrar errores si existen -->
        <?php if (isset($error) && $error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form method="POST" action="login.php" class="login-form">
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required class="form-control">
            </div>

            <button type="submit" class="btn-submit">Iniciar sesión</button>
        </form>
    </main>

    <footer>
        <div class="footer-container">
            <p>Dirección: Calle Principal #123, Ciudad, País</p>
            <p>Email: contacto@ejemplo.com | Teléfono: +123 456 7890</p>
            <p>&copy; 2024 Mi Aplicación. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>


