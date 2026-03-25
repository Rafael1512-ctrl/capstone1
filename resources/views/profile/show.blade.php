@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
    <style>
        .profile-header {
            background: linear-gradient(135deg, #9d50bb 0%, #6e48aa 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }

        .profile-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .profile-info {
            padding: 40px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .profile-field {
            margin-bottom: 25px;
        }

        .profile-field-label {
            font-size: 0.85rem;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .profile-field-value {
            font-size: 1.1rem;
            color: #333;
            font-weight: 500;
        }

        .profile-actions {
            padding: 20px 40px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-edit-profile {
            background: linear-gradient(to right, #9d50bb, #6e48aa);
            color: white !important;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit-profile:hover {
            opacity: 0.9;
            box-shadow: 0 6px 20px rgba(157, 80, 187, 0.3);
        }

        .btn-back {
            background: #f5f5f5;
            color: #333 !important;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background: #e9e9e9;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .member-badge {
            display: inline-block;
            background: #9d50bb;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 5px;
        }
    </style>

    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2" style="font-family: 'DM Serif Display', serif; font-size: 2.5rem;">My Profile</h1>
                    <p class="lead mb-0">Manage your LuxTix account information and preferences</p>
                </div>
                <div class="col-md-4 text-right">
                    <i class="fa fa-user" style="font-size: 80px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if ($message = Session::get('success'))
            <div class="alert-success">
                <strong>Success!</strong> {{ $message }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="profile-info">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <span class="profile-field-label">Full Name</span>
                                    <span class="profile-field-value">{{ $user->name }}</span>
                                </div>
                                <div class="profile-field">
                                    <span class="profile-field-label">Account Status</span>
                                    <div>
                                        <span class="member-badge">
                                            <i class="fa fa-check-circle mr-1"></i>Active Member
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-field">
                                    <span class="profile-field-label">Email Address</span>
                                    <span class="profile-field-value">{{ $user->email }}</span>
                                </div>
                                <div class="profile-field">
                                    <span class="profile-field-label">Member Since</span>
                                    <span class="profile-field-value">
                                        {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                            <i class="fa fa-edit mr-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('landing') }}" class="btn-back">
                            <i class="fa fa-arrow-left mr-2"></i>Back to Landing
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="profile-info" style="text-align: center; border: none;">
                        <div style="font-size: 3rem; color: #9d50bb; margin-bottom: 15px;">
                            <i class="fa fa-shield"></i>
                        </div>
                        <h4 style="color: #333; margin-bottom: 10px;">Account Security</h4>
                        <p style="color: #666; font-size: 0.95rem; margin-bottom: 15px;">
                            Your account is secured with encrypted password protection
                        </p>
                        <p style="color: #999; font-size: 0.85rem;">
                            Change your password regularly through the edit profile section
                        </p>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="profile-info" style="text-align: center; border: none;">
                        <div style="font-size: 3rem; color: #9d50bb; margin-bottom: 15px;">
                            <i class="fa fa-ticket"></i>
                        </div>
                        <h4 style="color: #333; margin-bottom: 10px;">Your Tickets</h4>
                        <p style="color: #666; font-size: 0.95rem;">
                            <a href="{{ route('tickets.index') }}"
                                style="color: #9d50bb; text-decoration: none; font-weight: 600;">
                                View Your Tickets <i class="fa fa-arrow-right ml-1"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
