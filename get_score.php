<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "fit_frenzy");
if ($conn->connect_error) die(json_encode(["success"=>false,"error"=>$conn->connect_error]));
$result = $conn->query("SELECT score, intensity, time_seconds FROM highscore ORDER BY score DESC LIMIT 1");
if ($result && $result->num_rows) {
    $row = $result->fetch_assoc();
    echo json_encode(["success"=>true, "score"=>(int)$row['score'], "intensity"=>(float)$row['intensity'], "time"=>(int)$row['time_seconds']]);
} else {
    echo json_encode(["success"=>true, "score"=>0, "intensity"=>1.0, "time"=>0]);
}
$conn->close();
?>
