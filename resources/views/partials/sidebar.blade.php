<div class="col-12 col-md-2 d-none d-lg-flex flex-column align-items-center sidebar py-4">
    <img src="{{ asset('image/logo.jpg') }}" alt="Logo" class="mb-4" style="width: 100px;">

    <ul class="nav w-100 text-start px-3 flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->is('seller/dashboard*') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">
                Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->is('seller/profile*') ? 'active' : '' }}"
               href="{{ route('seller.profile') }}">
                 Profile
            </a>
        </li>

        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->is('seller/menus*') ? 'active' : '' }}" href="{{ route('seller.menus.index') }}">
                Menu
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->is('seller/orders*') ? 'active' : '' }}" href="{{ route('seller.orders.index') }}">
                Order List
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link {{ request()->is('seller/order-history*') ? 'active' : '' }}" href="{{ route('seller.orders.history') }}">
                Order History
            </a>
        </li>
    </ul>

    <div class="mt-auto mb-3">
        <!-- Tombol Logout -->
        <button type="button" class="btn btn-link text-danger text-decoration-none logout p-0" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
        </button>
    </div>
</div>

<!-- Modal Logout di paling depan/root body -->
<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('seller.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
