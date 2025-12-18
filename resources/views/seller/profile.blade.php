@extends('layouts.layout')

@section('title', 'Profile')

@section('content')
    @include('partials.page-header', ['title' => 'Profile'])

    <div class="row mt-4 g-4">

        {{-- LEFT CARD --}}
        <div class="col-lg-4">
            <div class="card text-center p-4 shadow-sm">
              <div class="position-relative d-inline-block">

    <img
        src="{{ $seller->photo
            ? asset('storage/' . $seller->photo)
            : asset('image/profile.jpg') }}"
        class="rounded-circle"
        style="width: 100px; height: 100px; object-fit: cover;"
    >

    {{-- ICON UPLOAD FOTO --}}
    <form method="POST"
          action="{{ route('seller.profile.update') }}"
          enctype="multipart/form-data">
        @csrf
        @method('PATCH')

<label for="photoUpload"
       class="position-absolute bottom-0 end-0 bg-white border rounded-circle d-flex align-items-center justify-content-center shadow-sm"
       style="width: 28px; height: 28px; cursor: pointer; opacity: 0.85;">
    <i class="bi bi-pencil-fill" style="font-size: 12px;"></i>
</label>


        <input type="file"
               id="photoUpload"
               name="photo"
               class="d-none"
               onchange="this.form.submit()">
    </form>

</div>


                <h5 class="fw-semibold">{{ $seller->name }}</h5>
            </div>
        </div>

        {{-- RIGHT CONTENT --}}
        <div class="col-lg-8">

            {{-- PROFILE INFO --}}
            <div class="card p-4 shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0">Profile</h5>

                    <button class="button-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        Edit Profile
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Name</label>
                    <p>{{ $seller->name }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <p>{{ $seller->email }}</p>
                </div>
            </div>

            {{-- SECURITY --}}
            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0">Security</h5>

                    <button class="button-edit" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        Edit Password
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <p>••••••••</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= MODAL EDIT PROFILE ================= --}}
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('seller.profile.update') }}">
                     @csrf
                     @method('PATCH')

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $seller->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $seller->email) }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger text-white" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button class="btn btn-success">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- ================= MODAL CHANGE PASSWORD ================= --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('seller.profile.password') }}">
                    @csrf
                     @method('PATCH')

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" id="newPassword" class="form-control">

                            <small id="passwordError" class="text-danger d-none">
                                Password must be at least 8 characters long and include uppercase letters, lowercase
                                letters, and numbers.
                            </small>
                            
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="confirmPassword" class="form-control">

                            <small id="confirmError" class="text-danger d-none">
                                Passwords do not match.
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger text-white" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button id="updatePasswordBtn" class="btn btn-success text-white">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');
        const passwordError = document.getElementById('passwordError');
        const confirmError = document.getElementById('confirmError');
        const submitBtn = document.getElementById('updatePasswordBtn');

        function validatePassword() {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            const valid = regex.test(newPassword.value);
            passwordError.classList.toggle('d-none', valid);
            return valid;
        }

        function validateConfirm() {
            const match = newPassword.value === confirmPassword.value;
            confirmError.classList.toggle('d-none', match);
            return match;
        }

          function validateForm() {
            submitBtn.disabled = !(validatePassword() && validateConfirm());
        }

        newPassword.addEventListener('input', validateForm);
        confirmPassword.addEventListener('input', validateForm);
    </script>
@endpush