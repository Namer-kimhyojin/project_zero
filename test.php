<?php
require_once __DIR__ . '\autoload.php';
require_once __DIR__ . '\config\config.php';

use App\Lib\Database;

// CSRF 토큰 생성 함수
function generate_csrf_token() {
    if (!session_id()) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->query("SELECT 1");
    $result = $stmt->fetchColumn();

    echo "<h3>✅ DB 접속 성공</h3>";
    echo "DB 응답값: {$result}<br>";
} catch (Exception $e) {
    echo "<h3>❌ DB 접속 실패</h3>";
    echo "에러 메시지: " . $e->getMessage();
}

echo "<hr>";

$csrf = generate_csrf_token();
echo "<h3>CSRF 토큰 확인</h3>";
echo "<code>{$csrf}</code>";
?>
