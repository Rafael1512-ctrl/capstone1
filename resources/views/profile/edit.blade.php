@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
    <style>
        .edit-header {
            background: linear-gradient(135deg, #9d50bb 0%, #6e48aa 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
        }

        .edit-form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: block;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Open Sans', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #9d50bb;
            box-shadow: 0 0 0 3px rgba(157, 80, 187, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .form-section-title {
            font-size: 1.2rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #9d50bb;
            margin-top: 30px;
        }

        .form-section-title:first-child {
            margin-top: 0;
        }

        .form-divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 30px 0;
        }

        .btn-submit {
            background: linear-gradient(to right, #9d50bb, #6e48aa);
            color: white !important;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .btn-submit:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(157, 80, 187, 0.3);
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #333 !important;
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background: #e9e9e9;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .password-hint {
            font-size: 0.85rem;
            color: #666;
            margin-top: 8px;
            font-style: italic;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
    </style>

    <div class="edit-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2" style="font-family: 'DM Serif Display', serif; font-size: 2.5rem;">Edit Profile</h1>
                    <p class="lead mb-0">Update your personal information and account settings</p>
                </div>
                <div class="col-md-4 text-right">
                    <i class="fa fa-edit" style="font-size: 80px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if ($errors->any())
            <div class="alert-danger">
                <strong>Oops! Something went wrong:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="edit-form-card">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information Section -->
                        <h3 class="form-section-title">Personal Information</h3>

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <p class="password-hint">We'll use this email for account notifications and password recovery
                            </p>
                        </div>

                        <hr class="form-divider">

                        <!-- Password Section -->
                        <h3 class="form-section-title">Change Password</h3>
                        <p style="color: #666; margin-bottom: 15px;">
                            Leave empty if you don't want to change your password
                        </p>

                        <div class="form-group">
                            <label for="pass" class="form-label">New Password</label>
                            <input type="password" id="pass" name="pass"
                                class="form-control @error('pass') is-invalid @enderror"
                                placeholder="Leave empty to keep current password">
                            @error('pass')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <p class="password-hint">Minimum 8 characters for security</p>
                        </div>

                        <div class="form-group">
                            <label for="pass_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="pass_confirmation" name="pass_confirmation" class="form-control"
                                placeholder="Repeat your password">
                            <p class="password-hint">Must match the password above</p>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fa fa-save mr-2"></i>Save Changes
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn-cancel">
                                <i class="fa fa-times mr-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Info Card -->
                <div class="edit-form-card" style="background: #f8f9fa; border-left: 4px solid #9d50bb;">
                    <h4 style="color: #333; margin-bottom: 15px;">
                        <i class="fa fa-info-circle mr-2" style="color: #9d50bb;"></i>Important Reminders
                    </h4>
                    <ul style="color: #666; line-height: 1.8; padding-left: 20px;">
                        <li>Keep your email address up to date for account security</li>
                        <li>Use a strong password with a mix of characters</li>
                        <li>Never share your password with anyone</li>
                        <li>Your changes will be saved immediately after submission</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
