@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .profile-avatar-section {
        position: relative;
        display: inline-block;
    }

    .profile-avatar-lg {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: var(--shadow-lg);
    }

    .avatar-placeholder-lg {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-400) 0%, var(--primary-500) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid white;
        box-shadow: var(--shadow-lg);
    }

    .avatar-placeholder-lg i {
        font-size: 3rem;
        color: white;
    }

    .change-avatar-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-600);
        border: 2px solid white;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--transition-fast);
    }

    .change-avatar-btn:hover {
        background: var(--primary-700);
    }

    .form-label {
        font-weight: var(--font-weight-medium);
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        padding: 0.625rem 1rem;
        font-size: var(--font-size-sm);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-400);
        box-shadow: 0 0 0 3px var(--primary-100);
    }

    .info-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-card-title {
        font-size: var(--font-size-lg);
        font-weight: var(--font-weight-semibold);
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-title i {
        color: var(--primary-600);
    }

    .read-only-field {
        background-color: var(--bg-subtle);
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
    }

    .read-only-label {
        font-size: var(--font-size-xs);
        color: var(--text-tertiary);
        margin-bottom: 0.25rem;
    }

    .read-only-value {
        font-weight: var(--font-weight-medium);
        color: var(--text-primary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="profile-avatar-section">
                    @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="profile-avatar-lg">
                    @else
                    <div class="avatar-placeholder-lg">
                        <i class="fas fa-user"></i>
                    </div>
                    @endif
                    <label for="profile_image" class="change-avatar-btn" title="Change Profile Image">
                        <i class="fas fa-camera"></i>
                    </label>
                    <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data" class="d-none">
                        @csrf
                        @method('PUT')
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
            <div class="col">
                <h2 class="mb-1">{{ $user->name }}</h2>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                </p>
                <p class="mb-0 opacity-75">
                    <span class="badge bg-light text-dark mt-2">
                        <i class="fas fa-shield-alt me-1"></i>{{ $user->role->name }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="info-card">
                    <h5 class="info-card-title">
                        <i class="fas fa-user-cog"></i>
                        Personal Information
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="Enter phone number">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', $user->date_of_birth) }}">
                            @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Residential Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" rows="3"
                                placeholder="Enter your address">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="info-card">
                <h5 class="info-card-title">
                    <i class="fas fa-shield-alt"></i>
                    Account Security
                </h5>
                <p class="text-muted mb-3">
                    Keep your account secure by regularly updating your password.
                </p>
                <a href="{{ route('password.change') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-key me-2"></i>Change Password
                </a>
            </div>

            <div class="info-card">
                <h5 class="info-card-title">
                    <i class="fas fa-file-medical-alt"></i>
                    Medical Records
                </h5>
                <p class="text-muted mb-3">
                    Access your medical records, lab reports, and prescriptions.
                </p>
                <a href="{{ route('medical-records.index') }}" class="btn btn-outline-info w-100">
                    <i class="fas fa-folder-open me-2"></i>View Records
                </a>
            </div>

            <div class="info-card bg-primary-50 border-primary-200">
                <h5 class="info-card-title text-primary">
                    <i class="fas fa-question-circle"></i>
                    Need Help?
                </h5>
                <p class="text-muted mb-3">
                    Contact our support team for assistance with your account.
                </p>
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#helpSupportModal">
                    <i class="fas fa-headset me-2"></i>Get Support
                </button>
            </div>
        </div>
    </div>
</div>
@endsection