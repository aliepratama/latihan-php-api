<?php

require "../vendor/autoload.php";


$env = parse_ini_file('../.env');
$db_key = $env["DB_KEY"];
$db_url = $env["DB_URL"];

$service = new PHPSupabase\Service(
    $db_key, $db_url
);

$db = $service->initializeDatabase('coba_php', 'id');

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        try{
            $data = $db->fetchAll()->getResult();
            http_response_code(200);
            echo json_encode([
                'message' => 'Todo berhasil dimuat!',
                'data' => $data
            ]);
        }
        catch(Exception $e){
            http_response_code(500);
            echo json_encode([
                'message' => 'Todo gagal dimuat!',
                'reason' => $e->getMessage()
            ]);
        }
    break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $data['title'];
        $description = $data['description'];
        $priority = $data['priority'];

        $newTodo = [
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
        ];
        
        try{
            $data = $db->insert($newTodo);
            http_response_code(200);
            echo json_encode([
                'message' => 'Todo berhasil ditambahkan!',
                'data' => $data
            ]);
        }
        catch(Exception $e){
            http_response_code(500);
            echo json_encode([
                'message' => 'Todo gagal ditambahkan!',
                'reason' => $e->getMessage()
            ]);
        }
    break;
    default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    break;
}
