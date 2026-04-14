<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fit_frenzy"; // nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$score = (int)$data['score'];
$intensity = (float)$data['intensity'];
$time = (int)$data['time'];

// Obtener el récord actual más alto
$sql_check = "SELECT score FROM highscore ORDER BY score DESC LIMIT 1";
$result = $conn->query($sql_check);
$currentBest = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentBest = (int)$row['score'];
}

$isNewRecord = ($score > $currentBest);

if ($isNewRecord) {
    // Opcional: borrar el anterior y guardar solo el mejor, o mantener histórico.
    // Aquí se guarda siempre, pero para que solo quede el mejor, puedes truncar la tabla antes de insertar.
    // Para simplificar, insertamos y luego mostraremos el mejor con ORDER BY.
    $stmt = $conn->prepare("INSERT INTO highscore (score, intensity, time_seconds) VALUES (?, ?, ?)");
    $stmt->bind_param("idi", $score, $intensity, $time);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(["success" => true, "isNewRecord" => $isNewRecord]);

$conn->close();
?>
