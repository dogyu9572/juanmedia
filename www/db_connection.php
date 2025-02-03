<?php
// 데이터베이스 연결 정보
$host = '10.14.32.4';
$dbname = 'juanmedia';
$username = 'root';
$password = '2025Gabia@)@%';

try {
    // PDO 인스턴스 생성
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // 에러를 예외로 처리
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 기본 fetch 모드 설정
        PDO::ATTR_EMULATE_PREPARES => false, // 네이티브 prepared statements 사용
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    // 연결 성공 메시지 출력
    echo "PDO로 MySQL에 성공적으로 연결되었습니다.<br>";

    // 테스트용 쿼리 실행
    $query = "SELECT NOW() AS current_time"; // 현재 시간을 반환하는 쿼리
    $stmt = $pdo->query($query);

    // 결과 출력
    $row = $stmt->fetch();
    echo "현재 서버 시간: " . $row['current_time'] . "<br>";

} catch (PDOException $e) {
    // 예외 발생 시 에러 메시지 출력
    echo "데이터베이스 연결 실패: " . $e->getMessage() . "<br>";
}
