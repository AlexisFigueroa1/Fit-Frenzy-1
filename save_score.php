<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "fit_frenzy");
if ($conn->connect_error) die(json_encode(["success"=>false,"error"=>$conn->connect_error]));
$data = json_decode(file_get_contents('php://input'), true);
$score = (int)$data['score'];
$intensity = (float)$data['intensity'];
$time = (int)$data['time'];

$result = $conn->query("SELECT score FROM highscore ORDER BY score DESC LIMIT 1");
$currentBest = ($result && $result->num_rows) ? (int)$result->fetch_assoc()['score'] : 0;
$isNew = $score > $currentBest;
if ($isNew) {
    $stmt = $conn->prepare("INSERT INTO highscore (score, intensity, time_seconds) VALUES (?,?,?)");
    $stmt->bind_param("idi", $score, $intensity, $time);
    $stmt->execute();
    $stmt->close();
}
echo json_encode(["success"=>true, "isNewRecord"=>$isNew]);
$conn->close();
?>
