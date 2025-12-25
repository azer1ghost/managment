@extends('pages.transit.layout')

@section('title', __('transit.title') . ' | ' . __('transit.login'))

@section('content')
<div class="transit-card">
    <div class="p-4">
        <ul class="nav nav-pills nav-justified mb-4" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="tab-login" data-toggle="tab" href="#pills-login" role="tab"
                   aria-controls="pills-login" aria-selected="true">
                    <i class="fas fa-sign-in-alt"></i> {{ __('transit.login') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-register" data-toggle="tab" href="#pills-register" role="tab"
                   aria-controls="pills-register" aria-selected="false">
                    <i class="fas fa-user-plus"></i> {{ __('transit.register') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Login Tab -->
            <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary pulse-animation" style="filter: drop-shadow(0 5px 20px rgba(102, 126, 234, 0.5));"></i>
                    </div>
                    <h3 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800;">
                        {{ __('transit.message.welcome_back') }}
                    </h3>
                </div>
                
                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius: 15px; border-left: 5px solid #dc3545; animation: shake 0.5s;">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="loginName" style="font-size: 16px;">
                            <i class="fas fa-envelope text-primary me-2"></i> {{ __('transit.form.email') }}
                        </label>
                        <div class="input-group-icon">
                            <input type="email" name="login" id="loginName" class="form-control" 
                                   placeholder="{{ __('transit.form.email') }}" required autofocus
                                   style="padding-left: 50px;">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="loginPassword" style="font-size: 16px;">
                            <i class="fas fa-lock text-primary me-2"></i> {{ __('transit.form.password') }}
                        </label>
                        <div class="input-group-icon">
                            <input type="password" name="password" id="loginPassword" class="form-control" 
                                   placeholder="{{ __('transit.form.password') }}" required
                                   style="padding-left: 50px; padding-right: 50px;">
                            <i class="fas fa-lock input-icon"></i>
                            <button class="btn btn-link password-toggle" type="button" id="togglePassword" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 10; color: #667eea;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="loginCheck" checked>
                                <label class="form-check-label" for="loginCheck">
                                    {{ __('transit.form.remember_me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="#" class="text-primary text-decoration-none">{{ __('transit.form.forgot_password') }}</a>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> {{ __('transit.button.sign_in') }}
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted">{{ __('transit.message.not_member') }} 
                            <a href="#" class="text-primary text-decoration-none" id="switchToRegister">
                                {{ __('transit.message.register_now') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Register Tab -->
            <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                <form action="{{ route('transitRegister') }}" method="POST" enctype="multipart/form-data" id="registerForm">
                    @csrf
                    <input type="hidden" name="role_id" value="4">

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="type">
                            <i class="fas fa-user-tag text-primary"></i> Customer Type
                        </label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Select customer type</option>
                            <option value="legal">Hüquqi (Legal Entity)</option>
                            <option value="people">Fiziki (Individual)</option>
                        </select>
                    </div>

                    <!-- Legal Entity Fields -->
                    <div id="legalFields" style="display: none;">
                        <div class="mb-4">
                            <label class="form-label fw-bold" for="country">
                                <i class="fas fa-globe text-primary"></i> Country
                            </label>
                            <select class="form-select" name="country" id="country">
                                <option value="">Select country</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Russia">Russia</option>
                                <option value="Iran">Iran</option>
                            </select>
                        </div>

                        <div class="mb-4" id="rekvizitField" style="display: none;">
                            <label class="form-label fw-bold" for="rekvisit">
                                <i class="fas fa-file-contract text-primary"></i> Rekvizit
                            </label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="rekvisit" id="rekvisit" class="form-control file-input" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <label for="rekvisit" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                    <div class="file-name">Upload Rekvizit...</div>
                                    <small class="text-muted">PDF, JPG, PNG (Max 10MB)</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" for="voen">
                                <i class="fas fa-id-card text-primary"></i> VOEN
                            </label>
                            <input type="text" name="voen" id="voen" class="form-control" 
                                   placeholder="Enter VOEN number">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="registerName">
                            <i class="fas fa-user text-primary"></i> Full Name
                        </label>
                        <input type="text" name="name" id="registerName" class="form-control" 
                               placeholder="Enter your full name" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="registerEmail">
                            <i class="fas fa-envelope text-primary"></i> Email
                        </label>
                        <input type="email" name="email" id="registerEmail" class="form-control" 
                               placeholder="Enter your email" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="registerPhone">
                            <i class="fas fa-phone text-primary"></i> Phone Number
                        </label>
                        <input type="text" name="phone" id="registerPhone" class="form-control" 
                               placeholder="Enter your phone number" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="registerPassword">
                            <i class="fas fa-lock text-primary"></i> Password
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" id="registerPassword" class="form-control" 
                                   placeholder="Enter password" required minlength="8">
                            <button class="btn btn-outline-secondary" type="button" id="toggleRegisterPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="registerRepeatPassword">
                            <i class="fas fa-lock text-primary"></i> Confirm Password
                        </label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="registerRepeatPassword" 
                                   class="form-control" placeholder="Confirm password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleRegisterRepeatPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="1" id="registerCheck" required>
                        <label class="form-check-label" for="registerCheck">
                            I have read and agree to the <a href="#" class="text-primary">terms and conditions</a>
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i> Register
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted">Already have an account? 
                            <a href="#" class="text-primary text-decoration-none" id="switchToLogin">
                                Sign in
                            </a>
                        </p>
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
        const passwordInput = $('#loginPassword');
        const icon = $(this).find('i');
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#toggleRegisterPassword').on('click', function() {
        const passwordInput = $('#registerPassword');
        const icon = $(this).find('i');
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#toggleRegisterRepeatPassword').on('click', function() {
        const passwordInput = $('#registerRepeatPassword');
        const icon = $(this).find('i');
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Customer type change
    $('#type').on('change', function() {
        if ($(this).val() === 'legal') {
            $('#legalFields').slideDown(300);
        } else {
            $('#legalFields').slideUp(300);
            $('#rekvizitField').hide();
        }
    });

    // Country change for Rekvizit
    $('#country').on('change', function() {
        if ($(this).val() === 'Azerbaijan') {
            $('#rekvizitField').slideDown(300);
        } else {
            $('#rekvizitField').slideUp(300);
        }
    });

    // File input change handler
    $('.file-input').on('change', function() {
        const input = $(this);
        const label = input.siblings('.file-upload-label');
        const fileName = input[0].files[0]?.name || 'Upload file...';
        const fileSize = input[0].files[0]?.size || 0;
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (fileSize > maxSize) {
            alert('File size exceeds 10MB limit. Please choose a smaller file.');
            input.val('');
            label.find('.file-name').text('Upload file...');
            label.removeClass('has-file');
            return;
        }

        label.find('.file-name').text(fileName);
        label.addClass('has-file');
    });

    // Switch between login and register tabs
    $('#switchToRegister').on('click', function(e) {
        e.preventDefault();
        $('#tab-register').tab('show');
    });

    $('#switchToLogin').on('click', function(e) {
        e.preventDefault();
        $('#tab-login').tab('show');
    });

    // Password confirmation validation
    $('#registerRepeatPassword').on('keyup', function() {
        const password = $('#registerPassword').val();
        const confirmPassword = $(this).val();
        
        if (password !== confirmPassword && confirmPassword !== '') {
            $(this).addClass('is-invalid');
            $(this).removeClass('is-valid');
        } else if (confirmPassword !== '') {
            $(this).addClass('is-valid');
            $(this).removeClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid is-valid');
        }
    });

    // Form validation
    $('#registerForm').on('submit', function(e) {
        const password = $('#registerPassword').val();
        const confirmPassword = $('#registerRepeatPassword').val();
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }

        if (!$('#registerCheck').is(':checked')) {
            e.preventDefault();
            alert('Please accept the terms and conditions to continue.');
            return false;
        }
    });
});
</script>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

.input-group-icon {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    z-index: 5;
    transition: all 0.3s ease;
}

.form-control:focus ~ .input-icon {
    color: #764ba2;
    transform: translateY(-50%) scale(1.2);
}

.password-toggle {
    background: transparent !important;
    border: none !important;
}

.password-toggle:hover {
    color: #764ba2 !important;
    transform: translateY(-50%) scale(1.1);
}

.file-upload-wrapper {
    position: relative;
}
.file-upload-wrapper input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 2;
}
.file-upload-label {
    display: block;
    padding: 30px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border: 3px dashed rgba(102, 126, 234, 0.5);
    border-radius: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.file-upload-label::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
    transform: rotate(45deg);
    transition: all 0.6s;
}

.file-upload-label:hover::before {
    animation: shine 1.5s infinite;
}

@keyframes shine {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
}

.file-upload-label:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
    border-color: #667eea;
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
}
.file-upload-label.has-file {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(40, 167, 69, 0.1) 100%);
    border-color: #28a745;
    border-style: solid;
}
.file-name {
    font-weight: 600;
    margin-top: 10px;
    font-size: 15px;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-check-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>
@endsection
