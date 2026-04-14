<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fit_frenzy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Conexión fallida: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$player_name = $conn->real_escape_string($data['player_name']);
$score = (int)$data['score'];
$intensity = (float)$data['intensity'];
$time = (int)$data['time'];

// Obtener el mejor puntaje actual
$sql_check = "SELECT score FROM highscore ORDER BY score DESC LIMIT 1";
$result = $conn->query($sql_check);
$currentBest = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentBest = (int)$row['score'];
}

$isNewRecord = ($score > $currentBest);

if ($isNewRecord) {
    // Eliminar el anterior (opcional, si solo queremos uno) o simplemente insertar y luego mostrar el max.
    // Para mantener solo el mejor, podemos eliminar todos y luego insertar.
    $conn->query("DELETE FROM highscore");
    $stmt = $conn->prepare("INSERT INTO highscore (player_name, score, intensity, time_seconds) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sidi", $player_name, $score, $intensity, $time);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(["success" => true, "isNewRecord" => $isNewRecord]);

$conn->close();
?>
