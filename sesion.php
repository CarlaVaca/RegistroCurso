<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Obtener la información del usuario desde la sesión
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="styles.css"> <!-- Incluye el nuevo CSS -->
</head>
<body class="session">
    <div class="container session">
        <header class="session">
            <h1 class="session">Bienvenido, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p class="session">Correo: <?php echo htmlspecialchars($user_email); ?></p>
        </header>

        <div class="message session">
            <p class="session">Inicio de sesión exitosa. Serás redirigido a la página Galería...</p>
            <p class="session">Redirigiendo en <span id="countdown" class="session">3</span> segundos...</p>
        </div>
    </div>

    <script>
        // Función para el contador
        let countdown = 5; // Usamos 5 segundos como el tiempo de redirección
        const countdownElement = document.getElementById('countdown');

        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown === 0) {
                clearInterval(interval);
                window.location.href = 'galeria.php'; // Redirigir al usuario
            }
        }, 1000);
    </script>
</body>
</html>
