<?php
// Datos de conexión
$servername = "localhost";  // Cambia por tu servidor, por ejemplo: localhost, 127.0.0.1, o la IP de tu servidor.
$username = "root";         // Usuario de la base de datos (por defecto suele ser 'root')
$password = "";             // Contraseña de la base de datos (por defecto suele estar vacía, pero debes configurarlo si es necesario)
$dbname = "curso_registro"; // Nombre de la base de datos que deseas conectar

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa a la base de datos '$dbname'.";
}

// Cerrar la conexión
$conn->close();
?>


