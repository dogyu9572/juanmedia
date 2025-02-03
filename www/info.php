<?php
// 데이터베이스 연결
$conn = mysqli_connect("10.14.32.4", "root", "2025Gabia@)@%", "juanmedia");

// 연결 확인
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 쿼리 실행
$sql = "SELECT * FROM tbl_admin";
$result = mysqli_query($conn, $sql);

// 쿼리 실행 확인
if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // 쿼리 실패 시 오류 출력
}

// 결과 확인 및 데이터 출력
if (mysqli_num_rows($result) > 0) {
    // 결과를 반복 처리하여 출력
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['a_id'] . "<br>";
        echo "Name: " . $row['a_name'] . "<br>";
        echo "Email: " . $row['a_email'] . "<br><br>";
    }
} else {
    echo "No rows found.";
}

// 연결 종료
mysqli_close($conn);
?>