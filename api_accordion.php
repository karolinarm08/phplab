<?php
header('Content-Type: application/json');
require_once 'db.php'; 

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$PAGE_ID = 'accordion_storage';
$ELEMENT_KEY = 'main_list';

if ($method === 'POST' && $action === 'save') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['data'])) {
        $jsonContent = json_encode($input['data'], JSON_UNESCAPED_UNICODE);
        
        $sql = "INSERT INTO content (page_id, element_key, content_data) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE content_data = VALUES(content_data)";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$PAGE_ID, $ELEMENT_KEY, $jsonContent]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No data provided']);
    }
} 
elseif ($method === 'GET' && $action === 'load') {
    $stmt = $pdo->prepare("SELECT content_data FROM content WHERE page_id = ? AND element_key = ?");
    $stmt->execute([$PAGE_ID, $ELEMENT_KEY]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $data = $row ? json_decode($row['content_data']) : [];
    
    echo json_encode([
        'data' => $data,
        'hash' => md5($row['content_data'] ?? '') 
    ]);
}
?>