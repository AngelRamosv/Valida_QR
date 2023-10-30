<?php
session_start();
session_unset();
session_destroy();

// Redirige al usuario a la página de inicio de sesión (index.html)
header('Location: index.html');
exit;
?>
