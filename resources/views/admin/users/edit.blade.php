@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit User</h4>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">User Form</div>
                    </div>
                    <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter Name" required value="{{ old('name', $user->name) }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter Email" required value="{{ old('email', $user->email) }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="role_id">Role</label>
                                        <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                            <option value="3" {{ (old('role_id', $user->role_id) == 3) ? 'selected' : '' }}>User (Customer)</option>
                                            <option value="2" {{ (old('role_id', $user->role_id) == 2) ? 'selected' : '' }}>Organizer</option>
                                            <option value="1" {{ (old('role_id', $user->role_id) == 1) ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('role_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password (Optional)</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Leave blank to keep current password">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
