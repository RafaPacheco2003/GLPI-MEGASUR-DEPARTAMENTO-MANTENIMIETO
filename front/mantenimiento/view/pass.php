<?php
session_start();
// Hash extraído de la base de datos GLPI. Este hash está en formato bcrypt
// Comienza con $2y$ que indica que se usó el algoritmo bcrypt con un "cost" de 10
// Estructura: $2y$10$saltAndHash
 $hashGuardado = '$2y$10$..NaeQCUtbD2X2s.rmExYOMGkQvvv5wu3Rs3HFwFhGPdIB2dGonoO';
 $id_usuario = isset($_SESSION['glpiID']) ? $_SESSION['glpiID'] : 'No logueado';

// Contraseña en texto plano que quieres verificar contra el hash anterior
$clavePrueba = 'glpi'; // Prueba con diferentes contraseñas

// password_verify es una función de PHP que compara la contraseña en texto plano
// con el hash guardado. Internamente usa bcrypt si el hash comienza con $2y$, $2a$, $2b$.
if (password_verify($clavePrueba, $hashGuardado)) {
    // Si coincide, la contraseña es correcta
    echo "✅ Contraseña correcta y id: $id_usuario";
} else {
    // Si no coincide, la contraseña es incorrecta
    echo "❌ Contraseña incorrecta";
}
?>
