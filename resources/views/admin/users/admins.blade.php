@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Daftar Admin</h4>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('admin.users.create', 'admin') }}" class="btn btn-primary btn-round">Add Admin</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Admin List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td><span class="badge badge-danger">{{ ucfirst($user->role) }}</span></td>
                                             <td>
                                                 <div class="d-flex align-items-center gap-2">
                                                     <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn-action btn-action-edit" title="Edit">
                                                         <i class="fas fa-edit"></i>
                                                     </a>
                                                     <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" class="m-0">
                                                         @csrf
                                                         @method('DELETE')
                                                         <button type="submit" class="btn-action btn-action-delete" onclick="return confirm('Are you sure?')" title="Delete">
                                                             <i class="fas fa-trash-alt"></i>
                                                         </button>
                                                     </form>
                                                 </div>
                                             </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No admins found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection