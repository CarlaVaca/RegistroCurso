<?php
session_start();
require 'db_connection.php'; // Incluir la conexión a la base de datos

// Verificar autenticación y permisos de administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit;
}

// Consultar estudiantes, cursos en los que están inscritos y tutores
$query = "
    SELECT
    rp.id_persona,
    rp.nombre AS estudiante_nombre,
    rp.email AS estudiante_email,
    c.id_curso,
    c.nombre AS curso_nombre,
    e.id_empleado,
    e.nombre AS tutor_nombre
FROM
    registro_personas AS rp
JOIN
    inscripciones_tiene_persona AS itp ON rp.id_persona = itp.id_persona
JOIN
    cursos_tiene_instructor AS cti ON itp.id_CursoInstructor = cti.id_CursoInstructor  -- Relación directa con cursos_tiene_instructor
JOIN
    cursos AS c ON cti.id_curso = c.id_curso  -- Relación con la tabla cursos
JOIN
    empleados AS e ON cti.id_empleado = e.id_empleado  -- Relación con la tabla empleados
WHERE
    e.roles_id_Rol = 2;  -- Filtrar solo tutores
";

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Estudiantes</title>
    <link rel="stylesheet" href="css/estudiantes.css">
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
        <h1>Listado de Estudiantes</h1>

        <!-- Tabla de estudiantes, cursos y tutores -->
        <table>
            <thead>
                <tr>
                    <th>Nombre Estudiante</th>
                    <th>Email</th>
                    <th>Cursos Inscritos</th>
                    <th>Tutor Asignado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['estudiante_nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['estudiante_email']) . "</td>";
                        // Corregir acceso al campo curso_nombre en lugar de 'curso'
                        echo "<td>" . ($row['curso_nombre'] ? htmlspecialchars($row['curso_nombre']) : 'No inscrito') . "</td>";
                        echo "<td>" . ($row['tutor_nombre'] ? htmlspecialchars($row['tutor_nombre']) : 'No asignado') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay estudiantes registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
