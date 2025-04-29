<?php
session_start();

// 장바구니가 비어있는지 확인
if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

// 총 금액 계산
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// 결제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 실제 결제 시스템 연동은 여기에 구현
    // 예시로 단순히 장바구니를 비우고 주문 완료 페이지로 이동
    $_SESSION['cart'] = [];
    header('Location: order_complete.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결제하기 - 카페 메뉴 주문 시스템</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body data-theme="<?php echo isset($_SESSION['theme']) ? $_SESSION['theme'] : 'modern'; ?>">
    <div class="container">
        <header>
            <h1>결제하기</h1>
        </header>

        <div class="checkout-container">
            <h2>주문 내역</h2>
            <div class="order-items">
                <?php
                foreach ($_SESSION['cart'] as $item) {
                    echo '<div class="order-item">';
                    echo '<img src="' . $item['img_src'] . '" alt="' . $item['name'] . '">';
                    echo '<div class="order-item-info">';
                    echo '<div class="order-item-name">' . $item['name'] . '</div>';
                    echo '<div class="order-item-quantity">수량: ' . $item['quantity'] . '</div>';
                    echo '<div class="order-item-price">₩' . number_format($item['price'] * $item['quantity']) . '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="order-total">
                <h3>총 결제 금액: ₩<?php echo number_format($total_price); ?></h3>
            </div>

            <form method="post" class="payment-form">
                <div class="form-group">
                    <label for="name">이름</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">이메일</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">전화번호</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">주소</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-actions">
                    <a href="index.php" class="btn-back">뒤로가기</a>
                    <button type="submit" class="btn-pay">결제하기</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 