<?php
// Conexión a la base de datos
include('db_connection.php');

// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Verificar si 'user_id' está presente en la sesión
if (!isset($_SESSION['user_id'])) {
    echo "<p>Error: no se ha encontrado la información del usuario. Por favor, inicie sesión nuevamente.</p>";
    exit;
}

// Obtener el id_Persona desde la sesión
$id_Persona = $_SESSION['user_id'];  // Usamos 'user_id' en lugar de 'id_persona'

// Verificar si se ha recibido el parámetro id_Curso
if (isset($_GET['id_Curso'])) {
    $id_Curso = $_GET['id_Curso'];

    // Consultar el nombre del curso
    $curso_query = "SELECT nombre FROM cursos WHERE id_Curso = ?";
    $curso_stmt = $mysqli->prepare($curso_query);
    $curso_stmt->bind_param("i", $id_Curso);
    $curso_stmt->execute();
    $curso_result = $curso_stmt->get_result();

    // Si el curso existe, continuar con la consulta de instructores
    if ($curso_result->num_rows > 0) {
        $curso_row = $curso_result->fetch_assoc();
        $curso_nombre = $curso_row['nombre'];

        // Consultar los instructores asignados al curso
        $instructor_query = "SELECT e.nombre, e.email, cti.id_CursoInstructor
                             FROM empleados e
                             JOIN cursos_tiene_instructor cti ON e.id_empleado = cti.id_empleado
                             WHERE cti.id_Curso = ?";
        $instructor_stmt = $mysqli->prepare($instructor_query);
        $instructor_stmt->bind_param("i", $id_Curso);
        $instructor_stmt->execute();
        $instructor_result = $instructor_stmt->get_result();
    } else {
        // Si no se encontró el curso, redirigir al listado de cursos
        header("Location: galeria.php");
        exit;
    }
} else {
    // Si no se recibió el id_Curso, redirigir al listado de cursos
    header("Location: galeria.php");
    exit;
}

// Verificar si se ha enviado el formulario de inscripción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscribir'])) {
    $id_CursoInstructor = $_POST['id_CursoInstructor'];
    $fecha_inscripcion = date('Y-m-d H:i:s'); // Fecha y hora actual

    // Insertar inscripción en la tabla inscripciones_tiene_persona
    $inscripcion_query = "INSERT INTO inscripciones_tiene_persona (fecha_inscripcion, id_CursoInstructor, id_Persona)
                          VALUES (?, ?, ?)";
    $inscripcion_stmt = $mysqli->prepare($inscripcion_query);
    $inscripcion_stmt->bind_param("sii", $fecha_inscripcion, $id_CursoInstructor, $id_Persona);
    $inscripcion_stmt->execute();

    if ($inscripcion_stmt->affected_rows > 0) {
        echo "<p>Inscripción realizada con éxito.</p>";
    } else {
        echo "<p>Error en la inscripción. Por favor, inténtelo de nuevo.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructores del Curso</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>Instructores del Curso</h1>
    <nav class="navbar">
        <div class="logo">Desarrollo de aplicaciones web</div>
        <ul class="nav-links">
            <li><a href="logout.php">Inicio</a></li>
            <li><a href="miscursos.php">Mis Cursos</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
            <li><a href="nosotros.php">Tutores</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="container">
        <h2>Instructores para el Curso: <?php echo htmlspecialchars($curso_nombre); ?></h2>

        <?php if ($instructor_result->num_rows > 0): ?>
            <table class="gallery-table">
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Inscripción</th>
                </tr>
                <?php while ($row = $instructor_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <!-- Formulario para realizar la inscripción -->
                            <form method="POST" action="">
                                <input type="hidden" name="id_CursoInstructor" value="<?php echo $row['id_CursoInstructor']; ?>">
                                <button type="submit" name="inscribir">Inscribirse</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay instructores asignados a este curso.</p>
        <?php endif; ?>

    </div>
</main>

</body>
</html>
