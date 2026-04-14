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

$sql = "SELECT player_name, score, intensity, time_seconds FROM highscore ORDER BY score DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "player_name" => $row['player_name'],
        "score" => (int)$row['score'],
        "intensity" => (float)$row['intensity'],
        "time" => (int)$row['time_seconds']
    ]);
} else {
    echo json_encode([
        "success" => true,
        "player_name" => "---",
        "score" => 0,
        "intensity" => 1.0,
        "time" => 0
    ]);
}

$conn->close();
?>
