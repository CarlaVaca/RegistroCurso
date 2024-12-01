<?php
// Incluir el archivo de conexión
include('db_connection.php');

// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión y tiene rol 1
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 1) {
    // Si no está autenticado o no tiene rol 1, redirigir a una página de acceso denegado
    header("Location: acceso_denegado.php");
    exit();
}

// Obtener los tutores de la base de datos
$query_tutores = "SELECT id_empleado, nombre FROM empleados WHERE roles_id_Rol = 2"; // Suponiendo que los tutores tienen rol = 2
$result_tutores = $mysqli->query($query_tutores);

// Obtener los cursos de la base de datos
$query_cursos = "SELECT id_Curso, nombre FROM cursos";
$result_cursos = $mysqli->query($query_cursos);

// Procesar el formulario de asignación de curso a tutor
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_tutor']) && isset($_POST['id_curso'])) {
        $id_tutor = $_POST['id_tutor'];
        $id_curso = $_POST['id_curso'];

        // Asignar el curso al tutor
        $query_asignar = "INSERT INTO cursos_tiene_instructor(id_Curso, id_Empleado) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query_asignar);
        $stmt->bind_param("ii", $id_curso, $id_tutor);
        $stmt->execute();

        // Mensaje de éxito
        echo "<p>Curso asignado correctamente al tutor.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Curso a Tutor</title>
    <link rel="stylesheet" href="css/styles.css">
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
    <h1>Asignar Curso a Tutor</h1>

    <form method="POST" action="cursotutor.php">
        <label for="id_tutor">Seleccionar Tutor:</label>
        <select name="id_tutor" id="id_tutor" required>
            <option value="">-- Seleccionar Tutor --</option>
            <?php
            if ($result_tutores->num_rows > 0) {
                while ($row = $result_tutores->fetch_assoc()) {
                    echo "<option value='" . $row['id_empleado'] . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                }
            } else {
                echo "<option value=''>No hay tutores disponibles</option>";
            }
            ?>
        </select>

        <br><br>

        <label for="id_curso">Seleccionar Curso:</label>
        <select name="id_curso" id="id_curso" required>
            <option value="">-- Seleccionar Curso --</option>
            <?php
            if ($result_cursos->num_rows > 0) {
                while ($row = $result_cursos->fetch_assoc()) {
                    echo "<option value='" . $row['id_Curso'] . "'>" . htmlspecialchars($row['nombre']) . "</option>";
                }
            } else {
                echo "<option value=''>No hay cursos disponibles</option>";
            }
            ?>
        </select>

        <br><br>

        <input type="submit" value="Asignar Curso">
    </form>
</main>

</body>
</html>
