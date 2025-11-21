<?php
header('Content-Type: application/json');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['success' => false, 'message' => 'Дозволено тільки POST-запити']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['page_id']) || !isset($input['element_key']) || !isset($input['content_data'])) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Недостатньо даних']);
    exit;
}

$page_id = $input['page_id'];
$element_key = $input['element_key'];
$content_data = $input['content_data'];

$sql = "INSERT INTO content (page_id, element_key, content_data) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE content_data = VALUES(content_data)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$page_id, $element_key, $content_data]);
    echo json_encode(['success' => true, 'message' => 'Дані успішно збережено']);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Помилка збереження даних: ' . $e->getMessage()]);
}
?>