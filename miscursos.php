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

// Obtener el id_Persona desde la sesión
$id_Persona = $_SESSION['user_id']; // Usamos 'user_id' para obtener el id de la persona

// Consultar los cursos en los que el usuario está inscrito
$query = "SELECT c.nombre AS curso_nombre, c.descripcion AS curso_descripcion
          FROM cursos c
          JOIN cursos_tiene_instructor cti ON c.id_Curso = cti.id_Curso
          JOIN inscripciones_tiene_persona itp ON cti.id_CursoInstructor = itp.id_CursoInstructor
          WHERE itp.id_Persona = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_Persona);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos Inscritos</title>
    <link rel="stylesheet" href="css/miscursos.css">
</head>
<body>

<header>
    <h1>Mis Cursos Inscritos</h1>
    <nav class="navbar">
        <div class="logo">Desarrollo de aplicaciones web</div>
        <ul class="nav-links">
            <li><a href="galeria.php">Inicio</a></li>
            <li><a href="miscursos.php">Mis Cursos</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
            <li><a href="nosotros.php">Tutores</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="container">
        <h2>Cursos en los que estás inscrito</h2>

        <?php if ($result->num_rows > 0): ?>
            <table class="courses-table">
                <tr>
                    <th>Curso</th>
                    <th>Descripción</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['curso_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['curso_descripcion']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No estás inscrito en ningún curso.</p>
        <?php endif; ?>

    </div>
</main>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$stmt->close();
$mysqli->close();
?>
