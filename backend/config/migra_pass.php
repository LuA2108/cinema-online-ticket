
<?php
// Archivo para actualizar las contraseñas de texto plano a hash, solo se ejecuta 1 vez.

require 'database.php';

$db = new Database();
$conn = $db->obtenerConexion();

// Consulta a id y contraseñas de usuarios
$resultado = $conn->query("SELECT id, contrasena FROM usuario");

// Recorre los resultados
while ($fila = $resultado->fetch_assoc()) {
    $id = $fila['id'];
    $contrasena = $fila['contrasena'];

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Actualizar contraseña segón su id
    $sql = $conn->prepare("UPDATE usuario SET contrasena = ? WHERE id = ?");
    $sql->bind_param("si", $hash, $id);
    if ($sql->execute()) {
        echo "OK ";
        echo "Usuario $id actualizado<br>";

    } else {
        echo "Error";
        echo "Usuario $id no actualizado<br>";
    }
}
