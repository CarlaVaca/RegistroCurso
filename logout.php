<?php
// Iniciar sesión
session_start();

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir a index.html después de 3 segundos
header("Refresh: 3; url=index.html");  // La redirección se coloca primero

// Mensaje de confirmación (esto se puede mostrar después de header())
echo "<p>Sesión cerrada exitosamente. Serás redirigido a la página principal...</p>";
exit;
?>

