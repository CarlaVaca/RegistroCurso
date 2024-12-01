<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    // Si no está iniciado, redirigir a login.php
    header("Location: login.php");
    exit;
}

// Incluir la conexión a la base de datos
include 'db_connection.php';  // Ruta correcta del archivo de conexión

// Consulta SQL para obtener los empleados con roles_id_Rol = 2
$sql = "SELECT nombre, email, img FROM empleados WHERE roles_id_Rol = 2";
$result = $mysqli->query($sql);  // Usar $mysqli para la consulta

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $mysqli->error);  // Usar $mysqli para el error
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<!-- Menú de navegación -->
<header>
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

<body>
    <div class="container">
        <h1>Conoce a nuestros tutores</h1>

        <table class="empleados-table" style="margin: 0 auto;">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Imagen</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Verificar si hay resultados en la consulta
        if ($result->num_rows > 0) {
            // Mostrar los datos de cada empleado
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";

                // Verificar si la imagen tiene una ruta válida
                $img_path = htmlspecialchars($row['img']);
                if (!empty($img_path) && file_exists($img_path)) {
                    // Mostrar la imagen desde la ruta especificada
                    echo "<td><img src='$img_path' alt='Imagen de " . htmlspecialchars($row['nombre']) . "' class='empleado-img' style='width: 100px; height: 100px;'></td>";
                } else {
                    // Si no hay imagen o no existe la ruta, mostrar texto alternativo
                    echo "<td>No disponible</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='no-tutores'>No se encontraron empleados con el rol de tutor.</td></tr>";
        }
        ?>
    </tbody>
</table>


    </div>

</body>
</html>

<?php
// Cerrar la conexión
$mysqli->close();  // Cerrar la conexión correctamente
?>











