@extends('layouts.layout')

@section('title', 'Menu')

@section('content')
                {{-- PAGE HEADER --}}
                @include('partials.page-header', [
                        'title' => 'Menu',
                        'button' => '
                        <button class="btn text-white d-flex align-items-center gap-2 px-3 py-2 me-3"
                            style="background-color: #FFBC33; border: none; border-radius: 8px;"
                            data-bs-toggle="modal"
                            data-bs-target="#addMenuModal">
                            <i class="bi bi-plus-lg"></i><span>Add Menu</span>
                        </button>
                    ',
                ])

                <!-- Kategori -->
                <div class="category-scroll mb-3">
                @foreach ($categories as $category)
                    <form action="{{ route('seller.menus.index') }}" method="GET" class="d-inline">
                        <input type="hidden" name="category" value="{{ $category->id_category }}">
                        <button type="submit"
                            class="px-3 py-1 {{ $selectedCategory == $category->id_category ? 'category-borderOn' : 'category-borderOff' }}">
                            {{ $category->name }}
                        </button>
                    </form>
                @endforeach
                </div>

                

<!-- GRID MENU -->
<div class="row g-4">
@foreach ($menu as $m)
    <div class="col-6 col-md-3">

        <!-- Card Menu -->
        <div class="card border-0 shadow-sm rounded-4 menu-card h-100 position-relative"
     data-id="{{ $m->id_menu }}"
     data-name="{{ $m->menu_name }}"
     data-price="{{ $m->menu_price }}"
     data-description="{{ $m->menu_description }}"
     data-image="{{ asset($m->menu_image) }}"
     data-category="{{ $m->id_category }}"
     data-status="{{ $m->menu_status }}"
     data-display="{{ $m->display_status }}"
     style="cursor:pointer;">

            <img src="{{ asset($m->menu_image) }}" 
                 class="card-img-top mx-auto mt-3 img-fluid">

            <div class="card-body">
                @if (in_array($m->id_menu, $bestSellerIds))
                    <span class="badge-best-seller">
                         👍 Best Seller
                    </span>
                @endif

                <h6 class="fw-semibold mb-1">{{ $m->menu_name }}</h6>

                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0 mt-1">Rp {{ number_format($m->menu_price) }}</p>

                    <div class="d-flex gap-1">

                        <!-- EDIT BUTTON -->
                        <button 
                            class="btn btn-outline-success btn-sm rounded-3 mt-1 btnEditCard"
                            data-id="{{ $m->id_menu }}"
                            onclick="event.stopPropagation()">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <!-- DELETE BUTTON -->
                        <button 
                            class="btn btn-outline-danger btn-sm rounded-3 mt-1 btnDeleteCard"
                            data-id="{{ $m->id_menu }}"
                            onclick="event.stopPropagation()">
                            <i class="bi bi-trash"></i>
                        </button>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endforeach
</div>


</div>


<!-- ============================
     MODAL ADD MENU 
============================= -->
<div class="modal fade" id="addMenuModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold">Add Menu</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <form action="{{ route('seller.menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- NAME -->
                    <div class="mb-3">
                        <label class="form-label">Menu Name</label>
                        <input type="text" name="menu_name" class="form-control rounded-3" required>
                    </div>

                    <!-- CATEGORY -->
                    <div class="mb-3">
                        <label class="form-label">Menu Category</label>
                        <select name="id_category"
        id="menuCategorySelect"
        class="form-select rounded-3"
        required>
    <option value="" selected disabled>Choose Menu Category</option>

    @foreach ($categories as $c)
        <option value="{{ $c->id_category }}">{{ $c->name }}</option>
    @endforeach

    <option value="__add_category__">+ Add Category</option>
</select>


                    </div>

                    <!-- PRICE -->
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="menu_price" class="form-control rounded-3" required>
                    </div>

                    <!-- STATUS -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="menu_status" class="form-select rounded-3" required>
                            <option disabled selected>Choose Menu Status</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="menu_description" class="form-control rounded-3" rows="3"></textarea>
                    </div>

                    <!-- IMAGE WITH PREVIEW -->
                    <div class="mb-3">
                        <label class="form-label">Menu Image</label>

                        <!-- Preview Box -->
