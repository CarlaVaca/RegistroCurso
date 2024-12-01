<?php
session_start();
require 'db_connection.php'; // Incluir el archivo de conexión a la base de datos

$error = ""; // Variable para almacenar errores

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta en la tabla registro_personas
    $stmt = $mysqli->prepare("SELECT id_Persona, contrasena, roles_id_Rol, nombre FROM registro_personas WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Comparar contraseñas en texto plano (sin hash)
        if ($password == $row['contrasena']) {
            // Credenciales válidas
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $row['id_Persona'];
            $_SESSION['role'] = $row['roles_id_Rol'];
            $_SESSION['user_name'] = $row['nombre']; // Guardamos el nombre de usuario

            if ($row['roles_id_Rol'] == 4) {
                header("Location: galeria.php");
                exit; // Detener ejecución tras redirigir
            } else {
                $error = "Rol no válido en registro_personas.";
            }
        } else {
            $error = "Contraseña incorrecta en registro_personas.";
        }
    } else {
        // Si no encuentra en registro_personas, buscar en empleados
        $stmt = $mysqli->prepare("SELECT id_Empleado, contrasena, roles_id_Rol, nombre FROM empleados WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Comparar contraseñas en texto plano (sin hash)
            if ($password == $row['contrasena']) {
                // Credenciales válidas
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['id_Empleado'];
                $_SESSION['role'] = $row['roles_id_Rol'];
                $_SESSION['user_name'] = $row['nombre']; // Guardamos el nombre de usuario

                if ($row['roles_id_Rol'] == 1) {
                    header("Location: administracion.php");
                    exit; // Detener ejecución tras redirigir
                } else {
                    $error = "Rol no válido en empleados.";
                }
            } else {
                $error = "Contraseña incorrecta en empleados.";
            }
        } else {
            $error = "Email no encontrado.";
        }
    }
    $stmt->close();
    $mysqli->close();
}
?>

