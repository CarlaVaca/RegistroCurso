<?php
session_start();
require 'db_connection.php'; // Incluir la conexión a la base de datos

// Variables para mensajes
$error_message = "";
$success_message = "";

// Manejo de autenticación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta en la tabla registro_personas
    $stmt = $mysqli->prepare("SELECT id_Persona, contrasena, roles_id_Rol, nombre FROM registro_personas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password == $row['contrasena']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['id_Persona'];
            $_SESSION['role'] = $row['roles_id_Rol'];
            $_SESSION['user_name'] = $row['nombre'];

            if ($row['roles_id_Rol'] == 4) {
                header("Location: galeria.php");
                exit;
            } else {
                $error_message = "Rol no válido en registro_personas.";
            }
        } else {
            $error_message = "Contraseña incorrecta.";
        }
    } else {
        // Si no encuentra en registro_personas, buscar en empleados
        $stmt = $mysqli->prepare("SELECT id_Empleado, contrasena, roles_id_Rol, nombre FROM empleados WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($password == $row['contrasena']) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['id_Empleado'];
                $_SESSION['role'] = $row['roles_id_Rol'];
                $_SESSION['user_name'] = $row['nombre'];

                if ($row['roles_id_Rol'] == 1) {
                    header("Location: administracion.php");
                    exit;
                } else {
                    $error_message = "Rol no válido en empleados.";
                }
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "Email no encontrado.";
        }
    }
    $stmt->close();
}

// Verificar autenticación y permisos de administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit;
}

// Manejo de creación, edición y eliminación de cursos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_course'])) {
    $curso_nombre = $_POST['curso_nombre'];
    $curso_descripcion = $_POST['curso_descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $numero_plazas = intval($_POST['numero_plazas']);
    $id_categoria = intval($_POST['id_categoria']);

    if (!empty($curso_nombre) && !empty($curso_descripcion) && !empty($fecha_inicio) && !empty($fecha_fin)) {
        $query = "INSERT INTO cursos (nombre, descripcion, fecha_inicio, fecha_fin, numero_plazas, id_categoria) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssssii", $curso_nombre, $curso_descripcion, $fecha_inicio, $fecha_fin, $numero_plazas, $id_categoria);
        if ($stmt->execute()) {
            $success_message = "Curso creado exitosamente.";
        } else {
            $error_message = "Error al crear el curso: " . $mysqli->error;
        }
        $stmt->close();
    } else {
        $error_message = "Por favor, complete todos los campos.";
    }
}

if (isset($_GET['delete_course'])) {
    $curso_id = intval($_GET['delete_course']);
    $query = "DELETE FROM cursos WHERE id_Curso = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $curso_id);
    if ($stmt->execute()) {
        $success_message = "Curso eliminado exitosamente.";
    } else {
        $error_message = "Error al eliminar el curso: " . $mysqli->error;
    }
    $stmt->close();
}

// Consultar cursos y categorías
$cursos_result = $mysqli->query("SELECT * FROM cursos");
$categorias_result = $mysqli->query("SELECT * FROM categoria");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Cursos</title>
    <link rel="stylesheet" href="css/cursos.css">
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
        <h1>Administrar Cursos</h1>
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Formulario para crear curso -->
        <form method="POST">
            <h2>Crear Nuevo Curso</h2>
            <input type="text" name="curso_nombre" placeholder="Nombre del curso" required>
            <textarea name="curso_descripcion" placeholder="Descripción" required></textarea>
            <input type="date" name="fecha_inicio" required>
            <input type="date" name="fecha_fin" required>
            <input type="number" name="numero_plazas" placeholder="Plazas" required>
            <select name="id_categoria" required>
                <option value="">Seleccione Categoría</option>
                <?php while ($categoria = $categorias_result->fetch_assoc()): ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="create_course">Crear Curso</button>
        </form>

        <!-- Listado de cursos -->
        <h2>Cursos</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
            <?php while ($curso = $cursos_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($curso['descripcion']); ?></td>
                    <td>
                        <a href="?delete_course=<?php echo $curso['id_Curso']; ?>" onclick="return confirm('¿Estás seguro?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
</body>
</html>
<?php $mysqli->close(); ?>
