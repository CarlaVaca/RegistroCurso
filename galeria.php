<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    // Si no está iniciado, redirigir a login.php
    header("Location: login.php");
    exit;
}

// Incluir archivo de conexión a la base de datos
include('db_connection.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Cursos</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilo para centrar la tabla */
        .container {
            text-align: center;
            margin-top: 20px;
        }

        .gallery-table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 80%;
        }

        .gallery-table th, .gallery-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .gallery-table th {
            background-color: #f2f2f2;
        }

        .gallery-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        h2 {
            color: #333;
        }

        .navbar {
            background-color: #333;
            padding: 10px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-links li {
            display: inline;
            margin-right: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
        }

        .form-select {
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body>

<header>
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
       <!--Imagen de promoción-->
        <div class="image-container">
            <img src="img/curso.png" alt="Curso 1" class="image">
        </div>
    <div class="container">
        <h1>Galería de Cursos</h1>
        <!-- Formulario para seleccionar categoría -->
        <form method="GET" action="galeria.php">
            <label for="categoria" class="form-label">Selecciona una categoría:</label>
            <select name="categoria_id" id="categoria" class="form-select" onchange="this.form.submit()">
                <option value="">-- Seleccionar categoría --</option>
                <?php
                // Consulta a la base de datos para obtener las categorías
                $query = "SELECT id_categoria, nombre FROM categoria";
                $result = $mysqli->query($query);

                // Generar las opciones para el desplegable
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($_GET['categoria_id']) && $_GET['categoria_id'] == $row['id_categoria']) ? 'selected' : '';
                    echo "<option value='" . $row['id_categoria'] . "' $selected>" . htmlspecialchars($row['nombre']) . "</option>";
                }
                ?>
            </select>
        </form>

        <?php
        // Filtrar los cursos por categoría seleccionada
        if (isset($_GET['categoria_id']) && !empty($_GET['categoria_id'])) {
            $categoria_id = $_GET['categoria_id'];

            // Consulta para obtener los cursos filtrados por categoría
            $query = "SELECT c.id_Curso, c.nombre, c.descripcion, c.fecha_inicio, c.fecha_fin, c.numero_plazas, ca.nombre as categoria
                      FROM cursos c
                      JOIN categoria ca ON c.id_categoria = ca.id_categoria
                      WHERE ca.id_categoria = ?";

            // Preparar y ejecutar la consulta
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("i", $categoria_id); // Vincular el parámetro
                $stmt->execute();
                $result = $stmt->get_result(); // Obtener el resultado de la consulta

                // Mostrar los cursos en una tabla
                if ($result->num_rows > 0) {
                    echo "<table class='gallery-table'>";
                    echo "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Plazas</th><th>Acciones</th></tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_Curso']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_inicio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_fin']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['numero_plazas']) . "</td>";
                        echo "<td><a href='inscripcion.php?id_Curso=" . $row['id_Curso'] . "'>Inscribirse</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No hay cursos disponibles para esta categoría.</p>";
                }

                // Cerrar la declaración
                $stmt->close();
            }
        } else {
            echo "<p>No se ha seleccionado ninguna categoría.</p>";
        }
        ?>
    </div>
</main>

</body>
</html>

