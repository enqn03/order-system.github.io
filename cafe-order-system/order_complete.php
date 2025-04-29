<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>주문 완료 - 카페 메뉴 주문 시스템</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body data-theme="<?php echo isset($_SESSION['theme']) ? $_SESSION['theme'] : 'modern'; ?>">
    <div class="container">
        <header>
            <h1>주문 완료</h1>
        </header>

        <div class="order-complete-container">
            <div class="success-message">
                <h2>주문이 완료되었습니다!</h2>
                <p>감사합니다. 주문이 성공적으로 처리되었습니다.</p>
                <div class="order-number">
                    주문번호: <?php echo date('YmdHis') . rand(1000, 9999); ?>
                </div>
            </div>

            <div class="order-details">
                <h3>주문 정보</h3>
                <div class="info-group">
                    <label>주문 일시:</label>
                    <span><?php echo date('Y년 m월 d일 H시 i분'); ?></span>
                </div>
                <div class="info-group">
                    <label>결제 금액:</label>
                    <span>₩<?php echo number_format($_SESSION['last_order_total'] ?? 0); ?></span>
                </div>
            </div>

            <div class="action-buttons">
                <a href="index.php" class="btn-home">홈으로 돌아가기</a>
            </div>
        </div>
    </div>
</body>
</html> 