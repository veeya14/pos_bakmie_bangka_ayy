<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Pelanggan | Bakmie Bangka AYY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let cart = [];

        function showCategory(category) {
            document.querySelectorAll('.menu-category').forEach(c => c.classList.add('hidden'));
            document.getElementById(category).classList.remove('hidden');

            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('bg-blue-500', 'text-white', 'shadow-lg'));
            document.querySelector(`[data-tab="${category}"]`).classList.add('bg-blue-500', 'text-white', 'shadow-lg');
        }

        function addToCart(name, price) {
            const existing = cart.find(item => item.name === name);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ name, price, qty: 1 });
            }
            renderCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const cartContainer = document.getElementById("cart-items");
            const totalText = document.getElementById("cart-total");
            cartContainer.innerHTML = "";
            let total = 0;

            if (cart.length === 0) {
                cartContainer.innerHTML = '<p class="text-gray-500 text-center py-5">Keranjang kosong 🛒</p>';
                totalText.textContent = "Rp 0";
                return;
            }

            cart.forEach((item, index) => {
                total += item.price * item.qty;
                cartContainer.innerHTML += `
                    <div class="flex justify-between items-center py-2 border-b">
                        <div>
                            <p class="font-medium">${item.name}</p>
                            <p class="text-sm text-gray-500">${item.qty}x Rp ${item.price.toLocaleString()}</p>
                        </div>
                        <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                    </div>
                `;
            });

            totalText.textContent = "Rp " + total.toLocaleString();
        }

        function toggleCart() {
            document.getElementById("cart-modal").classList.toggle("hidden");
        }
    </script>
</head>
<body class="bg-gradient-to-b from-blue-50 to-white min-h-screen font-sans text-gray-800">

    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white/70 backdrop-blur-md border-b border-blue-100 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                🍜 <span>Bakmie Bangka AYY</span>
            </h1>
            <button onclick="toggleCart()" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition duration-300 shadow-md">
                🧺 Lihat Keranjang (<span id="cart-count">0</span>)
            </button>
        </div>
    </header>

    <!-- Search dan Tabs -->
    <section class="max-w-5xl mx-auto mt-8 px-6 text-center">
        <h2 class="text-3xl font-semibold text-blue-700 mb-4">Pilih Menu Favoritmu</h2>

        <!-- Search -->
        <div class="relative w-full sm:w-1/2 mx-auto mb-6">
            <input type="text" placeholder="Cari menu..." class="w-full px-5 py-3 rounded-full shadow-sm border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
            <span class="absolute right-4 top-3.5 text-blue-500 text-xl">🔍</span>
        </div>

        <!-- Tabs -->
        <div class="flex justify-center flex-wrap gap-3">
            <button data-tab="bakmie" onclick="showCategory('bakmie')" class="tab-button bg-blue-500 text-white px-5 py-2 rounded-full shadow-md transition">Bakmie</button>
            <button data-tab="cemilan" onclick="showCategory('cemilan')" class="tab-button px-5 py-2 rounded-full hover:bg-blue-100 transition">Cemilan</button>
            <button data-tab="minuman" onclick="showCategory('minuman')" class="tab-button px-5 py-2 rounded-full hover:bg-blue-100 transition">Minuman</button>
        </div>
    </section>

    <!-- Menu Cards -->
    <main class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

        <!-- Bakmie -->
        <div id="bakmie" class="menu-category grid grid-cols-subgrid gap-8 col-span-full">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition duration-300">
                <img src="https://source.unsplash.com/400x300/?noodle" alt="Bakmie Ayam Original" class="w-full h-44 object-cover">
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-gray-800">Bakmie Ayam Original</h3>
                    <p class="text-gray-500 mt-1">Rp 25.000</p>
                    <button onclick="addToCart('Bakmie Ayam Original', 25000)" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-xl hover:bg-blue-600 transition">Tambah ke Keranjang</button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition duration-300">
                <img src="https://source.unsplash.com/400x300/?chicken-noodle" alt="Bakmie Pedas Spesial" class="w-full h-44 object-cover">
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-gray-800">Bakmie Pedas Spesial</h3>
                    <p class="text-gray-500 mt-1">Rp 28.000</p>
                    <button onclick="addToCart('Bakmie Pedas Spesial', 28000)" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-xl hover:bg-blue-600 transition">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>

        <!-- Cemilan -->
        <div id="cemilan" class="menu-category hidden grid grid-cols-subgrid gap-8 col-span-full">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition duration-300">
                <img src="https://source.unsplash.com/400x300/?dumplings" alt="Pangsit Goreng" class="w-full h-44 object-cover">
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-gray-800">Pangsit Goreng</h3>
                    <p class="text-gray-500 mt-1">Rp 18.000</p>
                    <button onclick="addToCart('Pangsit Goreng', 18000)" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-xl hover:bg-blue-600 transition">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>

        <!-- Minuman -->
        <div id="minuman" class="menu-category hidden grid grid-cols-subgrid gap-8 col-span-full">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition duration-300">
                <img src="https://source.unsplash.com/400x300/?ice-tea" alt="Es Teh Manis" class="w-full h-44 object-cover">
                <div class="p-5">
                    <h3 class="font-semibold text-lg text-gray-800">Es Teh Manis</h3>
                    <p class="text-gray-500 mt-1">Rp 8.000</p>
                    <button onclick="addToCart('Es Teh Manis', 8000)" class="mt-4 w-full bg-blue-500 text-white py-2 rounded-xl hover:bg-blue-600 transition">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Cart Modal -->
    <div id="cart-modal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex justify-center items-center">
        <div class="bg-white rounded-2xl w-96 max-h-[80vh] overflow-y-auto shadow-xl p-6 relative">
            <button onclick="toggleCart()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-lg">✕</button>
            <h2 class="text-xl font-semibold text-blue-600 mb-4 flex items-center gap-2">🧺 Keranjang Pesanan</h2>

            <div id="cart-items"></div>

            <div class="mt-5 border-t pt-3 flex justify-between items-center">
                <p class="font-semibold text-gray-800">Total:</p>
                <p id="cart-total" class="text-blue-600 font-bold">Rp 0</p>
            </div>

            <button class="mt-5 w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition shadow-md">Konfirmasi Pesanan</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8 py-4 shadow-inner">
        <p class="text-center text-gray-500 text-sm">© 2025 Bakmie Bangka AYY — POS Sistem Pemesanan Modern</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showCategory('bakmie');
        });
    </script>
</body>
</html>
