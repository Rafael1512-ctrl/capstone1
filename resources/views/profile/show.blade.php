@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
    <style>
        .profile-hero {
            background: linear-gradient(135deg, #1a0a0a 0%, #2a0808 60%, #0d0d0d 100%);
            border-bottom: 1px solid rgba(220, 20, 60, 0.2);
            padding: 70px 0 50px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }
        .profile-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(220,20,60,0.08) 0%, transparent 65%);
            pointer-events: none;
        }
        .profile-card {
            background: #161616;
            border-radius: 18px;
            border: 1px solid rgba(220, 20, 60, 0.15);
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            overflow: hidden;
            margin-bottom: 24px;
            transition: border-color 0.3s ease;
        }
        .profile-card:hover {
            border-color: rgba(220, 20, 60, 0.3);
        }
        .profile-info {
            padding: 36px 40px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .profile-field {
            margin-bottom: 24px;
        }
        .profile-field-label {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 6px;
            display: block;
        }
        .profile-field-value {
            font-size: 1.05rem;
            color: #fff;
            font-weight: 500;
        }
        .profile-actions {
            padding: 20px 40px;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            background: rgba(255,255,255,0.02);
        }
        .btn-edit-profile {
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: white !important;
            border: none;
            padding: 11px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(220,20,60,0.3);
        }
        .btn-edit-profile:hover {
            box-shadow: 0 6px 22px rgba(220,20,60,0.5);
            transform: translateY(-1px);
        }
        .btn-back {
            background: transparent;
            color: rgba(255,255,255,0.55) !important;
            border: 1.5px solid rgba(255,255,255,0.12);
            padding: 11px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            border-color: rgba(220,20,60,0.4);
            color: #fff !important;
        }
        .member-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(220, 20, 60, 0.12);
            border: 1px solid rgba(220, 20, 60, 0.3);
            color: #ff6b6b;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .side-widget {
            text-align: center;
            padding: 36px 30px;
        }
        .side-widget-icon {
            font-size: 2.8rem;
            color: #dc143c;
            margin-bottom: 16px;
            opacity: 0.85;
        }
        .side-widget h4 {
            color: #fff;
            margin-bottom: 10px;
            font-size: 1.05rem;
        }
        .side-widget p, .side-widget small {
            color: rgba(255,255,255,0.45);
            font-size: 0.88rem;
            line-height: 1.7;
        }
        .alert-success-dark {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #4ade80;
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.93rem;
        }
    </style>

    <div class="profile-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-4 position-relative">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" 
                                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #dc143c; box-shadow: 0 0 20px rgba(220,20,60,0.3);">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=dc143c&color=fff&size=128" alt="Avatar" 
                                     style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid rgba(220,20,60,0.4); box-shadow: 0 0 15px rgba(220,20,60,0.2);">
                            @endif
                        </div>
                        <div>
                            <p style="color: #dc143c; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 5px;">
                                <i class="fa fa-shield mr-1"></i> Verified Account
                            </p>
                            <h1 style="font-family: 'DM Serif Display', serif; font-size: 2.8rem; color: #fff; margin-bottom: 5px; line-height: 1.1;">{{ $user->name }}</h1>
                            <p style="color: rgba(255,255,255,0.45); font-size: 1rem; margin: 0;">{{ $user->role == 'admin' ? 'System Administrator' : ($user->role == 'organizer' ? 'Event Organizer' : 'Music Enthusiast') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right d-none d-md-block">
                    <div class="member-badge" style="font-size: 0.9rem; padding: 8px 18px; border-radius: 50px;">
                        <i class="fa fa-star text-warning"></i> {{ strtoupper($user->role) }} STATUS
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        @if ($message = Session::get('success'))
            <div class="alert-success-dark">
                <i class="fa fa-check-circle mr-2"></i><strong>Success!</strong> {{ $message }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="profile-info">
                        <div class="row">
                            <div class="col-md-6 border-right" style="border-color: rgba(255,255,255,0.05) !important;">
                                <div class="profile-field">
                                    <span class="profile-field-label">Full Name</span>
                                    <span class="profile-field-value">{{ $user->name }}</span>
                                </div>
                                <div class="profile-field">
                                    <span class="profile-field-label">Email Address</span>
                                    <span class="profile-field-value">{{ $user->email }}</span>
                                </div>
                                <div class="profile-field">
                                    <span class="profile-field-label">Phone Number</span>
                                    <span class="profile-field-value">{{ $user->phone ?? 'Not provided' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 pl-md-4">
                                <div class="profile-field">
                                    <span class="profile-field-label">Account Status</span>
                                    <div>
                                        <span class="member-badge">
                                            <i class="fa fa-check-circle"></i> Active Member
                                        </span>
                                    </div>
                                </div>
                                <div class="profile-field">
                                    <span class="profile-field-label">Member Since</span>
                                    <span class="profile-field-value">
                                        {{ $user->member_since ? $user->member_since->format('d M Y') : 'N/A' }}
                                    </span>
                                </div>
                                <div class="profile-field">
                                    <span class="profile-field-label">Bio / About Me</span>
                                    <span class="profile-field-value text-white-50 small" style="display: block; line-height: 1.5;">
                                        {{ $user->bio ?? 'No bio provided yet.' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                            <i class="fa fa-edit"></i> Edit Profile
                        </a>
                        <a href="{{ route('home') }}" class="btn-back">
                            <i class="fa fa-arrow-left"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="side-widget">
                        <div class="side-widget-icon">
                            <i class="fa fa-shield"></i>
                        </div>
                        <h4>Account Security</h4>
                        <p>Your account is secured with encrypted password protection.</p>
                        <small>Change your password regularly via Edit Profile.</small>
                    </div>
                </div>

                @if (Auth::user()->role !== 'Organizer' && Auth::user()->role !== 'organizer')
                <div class="profile-card">
                    <div class="side-widget">
                        <div class="side-widget-icon">
                            <i class="fa fa-ticket"></i>
                        </div>
                        <h4>Your Tickets</h4>
                        <p>
                            <a href="{{ route('tickets.index') }}"
                                style="color: #dc143c; text-decoration: none; font-weight: 600;">
                                View Your Tickets <i class="fa fa-arrow-right ml-1"></i>
                            </a>
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
