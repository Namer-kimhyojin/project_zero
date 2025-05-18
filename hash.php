<?php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/autoload.php';

use App\Lib\Database;

$id = '';
$password = '';
$resultMessage = '';
$foundHash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($id === '' || $password === '') {
        $resultMessage = '❗ 아이디와 비밀번호를 모두 입력해주세요.';
    } else {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $foundHash = $user['password'];
                if (password_verify($password, $user['password'])) {
                    $resultMessage = '✅ 로그인 성공! 비밀번호 일치.';
                } else {
                    $resultMessage = '❌ 비밀번호가 일치하지 않습니다.';
                }
            } else {
                $resultMessage = '🚫 해당 아이디의 사용자가 존재하지 않습니다.';
            }
        } catch (PDOException $e) {
            $resultMessage = 'DB 오류: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>계정 확인 및 비밀번호 검증</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">🔐 사용자 비밀번호 검증 도구</h2>

    <form method="POST" class="mb-4">
      <div class="mb-3">
        <label for="username" class="form-label">아이디</label>
        <input type="text" id="username" name="username" class="form-control" required value="<?= htmlspecialchars($id) ?>">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">비밀번호</label>
        <input type="password" id="password" name="password" class="form-control" required value="<?= htmlspecialchars($password) ?>">
      </div>
      <button type="submit" class="btn btn-primary">검증하기</button>
    </form>

    <?php if ($resultMessage): ?>
      <div class="alert <?= str_contains($resultMessage, '성공') ? 'alert-success' : 'alert-warning' ?>">
        <?= htmlspecialchars($resultMessage) ?>
      </div>
      <?php if ($foundHash): ?>
        <div class="card">
          <div class="card-body">
            <strong>DB 저장 해시:</strong>
            <pre><?= htmlspecialchars($foundHash) ?></pre>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>