<div id="addImagePreviewBox"
    class="border rounded-3 d-flex justify-content-center align-items-center position-relative"
    style="height: 180px; background-color: #f9f9f9; cursor:pointer;"
    onclick="document.getElementById('addMenuImage').click()">

    <div id="addImagePlaceholder" class="text-muted d-flex flex-column align-items-center">
        <i class="bi bi-image fs-1"></i>
        <small>Upload Image</small>
    </div>

    <img id="addImagePreview"
     src=""
     class="d-none rounded border"
     style="width: 140px; height: 140px; object-fit: cover; position: absolute;">
</div>


                        <input type="file" id="addMenuImage" name="menu_image" class="d-none" accept="image/*">

                        <small class="text-muted">Accepted: PNG, JPG, JPEG (Max 10 MB)</small>
                    </div>

                    <!-- DISPLAY -->
                    <div class="mb-3">
                        <label class="form-label">Display Status</label>
                        <select name="display_status" class="form-select rounded-3" required>
                            <option disabled selected>Choose Display Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


                <!-- ============================
                     MODAL EDIT MENU
                ============================= -->
                <div class="modal fade" id="editMenuModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 rounded-4">

                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-semibold">Edit Menu</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body px-4">
                                <form id="editMenuForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label">Menu Name</label>
                                        <input type="text" id="editMenuName" name="menu_name" class="form-control rounded-3" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select id="editMenuCategory" name="id_category" class="form-select rounded-3" required>
                                            @foreach ($categories as $c)
                                                <option value="{{ $c->id_category }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" id="editMenuPrice" name="menu_price" class="form-control rounded-3" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select id="editMenuStatus" name="menu_status" class="form-select rounded-3">
                                            <option value="available">Available</option>
                                            <option value="unavailable">Unavailable</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea id="editMenuDescription" name="menu_description" class="form-control rounded-3" rows="3"></textarea>
                                    </div>

   <!-- IMAGE -->
<div class="mb-3 text-center">
    <label class="form-label d-block">Menu Image</label>

    <!-- PREVIEW GAMBAR -->
    <div class="mb-2 d-flex justify-content-center">
        <img id="editPreviewImage"
             src=""
             class="rounded border"
             style="width: 180px; height: 180px; object-fit: cover;">
    </div>

    <!-- INPUT FILE -->
    <input type="file" id="editMenuImage" name="menu_image" class="d-none" accept="image/*">

    <!-- BUTTON GANTI FOTO DI TENGAH -->
    <button type="button" class="btn btn-secondary btn-sm mt-2" id="btnChangeImage">
        Ganti Foto
    </button>

    <small class="text-muted d-block mt-2">
        Kosongkan jika tidak ingin mengganti gambar.
    </small>
</div>

                                 <div class="mb-3">
                                        <label class="form-label">Display Status</label>
                                        <select id="editDisplayStatus" name="display_status" class="form-select rounded-3">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn btn-success">Update</button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ============================
                     MODAL DETAIL
                ============================= -->
                <div class="modal fade" id="menuDetailModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content rounded-4">

                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Menu Details</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <img id="detailMenuImage" class="img-fluid rounded-3 border"
                                            style="object-fit: cover; width: 100%; height: 180px;">
                                    </div>

                                    <div class="col-md-8">
                                        <h4 id="detailMenuName" class="fw-bold"></h4>
                                        <p id="detailMenuDescription" class="text-muted mt-2"></p>
                                        <h5 id="detailMenuPrice" class="fw-bold mt-3"></h5>

                                        <div class="mt-4 d-flex gap-2">
                                            <button class="btn btn-outline-success px-4 py-2 rounded-3 fw-semibold"
                                                id="btnDetailEdit">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </button>

                                            <button class="btn btn-outline-danger px-4 py-2 rounded-3 fw-semibold"
                                                id="btnDetailDelete">
                                                <i class="bi bi-trash me-2"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- ============================
                     MODAL CONFIRM DELETE
                ============================= -->
                <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4">

                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus menu ini?
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ============================
     MODAL ADD CATEGORY
