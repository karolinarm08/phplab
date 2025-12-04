<?php
date_default_timezone_set('Europe/Kiev'); 

header('Content-Type: application/json');
require_once 'db.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['event_id'])) {
        try {
            $serverTime = date('Y-m-d H:i:s');
            
            $stmt = $pdo->prepare("INSERT INTO lab5_events (event_id, client_time, message, server_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $input['event_id'], 
                $input['client_time'], 
                $input['message'], 
                $serverTime
            ]);
            
            echo json_encode(['success' => true, 'server_time' => $serverTime]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
    $stmt = $pdo->query("SELECT * FROM lab5_events ORDER BY id ASC LIMIT 50");
    echo json_encode(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'clear') {
    $pdo->exec("TRUNCATE TABLE lab5_events");
    echo json_encode(['success' => true]);
}
?>