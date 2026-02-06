@extends('layouts.app')

@section('title', 'Change Password')

@push('styles')
<style>
    .password-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 2rem;
        max-width: 500px;
        margin: 0 auto;
    }

    .password-card-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .password-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-400) 0%, var(--primary-500) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: var(--shadow-lg);
    }

    .password-icon i {
        font-size: 2rem;
        color: white;
    }

    .password-card-header h4 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .password-card-header p {
        color: var(--text-secondary);
        font-size: var(--font-size-sm);
        margin: 0;
    }

    .form-label {
        font-weight: var(--font-weight-medium);
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        padding: 0.75rem 1rem;
        font-size: var(--font-size-sm);
    }

    .form-control:focus {
        border-color: var(--primary-400);
        box-shadow: 0 0 0 3px var(--primary-100);
    }

    .input-group-text {
        border-radius: var(--radius-md);
        background: var(--bg-subtle);
        border: 1px solid var(--border-color);
    }

    .password-strength {
        height: 4px;
        border-radius: 2px;
        margin-top: 0.5rem;
        background: var(--bg-subtle);
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    .password-requirements {
        background: var(--bg-subtle);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-top: 1rem;
    }

    .password-requirements h6 {
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-semibold);
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 1.25rem;
        font-size: var(--font-size-xs);
        color: var(--text-secondary);
    }

    .password-requirements li {
        margin-bottom: 0.25rem;
    }

    .password-requirements li.valid {
        color: var(--success-600);
    }

    .password-requirements li.valid::before {
        content: "✓ ";
    }

    .password-requirements li.invalid::before {
        content: "○ ";
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="password-card">
                <div class="password-card-header">
                    <div class="password-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Change Password</h4>
                    <p>Ensure your account is using a strong password for security</p>
                </div>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password" name="current_password" required
                                placeholder="Enter your current password">
                            <span class="input-group-text toggle-password" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password *</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                id="new_password" name="new_password" required
                                placeholder="Enter new password">
                            <span class="input-group-text toggle-password" data-target="new_password">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                        <div class="input-group">
                            <input type="password" class="form-control"
                                id="new_password_confirmation" name="new_password_confirmation" required
                                placeholder="Confirm new password">
                            <span class="input-group-text toggle-password" data-target="new_password_confirmation">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="password-requirements">
                        <h6><i class="fas fa-info-circle me-1"></i>Password Requirements</h6>
                        <ul class="list-unstyled">
                            <li id="req-length">At least 8 characters</li>
                            <li id="req-upper">At least one uppercase letter</li>
                            <li id="req-lower">At least one lowercase letter</li>
                            <li id="req-number">At least one number</li>
                            <li id="req-special">At least one special character</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary flex-fill">
                            <i class="fas fa-arrow-left me-2"></i>Back to Profile
                        </a>
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-save me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Password strength checker
        const newPassword = document.getElementById('new_password');
        const strengthBar = document.getElementById('strengthBar');

        newPassword.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Check requirements
            const reqs = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            // Update requirement indicators
            document.getElementById('req-length').className = reqs.length ? 'valid' : 'invalid';
            document.getElementById('req-upper').className = reqs.upper ? 'valid' : 'invalid';
            document.getElementById('req-lower').className = reqs.lower ? 'valid' : 'invalid';
            document.getElementById('req-number').className = reqs.number ? 'valid' : 'invalid';
            document.getElementById('req-special').className = reqs.special ? 'valid' : 'invalid';

            // Calculate strength
            if (password.length > 0) strength += 20;
            if (reqs.length) strength += 20;
            if (reqs.upper) strength += 15;
            if (reqs.lower) strength += 15;
            if (reqs.number) strength += 15;
            if (reqs.special) strength += 15;

            // Update strength bar
            strengthBar.style.width = Math.min(strength, 100) + '%';

            if (strength < 40) {
                strengthBar.style.backgroundColor = 'var(--danger-500)';
            } else if (strength < 70) {
                strengthBar.style.backgroundColor = 'var(--warning-500)';
            } else if (strength < 100) {
                strengthBar.style.backgroundColor = 'var(--info-500)';
            } else {
                strengthBar.style.backgroundColor = 'var(--success-500)';
            }
        });
    });
</script>
@endpush
@endsection