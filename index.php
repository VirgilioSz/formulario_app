<?php
// --- Conexión a SQL Server ---
$servidor = "tcp:virgilio-server.database.windows.net,1433";
$opciones = array(
    "Database" => "formulario_app",
    "UID" => "virgilio",
    "PWD" => "Jesus-1234",
    "CharacterSet" => "UTF-8"
);

// Crear conexión
$conexion = sqlsrv_connect($servidor, $opciones);

// Verificar conexión
if ($conexion === false) {
    die(print_r(sqlsrv_errors(), true));
}

// --- Insertar datos si se envió el formulario ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['nombre'];
    $correoUsuario = $_POST['correo'];

    if (!empty($nombreUsuario) && !empty($correoUsuario)) {
        $sql = "INSERT INTO usuarios (nombre, correo) VALUES (?, ?)";
        $parametros = array($nombreUsuario, $correoUsuario);
        $resultado = sqlsrv_query($conexion, $sql, $parametros);

        if ($resultado === false) {
            echo "Error al insertar datos:<br>";
            die(print_r(sqlsrv_errors(), true));
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formulario PHP - Captura y Consulta</title>
</head>
<body>
    <h2>Formulario de Captura</h2>
    <form method="POST" action="">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>
        <label>Correo:</label><br>
        <input type="email" name="correo" required><br><br>
        <input type="submit" value="Guardar">
    </form>

    <h2>Consulta de Información</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
        </tr>
        <?php
        // --- Consultar los datos ---
        $consultaSQL = "SELECT * FROM usuarios";
        $consulta = sqlsrv_query($conexion, $consultaSQL);

        if ($consulta === false) {
            echo "<tr><td colspan='3'>Error en la consulta.</td></tr>";
            die(print_r(sqlsrv_errors(), true));
        }

        $hayRegistros = false;
        while ($fila = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)) {
            $hayRegistros = true;
            echo "<tr>
                    <td>{$fila['id']}</td>
                    <td>{$fila['nombre']}</td>
                    <td>{$fila['correo']}</td>
                  </tr>";
        }

        if (!$hayRegistros) {
            echo "<tr><td colspan='3'>No hay registros.</td></tr>";
        }

        sqlsrv_close($conexion);
        ?>
    </table>
</body>
</html>
