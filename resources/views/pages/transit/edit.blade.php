@extends('pages.transit.layout')

@section('title', __('translates.navbar.transit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="transit-card">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0"><i class="fas fa-user-edit text-primary"></i> Edit Profile</h3>
                    <a href="{{route('profile.index')}}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <form action="{{route('profile.update', auth()->id())}}" method="POST" enctype="multipart/form-data" id="editProfileForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <div class="profile-image-wrapper mb-3">
                                    <img src="{{asset('assets/images/diamond-green.png')}}" 
                                         alt="Profile" 
                                         class="rounded-circle border" 
                                         width="200" 
                                         height="200" 
                                         style="object-fit: cover; border-width: 4px !important; border-color: #667eea !important;">
                                </div>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="avatar" id="avatar" class="form-control file-input" 
                                           accept=".jpg,.jpeg,.png">
                                    <label for="avatar" class="btn btn-outline-primary">
                                        <i class="fas fa-camera"></i> Change Photo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="fullName">
                                    <i class="fas fa-user text-primary"></i> Full Name
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="fullName" 
                                       class="form-control" 
                                       value="{{auth()->user()->getFullnameAttribute()}}"
                                       placeholder="Enter your full name" 
                                       required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" for="email">
                                    <i class="fas fa-envelope text-primary"></i> Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control" 
                                       value="{{auth()->user()->getAttribute('email')}}"
                                       placeholder="Enter your email" 
                                       required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold" for="phone">
                                        <i class="fas fa-phone text-primary"></i> Phone Number
                                    </label>
                                    <input type="text" 
                                           name="phone" 
                                           id="phone" 
                                           class="form-control" 
                                           value="{{auth()->user()->getAttribute('phone')}}"
                                           placeholder="Enter your phone number">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold" for="voen">
                                        <i class="fas fa-id-card text-primary"></i> VOEN
                                    </label>
                                    <input type="text" 
                                           name="voen" 
                                           id="voen" 
                                           class="form-control" 
                                           value="{{auth()->user()->getAttribute('voen')}}"
                                           placeholder="Enter VOEN number">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" for="balance">
                                    <i class="fas fa-wallet text-primary"></i> Balance (AZN)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">AZN</span>
                                    <input type="number" 
                                           name="balance" 
                                           id="balance" 
                                           class="form-control" 
                                           value="{{auth()->user()->getAttribute('balance') ?? 0}}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00">
                                </div>
                                <small class="text-muted">Add or update your account balance</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" for="password">
                                    <i class="fas fa-lock text-primary"></i> New Password (Optional)
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control" 
                                           placeholder="Leave blank to keep current password"
                                           minlength="8">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 8 characters. Leave blank if you don't want to change it.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold" for="password_confirmation">
                                    <i class="fas fa-lock text-primary"></i> Confirm New Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Confirm new password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{route('profile.index')}}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const icon = $(this).find('i');
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#togglePasswordConfirmation').on('click', function() {
        const passwordInput = $('#password_confirmation');
        const icon = $(this).find('i');
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Avatar preview
    $('#avatar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-image-wrapper img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    $('#editProfileForm').on('submit', function(e) {
        const password = $('#password').val();
        const passwordConfirmation = $('#password_confirmation').val();

        if (password && password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
            return false;
        }

        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
    });

    // Password confirmation validation
    $('#password_confirmation').on('keyup', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        
        if (password && confirmPassword) {
            if (password !== confirmPassword) {
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');
            } else {
                $(this).addClass('is-valid');
                $(this).removeClass('is-invalid');
            }
        } else {
            $(this).removeClass('is-invalid is-valid');
        }
    });
});
</script>

<style>
.profile-image-wrapper {
    position: relative;
    display: inline-block;
}
.profile-image-wrapper img {
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.profile-image-wrapper:hover img {
    transform: scale(1.05);
}
.file-upload-wrapper input[type="file"] {
    display: none;
}
</style>
@endsection
