@extends('layouts.app')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <span class="card-title">All Platform Users</span>
        <form method="GET" action="{{ route('admin.users') }}" style="display:flex; gap:10px;">
            <div class="search-bar" style="width:250px;">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}">
            </div>
            <select name="role" class="form-control" style="width:150px;">
                <option value="">All Roles</option>
                <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmers</option>
                <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyers</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="avatar-placeholder" style="width:36px; height:36px; font-size:14px;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div>
                                <div style="font-weight:600; font-size:14px;">{{ $user->name }}</div>
                                <div style="font-size:12px; color:#9E9E9E;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->isAdmin() ? 'badge-primary' : ($user->isBuyer() ? 'badge-info' : 'badge-success') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->location ?? '—' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Blocked</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf
                                <button type="submit" class="btn {{ $user->is_active ? 'btn-outline' : 'btn-primary' }} btn-sm" {{ $user->isAdmin() ? 'disabled' : '' }}>
                                    {{ $user->is_active ? 'Block' : 'Unblock' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" {{ $user->isAdmin() ? 'disabled' : '' }}>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
