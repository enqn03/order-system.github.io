<?php
session_start();

// 테마 설정
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'modern';

// 장바구니 초기화
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 테마 변경 처리
if (isset($_POST['change_theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 장바구니 아이템 추가
if (isset($_POST['add_to_cart'])) {
    $item = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'quantity' => 1,
        'img_src' => $_POST['img_src']
    ];
    
    // 이미 장바구니에 있는지 확인
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['name'] === $item['name']) {
            $cart_item['quantity']++;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = $item;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 장바구니 아이템 수량 변경
if (isset($_POST['update_quantity'])) {
    $index = $_POST['index'];
    $change = $_POST['change'];
    
    $_SESSION['cart'][$index]['quantity'] += $change;
    
    if ($_SESSION['cart'][$index]['quantity'] <= 0) {
        array_splice($_SESSION['cart'], $index, 1);
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 장바구니 아이템 삭제
if (isset($_POST['remove_item'])) {
    $index = $_POST['index'];
    array_splice($_SESSION['cart'], $index, 1);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// 총 금액 계산
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>카페 메뉴 주문 시스템</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body data-theme="<?php echo $theme; ?>">
    <div class="container">
        <header>
            <h1>카페 메뉴 주문 시스템</h1>
            <div class="theme-selector">
                <form method="post" class="theme-buttons">
                    <button type="submit" name="change_theme" value="modern" class="theme-btn <?php echo $theme === 'modern' ? 'active' : ''; ?>">
                        <span class="theme-icon modern"></span>
                        <span class="theme-name">모던</span>
                        <input type="hidden" name="theme" value="modern">
                    </button>
                    <button type="submit" name="change_theme" value="classic" class="theme-btn <?php echo $theme === 'classic' ? 'active' : ''; ?>">
                        <span class="theme-icon classic"></span>
                        <span class="theme-name">클래식</span>
                        <input type="hidden" name="theme" value="classic">
                    </button>
                    <button type="submit" name="change_theme" value="minimal" class="theme-btn <?php echo $theme === 'minimal' ? 'active' : ''; ?>">
                        <span class="theme-icon minimal"></span>
                        <span class="theme-name">미니멀</span>
                        <input type="hidden" name="theme" value="minimal">
                    </button>
                    <button type="submit" name="change_theme" value="dark" class="theme-btn <?php echo $theme === 'dark' ? 'active' : ''; ?>">
                        <span class="theme-icon dark"></span>
                        <span class="theme-name">다크 모드</span>
                        <input type="hidden" name="theme" value="dark">
                    </button>
                </form>
            </div>
        </header>

        <div class="menu-board">
            <div class="menu-category">
                <h2>커피</h2>
                <div class="menu-items">
                    <?php
                    $coffee_items = [
                        ['name' => '아메리카노', 'price' => 3000, 'img' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '카페라떼', 'price' => 4000, 'img' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '카푸치노', 'price' => 4000, 'img' => 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '바닐라라떼', 'price' => 4500, 'img' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '카라멜마끼아또', 'price' => 4500, 'img' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '에스프레소', 'price' => 3500, 'img' => 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80']
                    ];

                    foreach ($coffee_items as $item) {
                        echo '<div class="menu-item">';
                        echo '<img src="' . $item['img'] . '" alt="' . $item['name'] . '">';
                        echo '<h3>' . $item['name'] . '</h3>';
                        echo '<p>₩' . number_format($item['price']) . '</p>';
                        echo '<form method="post">';
                        echo '<input type="hidden" name="name" value="' . $item['name'] . '">';
                        echo '<input type="hidden" name="price" value="' . $item['price'] . '">';
                        echo '<input type="hidden" name="img_src" value="' . $item['img'] . '">';
                        echo '<button type="submit" name="add_to_cart" class="order-btn">주문하기</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="menu-category">
                <h2>디저트</h2>
                <div class="menu-items">
                    <?php
                    $dessert_items = [
                        ['name' => '치즈케이크', 'price' => 5000, 'img' => 'https://images.unsplash.com/photo-1524351199678-941a58a3df50?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '티라미수', 'price' => 5500, 'img' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '초코무스', 'price' => 5000, 'img' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '마카롱', 'price' => 3000, 'img' => 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '크로플', 'price' => 4500, 'img' => 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80'],
                        ['name' => '브라우니', 'price' => 4000, 'img' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80']
                    ];

                    foreach ($dessert_items as $item) {
                        echo '<div class="menu-item">';
                        echo '<img src="' . $item['img'] . '" alt="' . $item['name'] . '">';
                        echo '<h3>' . $item['name'] . '</h3>';
                        echo '<p>₩' . number_format($item['price']) . '</p>';
                        echo '<form method="post">';
                        echo '<input type="hidden" name="name" value="' . $item['name'] . '">';
                        echo '<input type="hidden" name="price" value="' . $item['price'] . '">';
                        echo '<input type="hidden" name="img_src" value="' . $item['img'] . '">';
                        echo '<button type="submit" name="add_to_cart" class="order-btn">주문하기</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="cart">
            <h2>장바구니</h2>
            <div class="cart-items">
                <?php
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $index => $item) {
                        echo '<div class="cart-item">';
                        echo '<img src="' . $item['img_src'] . '" alt="' . $item['name'] . '">';
                        echo '<div class="cart-item-info">';
                        echo '<div class="cart-item-name">' . $item['name'] . '</div>';
                        echo '<div class="cart-item-price">₩' . number_format($item['price']) . '</div>';
                        echo '<div class="cart-item-quantity">';
                        echo '<form method="post" style="display: inline;">';
                        echo '<input type="hidden" name="index" value="' . $index . '">';
                        echo '<input type="hidden" name="change" value="-1">';
                        echo '<button type="submit" name="update_quantity" class="quantity-btn">-</button>';
                        echo '</form>';
                        echo '<span class="quantity">' . $item['quantity'] . '</span>';
                        echo '<form method="post" style="display: inline;">';
                        echo '<input type="hidden" name="index" value="' . $index . '">';
                        echo '<input type="hidden" name="change" value="1">';
                        echo '<button type="submit" name="update_quantity" class="quantity-btn">+</button>';
                        echo '</form>';
                        echo '<form method="post" style="display: inline;">';
                        echo '<input type="hidden" name="index" value="' . $index . '">';
                        echo '<button type="submit" name="remove_item" class="remove-btn">삭제</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="empty-cart">장바구니가 비어있습니다.</p>';
                }
                ?>
            </div>
            <div class="cart-total">
                <p>총 합계: <span id="totalPrice">₩<?php echo number_format($total_price); ?></span></p>
                <form method="post" action="checkout.php">
                    <button type="submit" class="checkout-btn">결제하기</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 