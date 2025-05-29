<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Logger;
use App\Core\Emailer;
use App\Models\User;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

// Initialize logger and emailer
$logger = Logger::getInstance();
$emailer = Emailer::getInstance();

// Initialize user model
$userModel = new User();

// Check if it's an API request
if (strpos($path, 'api/') === 0) {
    // API request handling
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');

    $path = substr($path, 4); // Remove 'api/' prefix

    try {
        // Route handling
        switch ("$method $path") {
            case 'GET users':
                $users = $userModel->getAll();
                echo json_encode(['status' => 'success', 'data' => $users]);
                break;

            case (preg_match('/^GET users\/(\d+)$/', "$method $path", $matches) ? true : false):
                $user = $userModel->read((int)$matches[1]);
                if ($user) {
                    echo json_encode(['status' => 'success', 'data' => $user]);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'User not found']);
                }
                break;

            case 'POST users':
                $data = json_decode(file_get_contents('php://input'), true);
                if (!$data) {
                    throw new Exception('Invalid input data');
                }
                $user = $userModel->create($data);
                $logger->info('New user created', ['id' => $user['id']]);
                $emailer->sendEmail(
                    $user['email'],
                    'Welcome!',
                    "Welcome to our platform, {$user['name']}!"
                );
                echo json_encode(['status' => 'success', 'data' => $user]);
                break;

            case (preg_match('/^PUT users\/(\d+)$/', "$method $path", $matches) ? true : false):
                $data = json_decode(file_get_contents('php://input'), true);
                if (!$data) {
                    throw new Exception('Invalid input data');
                }
                if ($userModel->update((int)$matches[1], $data)) {
                    echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'User not found']);
                }
                break;

            case (preg_match('/^DELETE users\/(\d+)$/', "$method $path", $matches) ? true : false):
                if ($userModel->delete((int)$matches[1])) {
                    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'User not found']);
                }
                break;

            default:
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
                break;
        }
    } catch (Exception $e) {
        $logger->error('API Error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Internal server error']);
    }
} else {
    // UI request handling
    if ($path === '' || $path === 'index.php') {
        // Load the users view
        require_once __DIR__ . '/../src/Views/users.php';
        require_once __DIR__ . '/../src/Views/layout.php';
    } else {
        // Handle static assets
        $file = __DIR__ . '/' . $path;
        if (file_exists($file) && is_file($file)) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif'
            ];
            
            if (isset($mimeTypes[$extension])) {
                header('Content-Type: ' . $mimeTypes[$extension]);
                readfile($file);
            } else {
                http_response_code(404);
                echo 'File not found';
            }
        } else {
            http_response_code(404);
            echo 'Page not found';
        }
    }
} 