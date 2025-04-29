// 장바구니 관련 변수
let cart = [];
let totalPrice = 0;

// IMP 초기화
const IMP = window.IMP;
IMP.init('imp00000000'); // 실제 상점 아이디로 교체 필요

document.addEventListener('DOMContentLoaded', () => {
    // 저장된 테마 적용
    const savedTheme = localStorage.getItem('theme') || 'modern';
    applyTheme(savedTheme);

    // 테마 버튼 이벤트
    const themeButtons = document.querySelectorAll('.theme-btn');
    if (themeButtons.length > 0) {
        themeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const theme = button.dataset.theme;
                applyTheme(theme);
                
                // 활성화된 버튼 스타일 업데이트
                themeButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
            
            // 현재 테마에 맞는 버튼을 활성화
            if (button.dataset.theme === savedTheme) {
                button.classList.add('active');
            }
        });
    } else {
        console.error('Theme buttons not found');
    }

    // 주문 버튼 이벤트
    const orderButtons = document.querySelectorAll('.order-btn');
    orderButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const menuItem = e.target.closest('.menu-item');
            const name = menuItem.dataset.name;
            const price = parseInt(menuItem.dataset.price);
            const imgSrc = menuItem.querySelector('img').src;
            
            addToCart(name, price, imgSrc);
            
            // 버튼 애니메이션
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 100);
        });
    });
    
    // 결제 버튼 이벤트
    document.getElementById('checkoutBtn').addEventListener('click', () => {
        if (cart.length === 0) {
            alert('장바구니가 비어있습니다.');
            return;
        }
        
        const orderItems = cart.map(item => `${item.name} x ${item.quantity}`).join('\n');
        
        IMP.request_pay({
            pg: 'html5_inicis',
            pay_method: 'card',
            merchant_uid: 'merchant_' + new Date().getTime(),
            name: '카페 주문',
            amount: totalPrice,
            buyer_email: 'buyer@example.com',
            buyer_name: '고객',
            buyer_tel: '010-0000-0000',
            buyer_addr: '서울특별시 강남구 삼성동',
            buyer_postcode: '123-456'
        }, function(rsp) {
            if (rsp.success) {
                alert('결제가 완료되었습니다.');
                cart = [];
                updateCart();
            } else {
                alert(`결제에 실패하였습니다. ${rsp.error_msg}`);
            }
        });
    });
});

function applyTheme(theme) {
    try {
        document.body.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        console.log(`Theme changed to: ${theme}`);
    } catch (error) {
        console.error('Error applying theme:', error);
    }
}

function addToCart(name, price, imgSrc) {
    const existingItem = cart.find(item => item.name === name);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            name,
            price,
            quantity: 1,
            imgSrc
        });
    }
    
    updateCart();
}

function updateQuantity(index, change) {
    cart[index].quantity += change;
    
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    
    updateCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

function updateCart() {
    const cartItems = document.getElementById('cartItems');
    cartItems.innerHTML = '';
    totalPrice = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        totalPrice += itemTotal;
        
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <img src="${item.imgSrc}" alt="${item.name}">
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">₩${item.price.toLocaleString()}</div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                    <button class="remove-btn" onclick="removeItem(${index})">삭제</button>
                </div>
            </div>
        `;
        
        cartItems.appendChild(cartItem);
    });
    
    document.getElementById('totalPrice').textContent = `₩${totalPrice.toLocaleString()}`;
} 