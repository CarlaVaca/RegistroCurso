<?php
// Incluir el archivo de conexión
include('db_connection.php');

// Inicializar una variable para los mensajes de error
$error_message = "";

// Comprobar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar y obtener los datos del formulario
    $nombre = mysqli_real_escape_string($mysqli, trim($_POST['nombre']));
    $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    $contrasena = trim($_POST['contrasena']);
    $confirm_contrasena = trim($_POST['confirm_contrasena']);

    // Verificar que los campos no estén vacíos
    if (empty($nombre) || empty($email) || empty($contrasena) || empty($confirm_contrasena)) {
        $error_message = "Por favor, complete todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "El correo electrónico no es válido.";
    } elseif ($contrasena !== $confirm_contrasena) {
        $error_message = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el correo electrónico ya está registrado
        $query = "SELECT * FROM registro_personas WHERE email = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "El correo electrónico ya está registrado.";
        } else {
            // Guardar la contraseña tal como está sin encriptarla
            // Insertar el nuevo usuario en la base de datos
            $insert_query = "INSERT INTO registro_personas (nombre, email, roles_id_Rol, contrasena) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert_query);
            $stmt->bind_param("sss", $nombre, $email, $roles_id_Rol, $contrasena);

            if ($stmt->execute()) {
                // Redirigir a la página de login si el registro es exitoso
                header("Location: login.php");
                exit;
            } else {
                $error_message = "Error al registrar el usuario: " . $mysqli->error;
            }
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
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<header>
    <nav class="navbar">
        <div class="logo">Desarrollo de aplicaciones web</div>
        <ul class="nav-links">
            <li><a href="index.html">Inicio</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="registro.php">Registro</a></li>
        </ul>
    </nav>
</header>
<body>
    <!-- Formulario -->
    <div class="register-container">
        <h1>Registro de Usuario</h1>
        <?php if (!empty($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
        <?php if (!empty($success_message)) { ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php } ?>
        <form action="registro.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Ingresa tu nombre">
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <label for="confirm_contrasena">Confirmar Contraseña</label>
                <input type="password" id="confirm_contrasena" name="confirm_contrasena" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn">Registrarse</button>
            <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </form>
    </div>
</body>
</html>





