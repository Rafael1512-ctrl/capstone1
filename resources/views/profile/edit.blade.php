@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
    <style>
        .edit-hero {
            background: linear-gradient(135deg, #1a0a0a 0%, #2a0808 60%, #0d0d0d 100%);
            border-bottom: 1px solid rgba(220, 20, 60, 0.2);
            padding: 70px 0 50px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }
        .edit-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(220,20,60,0.08) 0%, transparent 65%);
            pointer-events: none;
        }
        .edit-card {
            background: #161616;
            border-radius: 18px;
            border: 1px solid rgba(220, 20, 60, 0.15);
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            padding: 40px;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-label {
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            margin-bottom: 10px;
            display: block;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        .form-control {
            width: 100%;
            padding: 13px 16px;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            font-size: 0.97rem;
            transition: all 0.3s ease;
            background: #0d0d0d;
            color: #fff;
            font-family: inherit;
        }
        .form-control::placeholder {
            color: rgba(255,255,255,0.25);
        }
        .form-control:focus {
            outline: none;
            border-color: #dc143c;
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.12);
            background: #111;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #ff6b6b;
            font-size: 0.82rem;
            margin-top: 5px;
            display: block;
        }
        .form-section-title {
            font-size: 1.05rem;
            color: #fff;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid rgba(220, 20, 60, 0.35);
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section-title i {
            color: #dc143c;
        }
        .form-section-title:first-child {
            margin-top: 0;
        }
        .form-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.06);
            margin: 30px 0;
        }
        .btn-submit {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: white !important;
            border: none;
            padding: 12px 36px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.92rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(220,20,60,0.3);
        }
        .btn-submit:hover {
            box-shadow: 0 6px 22px rgba(220,20,60,0.5);
            transform: translateY(-1px);
        }
        .btn-cancel {
            background: transparent;
            color: rgba(255,255,255,0.5) !important;
            border: 1.5px solid rgba(255,255,255,0.12);
            padding: 12px 36px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .btn-cancel:hover {
            border-color: rgba(220,20,60,0.35);
            color: #fff !important;
        }
        .alert-error-dark {
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.92rem;
        }
        .password-hint {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.3);
            margin-top: 7px;
            font-style: italic;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            padding-top: 28px;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-wrap: wrap;
        }
        .info-card {
            background: rgba(220, 20, 60, 0.05);
            border: 1px solid rgba(220, 20, 60, 0.2);
            border-radius: 14px;
            padding: 28px;
        }
        .info-card h4 {
            color: #fff;
            font-size: 0.97rem;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-card h4 i { color: #dc143c; }
        .info-card ul {
            color: rgba(255,255,255,0.45);
            line-height: 2;
            padding-left: 18px;
            font-size: 0.88rem;
            margin: 0;
        }
    </style>

    <div class="edit-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p style="color: #dc143c; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 10px;">
                        <i class="fa fa-edit mr-2"></i>Account Settings
                    </p>
                    <h1 style="font-family: 'DM Serif Display', serif; font-size: 2.8rem; color: #fff; margin-bottom: 10px;">Edit Profile</h1>
                    <p style="color: rgba(255,255,255,0.45); font-size: 1rem; margin: 0;">Update your personal information and account settings</p>
                </div>
                <div class="col-md-4 text-right d-none d-md-block">
                    <i class="fa fa-edit" style="font-size: 90px; color: rgba(220,20,60,0.12);"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        @if ($errors->any())
            <div class="alert-error-dark">
                <strong><i class="fa fa-exclamation-triangle mr-2"></i>Oops! Something went wrong:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="edit-card">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture Section -->
                        <h3 class="form-section-title"><i class="fa fa-camera"></i> Profile Picture</h3>
                        <div class="d-flex align-items-center mb-4 p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);">
                            <div class="mr-4">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" 
                                         style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #dc143c;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=dc143c&color=fff&size=128" alt="Avatar" 
                                         style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.1);">
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <label for="profile_photo" class="form-label" style="margin-bottom: 5px;">Upload New Photo</label>
                                <input type="file" id="profile_photo" name="profile_photo" 
                                       class="form-control @error('profile_photo') is-invalid @enderror" 
                                       accept="image/*" style="padding: 8px; font-size: 0.85rem; height: auto;">
                                <small class="text-white-50 mt-1 d-block" style="font-size: 0.75rem;">JPG, PNG up to 2MB</small>
                                @error('profile_photo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <h3 class="form-section-title"><i class="fa fa-user"></i> Personal Information</h3>

                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required placeholder="Your full name">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}" required placeholder="your@email.com">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" id="phone" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}" placeholder="+62 821...">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bio" class="form-label">Bio / About Me</label>
                            <textarea id="bio" name="bio" rows="3"
                                class="form-control @error('bio') is-invalid @enderror"
                                placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr class="form-divider">

                        <!-- Password Section -->
                        <h3 class="form-section-title"><i class="fa fa-lock"></i> Change Password</h3>
                        <p style="color: rgba(255,255,255,0.35); font-size: 0.88rem; margin-bottom: 20px;">
                            Leave empty if you don't want to change your password
                        </p>

                        <div class="form-group">
                            <label for="pass" class="form-label">New Password</label>
                            <input type="password" id="pass" name="pass"
                                class="form-control @error('pass') is-invalid @enderror"
                                placeholder="Leave empty to keep current">
                            @error('pass')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pass_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="pass_confirmation" name="pass_confirmation"
                                class="form-control" placeholder="Repeat your new password">
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('profile.show') }}" class="btn-cancel">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <div class="info-card">
                    <h4><i class="fa fa-info-circle"></i> Important Reminders</h4>
                    <ul>
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
