<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Daftar Menu</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body>
<div class="container-fluid">
  <div class="row flex-nowrap">

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="col-12 col-md-10 p-3 main-content">

      <!-- Header -->
      <div class="d-none d-lg-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-semibold text-secondary">Daftar Menu</h1>
        <button type="button"
                class="btn text-white d-flex align-items-center gap-2 px-3 py-2 me-3"
                style="background-color: #FFBC33; border: none; border-radius: 8px;"
                data-bs-toggle="modal" data-bs-target="#addMenuModal">
          <i class="bi bi-plus-lg"></i><span>Tambah Menu</span>
        </button>
      </div>

      <!-- Kategori -->
      <div class="mb-4 category-btn d-flex flex-wrap gap-2">
        @foreach ($categories as $category)
          <a href="{{ route('seller.menus.index', ['category' => $category->id_category]) }}" 
             class="btn btn-outline-secondary {{ $selectedCategory == $category->id_category ? 'active text-white bg-warning border-warning' : '' }}">
            {{ $category->name }}
          </a>
        @endforeach
      </div>

      <!-- Grid Menu -->
      <div class="row g-4">
        @forelse ($menu as $item)
          <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-start menu-card h-100">
              <img src="{{ $item->menu_image ? asset($item->menu_image) : asset('image/default-food.png') }}" 
                class="card-img-top mx-auto mt-3 img-fluid rounded-4" 
                 alt="{{ $item->menu_name }}" 
                 style="height: 150px; object-fit: contain; object-position: center;">
              <div class="card-body">
                <h6 class="fw-semibold mb-1">{{ $item->menu_name }}</h6>
                <div class="d-flex justify-content-between align-items-center">
                  <p class="text-muted mb-0 mt-1">Rp {{ number_format($item->menu_price, 0, ',', '.') }}</p>
                  <div class="d-flex gap-1">
                    <!-- Edit Button -->
                    <a href="#"
                       class="btn btn-outline-success btn-sm rounded-3 mt-1 edit-menu-btn"
                       data-id="{{ $item->id_menu }}"
                       data-name="{{ $item->menu_name }}"
                       data-category="{{ $item->id_category }}"
                       data-price="{{ $item->menu_price }}"
                       data-status="{{ $item->menu_status }}"
                       data-description="{{ $item->menu_description }}"
                       data-image="{{ $item->menu_image ? asset($item->menu_image) : '' }}"
                       data-bs-toggle="modal"
                       data-bs-target="#editMenuModal">
                      <i class="bi bi-pencil"></i>
                    </a>

                    <!-- Delete Button -->
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-3 mt-1 delete-menu-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteMenuModal"
                            data-form-id="delete-form-{{ $item->id_menu }}">
                      <i class="bi bi-trash"></i>
                    </button>
                    <form id="delete-form-{{ $item->id_menu }}" action="{{ route('seller.menus.destroy', $item->id_menu) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
            <p>Tidak ada menu untuk kategori ini.</p>
          </div>
        @endforelse
      </div>

    </div>
  </div>
</div>

