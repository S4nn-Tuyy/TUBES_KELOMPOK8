@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Users</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->is_admin ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $user->is_banned ? 'bg-danger' : 'bg-success' }}">
                                        {{ $user->is_banned ? 'Banned' : 'Active' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if(!$user->is_admin)
                                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $user->is_banned ? 'btn-success' : 'btn-danger' }}"
                                                onclick="return confirm('Are you sure you want to {{ $user->is_banned ? 'unban' : 'ban' }} this user?')">
                                                {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">No actions</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 