============================= -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-semibold">Add Category</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <form id="addCategoryForm"
                      action="{{ route('seller.categories.store') }}"
                      method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text"
                               name="name"
                               class="form-control rounded-3"
                               placeholder="Ex: Bakmie, Minuman"
                               required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success px-4">
                            Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    let selectedId = null;
    let selectedData = {};

    /* =========================
       DETAIL MENU
    ========================== */
    document.querySelectorAll(".menu-card").forEach(card => {
        card.addEventListener("click", function () {
            selectedId = this.dataset.id;

            selectedData = {
                name: this.dataset.name,
                price: this.dataset.price,
                description: this.dataset.description,
                image: this.dataset.image,
                category: this.dataset.category,
                status: this.dataset.status,
                display: this.dataset.display
            };

            document.getElementById("detailMenuName").textContent = selectedData.name;
            document.getElementById("detailMenuPrice").textContent =
                "Rp " + Number(selectedData.price).toLocaleString("id-ID");
            document.getElementById("detailMenuDescription").textContent = selectedData.description;
            document.getElementById("detailMenuImage").src = selectedData.image;

            new bootstrap.Modal(
                document.getElementById("menuDetailModal")
            ).show();
        });
    });

    /* =========================
       EDIT MENU
    ========================== */
    function openEditModal() {
        const form = document.getElementById("editMenuForm");
        form.action = `/seller/menus/${selectedId}`;

        document.getElementById("editMenuName").value = selectedData.name;
        document.getElementById("editMenuPrice").value = selectedData.price;
        document.getElementById("editMenuDescription").value = selectedData.description;
        document.getElementById("editMenuCategory").value = selectedData.category;
        document.getElementById("editMenuStatus").value = selectedData.status;
        document.getElementById("editDisplayStatus").value = selectedData.display;
        document.getElementById("editPreviewImage").src = selectedData.image;

        new bootstrap.Modal(
            document.getElementById("editMenuModal")
        ).show();
    }

    document.querySelectorAll(".btnEditCard").forEach(btn => {
        btn.addEventListener("click", e => {
            e.stopPropagation();
            const card = btn.closest(".menu-card");

            selectedId = card.dataset.id;
            selectedData = {
                name: card.dataset.name,
                price: card.dataset.price,
                description: card.dataset.description,
                image: card.dataset.image,
                category: card.dataset.category,
                status: card.dataset.status,
                display: card.dataset.display
            };

            openEditModal();
        });
    });

    /* =========================
       ADD CATEGORY FROM SELECT
    ========================== */
   const categorySelect = document.getElementById('menuCategorySelect');

if (categorySelect) {
    categorySelect.addEventListener('change', function () {
        if (this.value === '__add_category__') {
            this.value = '';

            const addMenuModalEl = document.getElementById('addMenuModal');
            const addMenuModal = bootstrap.Modal.getInstance(addMenuModalEl);

            if (addMenuModal) {
                addMenuModal.hide();
            }

            setTimeout(() => {
                new bootstrap.Modal(
                    document.getElementById('addCategoryModal')
                ).show();
            }, 300);
        }
    });
}

const btnDetailEdit = document.getElementById('btnDetailEdit');

if (btnDetailEdit) {
    btnDetailEdit.addEventListener('click', () => {

        // tutup modal detail
        const detailModal = bootstrap.Modal.getInstance(
            document.getElementById('menuDetailModal')
        );
        detailModal.hide();

        // buka modal edit setelah modal detail tertutup
        setTimeout(() => {
            openEditModal();
        }, 300);
    });
}

// ===============================
// DELETE MENU (GRID & DETAIL)
// ===============================
let deleteMenuId = null;

// klik delete di card
document.querySelectorAll('.btnDeleteCard').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        deleteMenuId = btn.dataset.id;

        new bootstrap.Modal(
            document.getElementById('confirmDeleteModal')
        ).show();
    });
});

// klik delete di menu detail
const btnDetailDelete = document.getElementById('btnDetailDelete');
if (btnDetailDelete) {
    btnDetailDelete.addEventListener('click', () => {
        deleteMenuId = selectedId;

        new bootstrap.Modal(
            document.getElementById('confirmDeleteModal')
        ).show();
    });
}

// confirm delete
document.getElementById('confirmDeleteBtn')?.addEventListener('click', () => {
    if (!deleteMenuId) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/seller/menus/${deleteMenuId}`;

    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;

    document.body.appendChild(form);
    form.submit();
});

});
</script>
@endpush