<!-- Modal Tambah Menu -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold" id="addMenuLabel">Tambah Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">
        <form action="{{ route('seller.menus.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="namaMenu" class="form-label">Nama Menu</label>
            <input type="text" class="form-control rounded-3" id="namaMenu" name="menu_name" placeholder="Masukkan nama menu" required>
          </div>
          <div class="mb-3">
            <label for="kategoriMenu" class="form-label">Kategori Menu</label>
            <select class="form-select rounded-3" id="kategoriMenu" name="id_category" required>
              <option value="" disabled selected>Pilih kategori menu</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id_category }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="hargaMenu" class="form-label">Harga Menu</label>
            <input type="number" class="form-control rounded-3" id="hargaMenu" name="menu_price" placeholder="Masukkan harga menu" step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="statusMenu" class="form-label">Status Menu</label>
            <select class="form-select rounded-3" id="statusMenu" name="menu_status" required>
              <option value="" disabled selected>Pilih status menu</option>
              <option value="available">Tersedia</option>
              <option value="sold_out">Tidak Tersedia</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="deskripsiMenu" class="form-label">Deskripsi Menu</label>
            <textarea class="form-control rounded-3" id="deskripsiMenu" name="menu_description" rows="3" placeholder="Masukkan deskripsi menu"></textarea>
          </div>
          <div class="mb-4">
            <label for="fotoMenu" class="form-label">Foto Menu</label>
            <div class="border rounded-3 d-flex justify-content-center align-items-center" style="height: 120px; background-color: #f9f9f9;">
              <label for="fotoMenu" class="text-muted d-flex flex-column align-items-center" style="cursor: pointer;">
                <i class="bi bi-image fs-1"></i>
                <small>Upload Foto</small>
              </label>
              <input type="file" id="fotoMenu" name="menu_image" class="d-none" accept="image/*">
            </div>
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success px-4">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Menu -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold" id="editMenuLabel">Edit Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">
        <form id="editMenuForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <!-- Fields sama seperti add -->
          <div class="mb-3">
            <label for="editNamaMenu" class="form-label">Nama Menu</label>
            <input type="text" class="form-control rounded-3" id="editNamaMenu" name="menu_name" required>
          </div>
          <div class="mb-3">
            <label for="editKategoriMenu" class="form-label">Kategori Menu</label>
            <select class="form-select rounded-3" id="editKategoriMenu" name="id_category" required>
              <option value="" disabled>Pilih kategori menu</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id_category }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="editHargaMenu" class="form-label">Harga Menu</label>
            <input type="number" class="form-control rounded-3" id="editHargaMenu" name="menu_price" step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="editStatusMenu" class="form-label">Status Menu</label>
            <select class="form-select rounded-3" id="editStatusMenu" name="menu_status" required>
              <option value="" disabled>Pilih status menu</option>
              <option value="available">Tersedia</option>
              <option value="sold_out">Tidak Tersedia</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editDeskripsiMenu" class="form-label">Deskripsi Menu</label>
            <textarea class="form-control rounded-3" id="editDeskripsiMenu" name="menu_description" rows="3"></textarea>
          </div>
          <div class="mb-4">
            <label class="form-label">Foto Menu</label>
            <div class="d-flex flex-column align-items-center">
              <img id="editFotoPreview" src="" alt="Foto Menu" class="img-fluid rounded-3 mb-2" style="height: 120px; object-fit: contain; background-color: #f9f9f9; width: 100%;">
              <button type="button" class="btn btn-outline-primary btn-sm mb-2" id="editFotoBtn">Ganti Foto</button>
              <input type="file" id="editFotoMenu" name="menu_image" class="d-none" accept="image/*">
            </div>
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success px-4">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Delete Menu -->
<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-labelledby="deleteMenuLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold" id="deleteMenuLabel">Hapus Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin menghapus menu ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // EDIT MENU
  const editButtons = document.querySelectorAll('.edit-menu-btn');
  const editForm = document.getElementById('editMenuForm');
  const editFotoPreview = document.getElementById('editFotoPreview');
  const editFotoInput = document.getElementById('editFotoMenu');
  const editFotoBtn = document.getElementById('editFotoBtn');

  editButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      const name = this.dataset.name;
      const category = this.dataset.category;
      const price = this.dataset.price;
      const status = this.dataset.status;
      const description = this.dataset.description;
      const image = this.dataset.image;

      document.getElementById('editNamaMenu').value = name;
      document.getElementById('editKategoriMenu').value = category;
      document.getElementById('editHargaMenu').value = price;
      document.getElementById('editStatusMenu').value = status;
      document.getElementById('editDeskripsiMenu').value = description;
      editFotoPreview.src = image ? image : "{{ asset('image/default-food.png') }}";

      editForm.action = `/seller/menus/${id}`;
    });
  });

  editFotoBtn.addEventListener('click', () => editFotoInput.click());

  editFotoInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        editFotoPreview.src = e.target.result;
      }
      reader.readAsDataURL(this.files[0]);
    }
  });

  // DELETE MENU
  let deleteFormId;
  const deleteModal = document.getElementById('deleteMenuModal');
  const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

  deleteModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    deleteFormId = button.getAttribute('data-form-id');
  });

  confirmDeleteBtn.addEventListener('click', function() {
    if(deleteFormId) {
      document.getElementById(deleteFormId).submit();
    }
  });
});
</script>
</body>
</html>
