<?php
$server = "localhost";
$login = "root";
$pass = "";
$name_db = "city_users";

$link = mysqli_connect($server, $login, $pass, $name_db);
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $resource = $_GET['resource'] ?? '';

    switch ($resource) {
        case 'city':
            $stmt = mysqli_query($link, "SELECT c.id, c.name FROM city c");
            $names = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
            if ($stmt === false) {
                die('Ошибка выполнения запроса: ' . mysqli_error($link));
            }
            $response = ['names' => $names];
            break;
        default:
            $response = ['error' => 'Invalid resource'];
            break;
    }

    switch ($resource) {
        case 'user':
            $stmt = mysqli_query($link, "SELECT u.id, u.name, u.username, u.city_id FROM user u");
            $names = mysqli_fetch_all($stmt, MYSQLI_ASSOC);
            if ($stmt === false) {
                die('Ошибка выполнения запроса: ' . mysqli_error($link));
            }
            $response = ['names' => $names];
            break;
        default:
            $response = ['error' => 'Invalid resource'];
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource = $_GET['resource'] ?? '';

    switch ($resource) {
        case 'user':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = mysqli_prepare($link, "INSERT INTO city (name) VALUES (?)");
            mysqli_stmt_bind_param($stmt, 's', $data['name']);
            mysqli_stmt_execute($stmt);
            $response = ['id' => mysqli_insert_id($link)];
            break;

        case 'city':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $_GET['id'] ?? '';
            $stmt = mysqli_prepare($link, "UPDATE city SET name = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'si', $data['name'], $id);
            mysqli_stmt_execute($stmt);
            break;

        default:
            $response = ['error' => 'Invalid resource'];
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $resource = $_GET['resource'] ?? '';
    $id = $_GET['id'] ?? '';

    switch ($resource) {
        case 'name':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = mysqli_prepare($link, "UPDATE city SET name = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'si', $data['name'], $id);
            mysqli_stmt_execute($stmt);
            break;

        case 'city':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = mysqli_prepare($link, "UPDATE city SET name = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, 'si', $data['name'], $id);
            mysqli_stmt_execute($stmt);
            break;
    }
}