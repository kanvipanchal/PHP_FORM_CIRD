<?php
// api.php - small JSON CRUD API
//   GET    api.php           -> list all contacts
//   GET    api.php?id=1      -> get one contact
//   POST   api.php           -> create   (JSON body)
//   PUT    api.php?id=1      -> update   (JSON body)
//   DELETE api.php?id=1      -> delete
require __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

function respond($data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

function validate(array $in): array {
    $name  = trim($in['name'] ?? '');
    $email = trim($in['email'] ?? '');
    $phone = trim($in['phone'] ?? '');
    if ($name === '')                              respond(['error' => 'Name is required.'], 422);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respond(['error' => 'Enter a valid email address.'], 422);
    return [$name, $email, $phone];
}

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

try {
    switch ($method) {
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                $row ? respond($row) : respond(['error' => 'Contact not found.'], 404);
            }
            respond($pdo->query('SELECT * FROM contacts ORDER BY id DESC')->fetchAll());

        case 'POST':
            [$name, $email, $phone] = validate($input);
            $stmt = $pdo->prepare('INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $phone]);
            respond(['id' => (int)$pdo->lastInsertId(), 'message' => 'Contact added.'], 201);

        case 'PUT':
            if (!$id) respond(['error' => 'Missing id.'], 400);
            [$name, $email, $phone] = validate($input);
            $stmt = $pdo->prepare('UPDATE contacts SET name = ?, email = ?, phone = ? WHERE id = ?');
            $stmt->execute([$name, $email, $phone, $id]);
            respond(['message' => 'Contact updated.']);

        case 'DELETE':
            if (!$id) respond(['error' => 'Missing id.'], 400);
            $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
            $stmt->execute([$id]);
            respond(['message' => 'Contact deleted.']);

        default:
            respond(['error' => 'Method not allowed.'], 405);
    }
} catch (Throwable $e) {
    respond(['error' => 'Server error.'], 500);
}
