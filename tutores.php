<?php
session_start();
require 'db_connection.php'; // Incluir la conexión a la base de datos

// Variables para mensajes
$error_message = "";
$success_message = "";

// Verificar autenticación y permisos de administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit;
}

// Manejo de creación, edición y eliminación de tutores
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_tutor'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    // Verificar si se sube una nueva imagen
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_name = $_FILES['img']['name'];
        // Usar barra invertida en la ruta
        $img_path = 'img\perfiles\\' . $img_name;
        move_uploaded_file($img_tmp_name, $img_path);
    } else {
        $img_path = 'img/perfiles/p0.jpg'; // Ruta por defecto si no se sube imagen
    }

    // Inserción en la base de datos
    if (!empty($nombre) && !empty($email) && !empty($password) && !empty($role_id)) {
        $query = "INSERT INTO empleados (nombre, email, contrasena, roles_id_Rol, img) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssis", $nombre, $email, $password, $role_id, $img_path);
        if ($stmt->execute()) {
            $success_message = "Tutor creado exitosamente.";
        } else {
            $error_message = "Error al crear el tutor: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $error_message = "Por favor, complete todos los campos.";
    }
}

// Edición de perfil de tutor
if (isset($_GET['edit_tutor'])) {
    $empleado_id = $_GET['edit_tutor'];

    // Consultar datos del tutor
    $stmt = $mysqli->prepare("SELECT * FROM empleados WHERE id_Empleado = ?");
    $stmt->bind_param("i", $empleado_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_tutor'])) {
    $empleado_id = $_POST['empleado_id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    // Verificar si se sube una nueva imagen
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_name = $_FILES['img']['name'];
        // Usar barra invertida en la ruta
        $img_path = 'img\\' . $img_name;
        move_uploaded_file($img_tmp_name, $img_path);
    } else {
        // Si no se sube una nueva imagen, mantener la existente
        $img_path = $_POST['img_path']; // Mantener la imagen existente
    }

    // Actualización de los datos del tutor
    if (!empty($nombre) && !empty($email) && !empty($password) && !empty($role_id)) {
        $query = "UPDATE empleados SET nombre = ?, email = ?, contrasena = ?, roles_id_Rol = ?, img = ? WHERE id_Empleado = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssisi", $nombre, $email, $password, $role_id, $img_path, $empleado_id);
        if ($stmt->execute()) {
            $success_message = "Tutor actualizado exitosamente.";
        } else {
            $error_message = "Error al actualizar el tutor: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $error_message = "Por favor, complete todos los campos.";
    }
}

// Consultar todos los tutores y roles
$tutores_result = $mysqli->query("SELECT * FROM empleados WHERE roles_id_Rol = 2");
$roles_result = $mysqli->query("SELECT * FROM roles");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Tutores</title>
    <link rel="stylesheet" href="css/tutores.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">Desarrollo de aplicaciones web</div>
        <ul class="nav-links">
            <li><a href="administracion.php">Inicio</a></li>
            <li><a href="cursos.php">Cursos</a></li>
            <li><a href="tutores.php">Tutores</a></li>
            <li><a href="estudiantes.php">Estudiantes</a></li>
            <li><a href="cursoTutor.php">Cursos y tutores</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="container">
        <h1>Administrar Tutores</h1>
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulario para crear tutor -->
        <form method="POST" enctype="multipart/form-data">
            <h2>Crear Nuevo Tutor</h2>
            <input type="text" name="nombre" placeholder="Nombre del tutor" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="role_id" required>
                <option value="">Seleccione Rol</option>
                <?php while ($role = $roles_result->fetch_assoc()): ?>
                    <option value="<?php echo $role['id_Rol']; ?>"><?php echo htmlspecialchars($role['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
            <input type="file" name="img">
            <button type="submit" name="create_tutor">Crear Tutor</button>
        </form>

        <!-- Listado de tutores -->
        <h2>Tutores</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php while ($tutor = $tutores_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tutor['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($tutor['email']); ?></td>
                    <td><?php
                        // Obtener nombre del rol
                        $role_id = $tutor['roles_id_Rol'];
                        $role_query = $mysqli->query("SELECT nombre FROM roles WHERE id_Rol = $role_id");
                        $role = $role_query->fetch_assoc();
                        echo $role['nombre'];
                    ?></td>
                    <td>
                        <a href="?edit_tutor=<?php echo $tutor['id_Empleado']; ?>">Editar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php if (isset($empleado)): ?>
            <!-- Formulario para editar tutor -->
            <form method="POST" enctype="multipart/form-data">
                <h2>Editar Tutor</h2>
                <input type="hidden" name="empleado_id" value="<?php echo $empleado['id_Empleado']; ?>">
                <input type="hidden" name="img_path" value="<?php echo $empleado['img']; ?>">
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>" required>
                <input type="email" name="email" value="<?php echo htmlspecialchars($empleado['email']); ?>" required>
                <input type="password" name="password" value="<?php echo htmlspecialchars($empleado['contrasena']); ?>" required>
                <select name="role_id" required>
                    <?php
                    // Consultar roles
                    $roles_result_edit = $mysqli->query("SELECT * FROM roles");
                    while ($role = $roles_result_edit->fetch_assoc()):
                    ?>
                        <option value="<?php echo $role['id_Rol']; ?>" <?php echo ($role['id_Rol'] == $empleado['roles_id_Rol']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($role['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="file" name="img">
                <button type="submit" name="update_tutor">Actualizar Tutor</button>
            </form>
        <?php endif; ?>
    </div>
</main>
</body>
</html>


