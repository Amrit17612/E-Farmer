@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@push('styles')
<style>
    .profile-grid { display:grid; grid-template-columns:320px 1fr; gap:24px; }
    .profile-sidebar .avatar-section {
        text-align:center; padding:32px 24px 24px;
        border-bottom:1px solid #F0F0F0;
    }
    .avatar-wrap { position:relative; display:inline-block; margin-bottom:16px; }
    .avatar-wrap img, .avatar-wrap .avatar-big {
        width:96px; height:96px; border-radius:50%; object-fit:cover;
        border:3px solid #E8F5E9;
        background:#E8F5E9; display:flex; align-items:center; justify-content:center;
        font-size:40px; font-weight:700; color:#2E7D32;
    }
    .avatar-edit-btn {
        position:absolute; bottom:4px; right:4px;
        width:30px; height:30px; border-radius:50%;
        background:#2E7D32; color:#fff; font-size:14px;
        display:flex; align-items:center; justify-content:center;
        cursor:pointer; border:2px solid #fff;
    }
    .profile-name { font-size:18px; font-weight:700; color:#1A2E1A; }
    .profile-email { font-size:13px; color:#9E9E9E; margin-top:4px; }
    .profile-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:0; padding:20px; }
    .p-stat { text-align:center; padding:12px 8px; border-right:1px solid #F0F0F0; }
    .p-stat:last-child { border:none; }
    .p-stat .val { font-size:18px; font-weight:700; color:#2E7D32; }
    .p-stat .lbl { font-size:11px; color:#9E9E9E; margin-top:2px; }
    .profile-info-list { padding:16px 20px; }
    .info-row { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid #F5F5F5; font-size:13px; }
    .info-row:last-child { border:none; }
    .info-row i { color:#2E7D32; width:20px; }
    .info-row .info-val { color:#1A2E1A; font-weight:500; }
    .tab-nav { display:flex; gap:4px; border-bottom:2px solid #F0F0F0; margin-bottom:24px; }
    .tab-btn {
        padding:10px 20px; border:none; background:none;
        font-family:'Poppins',sans-serif; font-size:14px; font-weight:600;
        color:#757575; cursor:pointer; border-bottom:2px solid transparent;
        margin-bottom:-2px; transition:.2s;
    }
    .tab-btn.active { color:#2E7D32; border-bottom-color:#2E7D32; }
    .tab-content { display:none; }
    .tab-content.active { display:block; }
    @media(max-width:900px){ .profile-grid{grid-template-columns:1fr;} }
</style>
@endpush

@section('content')
<div class="profile-grid">
    {{-- Sidebar --}}
    <div class="card" style="height:fit-content;">
        <div class="profile-sidebar">
            <div class="avatar-section">
                <div class="avatar-wrap">
                    @if($user->avatar && !str_starts_with($user->avatar, 'http'))
                        <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}">
                    @else
                        <div class="avatar-big">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                    @endif
                    <label class="avatar-edit-btn" for="avatarInput" title="Change photo">
                        <i class="bi bi-camera"></i>
                    </label>
                </div>
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-email">{{ $user->email }}</div>
                @if($user->location)
                    <div style="font-size:12px;color:#9E9E9E;margin-top:6px;"><i class="bi bi-geo-alt"></i> {{ $user->location }}</div>
                @endif
            </div>

            <div class="profile-stats">
                @if($user->isBuyer())
                    <div class="p-stat">
                        <div class="val">{{ App\Models\Crop::active()->count() }}</div>
                        <div class="lbl">Available</div>
                    </div>
                    <div class="p-stat">
                        <div class="val">{{ $user->orders()->count() }}</div>
                        <div class="lbl">Orders</div>
                    </div>
                    <div class="p-stat">
                        <div class="val">{{ $user->location ? Str::limit($user->location, 8) : 'Any' }}</div>
                        <div class="lbl">Region</div>
                    </div>
                @elseif($user->isFarmer())
                    <div class="p-stat">
                        <div class="val">{{ $user->crops()->count() }}</div>
                        <div class="lbl">Crops</div>
                    </div>
                    <div class="p-stat">
                        <div class="val">{{ App\Models\Order::whereHas('crop', fn($q) => $q->where('user_id', $user->id))->count() }}</div>
                        <div class="lbl">Orders</div>
                    </div>
                    <div class="p-stat">
                        <div class="val">{{ $user->farm_size ?? '—' }}</div>
                        <div class="lbl">Acres</div>
                    </div>
                @else
                    <div class="p-stat">
                        <div class="val">{{ App\Models\Crop::count() }}</div>
                        <div class="lbl">Crops</div>
                    </div>
                    <div class="p-stat">
                        <div class="val">{{ App\Models\Order::count() }}</div>
                        <div class="lbl">Orders</div>
                    </div>
                    <div class="p-stat">
                        <div class="val">{{ App\Models\User::count() }}</div>
                        <div class="lbl">Users</div>
                    </div>
                @endif
            </div>

            <div class="profile-info-list">
                <div class="info-row">
                    <i class="bi bi-phone"></i>
                    <span class="info-val">{{ $user->phone ?? 'Not set' }}</span>
                </div>
                <div class="info-row">
                    <i class="bi bi-calendar3"></i>
                    <span class="info-val">Joined {{ $user->created_at->format('M Y') }}</span>
                </div>
                <div class="info-row">
                    <i class="bi bi-currency-rupee"></i>
                    @if($user->isBuyer())
                        <span class="info-val">₹{{ number_format($user->total_spent) }} spent</span>
                    @elseif($user->isAdmin())
                        <span class="info-val">₹{{ number_format($user->total_earnings) }} revenue</span>
                    @else
                        <span class="info-val">₹{{ number_format($user->total_earnings) }} earned</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div>
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab('profile', this)">Edit Profile</button>
            <button class="tab-btn" onclick="switchTab('password', this)">Change Password</button>
            <button class="tab-btn" onclick="switchTab('danger', this)" style="color:#c62828;">Danger Zone</button>
        </div>

        {{-- Tab: Edit Profile --}}
        <div class="tab-content active" id="tab-profile">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="bi bi-person-circle" style="color:#2E7D32;"></i> Personal Information</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;"
                               onchange="this.form.submit()">

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Location / Village</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $user->location) }}" placeholder="e.g. Ludhiana, Punjab">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Farm Size (acres)</label>
                                <input type="number" name="farm_size" class="form-control" step="0.1" min="0" value="{{ old('farm_size', $user->farm_size) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background:#F5F5F5;color:#9E9E9E;">
                            <p style="font-size:12px;color:#9E9E9E;margin-top:4px;">Email cannot be changed once registered.</p>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tab: Change Password --}}
        <div class="tab-content" id="tab-password">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="bi bi-lock-fill" style="color:#2E7D32;"></i> Change Password</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}" style="max-width:480px;">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-lock"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tab: Danger Zone --}}
        <div class="tab-content" id="tab-danger">
            <div class="card" style="border:1px solid #FFCDD2;">
                <div class="card-header" style="background:#FFEBEE;">
                    <span class="card-title" style="color:#c62828;"><i class="bi bi-exclamation-triangle-fill"></i> Danger Zone</span>
                </div>
                <div class="card-body">
                    <div style="background:#FFF5F5;border-radius:10px;padding:20px;margin-bottom:20px;">
                        <h3 style="color:#c62828;font-size:15px;font-weight:700;margin-bottom:8px;">Delete Account</h3>
                        <p style="font-size:13px;color:#4A4A4A;margin-bottom:16px;">Once you delete your account, all your crops, orders and data will be permanently deleted. This action cannot be undone.</p>
                        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you absolutely sure? This cannot be undone!')">
                            @csrf @method('DELETE')
                            <div class="form-group" style="max-width:300px;">
                                <label class="form-label">Enter your password to confirm</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash3"></i> Delete My Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}
</script>
@endpush
