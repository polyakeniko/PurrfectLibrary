@if(session('success'))
    <script>
        localStorage.removeItem('cart');
    </script>
@endif
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Your Cart
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="cart-items"></div>
                    <button id="checkout-btn" class="bg-green-600 text-white px-4 py-2 rounded mt-4">Checkout</button>
                    <form id="checkout-form" action="{{ route('cart.checkout') }}" method="POST" style="display:none;" class="mt-6">
                        @csrf
                        <input type="hidden" name="cart" id="cart-input">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" class="block w-full border rounded p-2" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input name="email" type="email" class="block w-full border rounded p-2" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <input name="phone" class="block w-full border rounded p-2" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <input name="address" class="block w-full border rounded p-2" required>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function renderCart() {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            let html = '';
            if (cart.length === 0) {
                html = '<p>Your cart is empty.</p>';
            } else {
                html = '<ul class="mb-4">';
                cart.forEach((item, idx) => {
                    const stock = typeof item.stock === 'number' ? item.stock : 1;
                    html += `<li class="mb-2 flex items-center gap-2">
                ${item.title} - $${item.price} x
                <input type="number" min="1" max="${stock}" value="${item.quantity}" data-idx="${idx}" class="w-16 border rounded p-1 quantity-input" />
                <span class="text-xs text-gray-500">(max: ${stock})</span>
                <button class="remove-btn text-red-600 ml-2" data-idx="${idx}">Remove</button>
            </li>`;
                });
                html += '</ul>';
            }
            document.getElementById('cart-items').innerHTML = html;

            // Quantity input listeners
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                    let idx = parseInt(this.dataset.idx);
                    const stock = typeof cart[idx].stock === 'number' ? cart[idx].stock : 1;
                    let val = Math.max(1, Math.min(parseInt(this.value) || 1, stock));
                    this.value = val;
                    cart[idx].quantity = val;
                    localStorage.setItem('cart', JSON.stringify(cart));
                    renderCart();
                    updateCartCount();
                });
            });

            // Remove button listeners
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                    let idx = parseInt(this.dataset.idx);
                    cart.splice(idx, 1);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    renderCart();
                    updateCartCount();
                });
            });
        }

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartCount = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
            const badge = document.getElementById('cart-count');
            if (badge) {
                badge.textContent = cartCount;
            }
        }

        document.getElementById('checkout-btn').onclick = function() {
            document.getElementById('checkout-form').style.display = 'block';
            document.getElementById('cart-input').value = localStorage.getItem('cart');
        };

        renderCart();
        document.addEventListener('DOMContentLoaded', function() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const checkoutBtn = document.getElementById('checkout-btn');
            if (!cart.length) {
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            updateCartCount();
        });
    </script>
</x-app-layout>
