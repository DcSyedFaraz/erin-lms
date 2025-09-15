@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-body bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1"><i class="fas fa-users mr-2"></i>Child Profiles Management</h2>
                            <p class="mb-0 opacity-75">Manage and create learning profiles for your children</p>
                        </div>
                        <div>
                            <button class="btn btn-light" data-toggle="modal" data-target="#childrenProfilesModal">
                                <i class="fas fa-plus mr-1"></i> Manage Profiles
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Children Profiles -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-child mr-2 text-primary"></i>Active Profiles
                        </h3>
                        <span class="badge badge-primary badge-lg">{{ $children->count() }} Profile{{ $children->count() !== 1 ? 's' : '' }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($children->count() > 0)
                        <div class="row">
                            @foreach($children as $child)
                                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card h-100 border-0 shadow-sm" style="transition: all 0.3s ease;">
                                        <div class="card-body text-center p-4">
                                            <div class="profile-avatar mx-auto mb-3 d-flex align-items-center justify-content-center"
                                                 style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 28px; font-weight: 700; color: white;">
                                                {{ strtoupper(mb_substr($child->name, 0, 1)) }}
                                            </div>
                                            <h5 class="card-title font-weight-bold mb-2">{{ $child->name }}</h5>
                                            <p class="text-muted small mb-3">Learning Profile</p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('parent.children.dashboard', $child) }}"
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-sign-in-alt mr-1"></i>Enter
                                                </a>
                                                <button class="btn btn-outline-secondary btn-sm"
                                                        onclick="editChild({{ $child->id }}, '{{ $child->name }}', this)"
                                                        title="Edit Profile">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm"
                                                        onclick="deleteChild({{ $child->id }}, '{{ $child->name }}', this)"
                                                        title="Delete Profile">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-user-plus fa-4x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-2">No Child Profiles Yet</h5>
                            <p class="text-muted mb-4">Create your first child profile to get started with personalized learning experiences.</p>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#childrenProfilesModal">
                                <i class="fas fa-plus mr-1"></i>Create First Profile
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($children->count() > 0)
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Profiles</span>
                        <span class="info-box-number">{{ $children->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Learning Ready</span>
                        <span class="info-box-number">{{ $children->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-gradient-warning">
                    <span class="info-box-icon"><i class="fas fa-plus"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Available Slots</span>
                        <span class="info-box-number">{{ 5 - $children->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,.15) !important;
}

.profile-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.gap-2 > * + * {
    margin-left: 0.5rem;
}
</style>

<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function editChild(id, currentName, btn) {
    const newName = window.prompt('Enter new name for this profile:', currentName || '');
    if (!newName || newName.trim().length === 0) return;
    const trimmed = newName.trim().slice(0, 50);

    fetch(`{{ route('parent.children.update', ['child' => '__ID__']) }}`.replace('__ID__', id), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name: trimmed })
    })
    .then(async (res) => {
        const data = await res.json();
        if (!res.ok) throw data;
        return data;
    })
    .then(({ data }) => {
        // Update text in the corresponding card if present
        const card = btn ? btn.closest('.card') : null;
        if (card) {
            const nameEl = card.querySelector('.card-title');
            const avatarEl = card.querySelector('.profile-avatar');
            if (nameEl) nameEl.textContent = data.name;
            if (avatarEl) avatarEl.textContent = (data.name || '?').charAt(0).toUpperCase();
        }
        if (window.toastr) toastr.success('Profile name updated.');
    })
    .catch((err) => {
        let msg = 'Could not update profile.';
        if (err && err.message) msg = err.message;
        if (err && err.errors) {
            const firstKey = Object.keys(err.errors)[0];
            if (firstKey && err.errors[firstKey][0]) msg = err.errors[firstKey][0];
        }
        if (window.toastr) toastr.error(msg);
    });
}

function deleteChild(id, name, btn) {
    if (!window.confirm(`Delete profile "${name}"? This cannot be undone.`)) return;
    fetch(`{{ route('parent.children.destroy', ['child' => '__ID__']) }}`.replace('__ID__', id), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(async (res) => {
        const data = await res.json();
        if (!res.ok) throw data;
        return data;
    })
    .then(() => {
        // Remove card from DOM
        const card = btn.closest('.col-sm-6, .col-md-4, .col-lg-3');
        if (card) card.remove();
        if (window.toastr) toastr.success('Profile deleted.');
    })
    .catch((err) => {
        let msg = 'Could not delete profile.';
        if (err && err.message) msg = err.message;
        if (window.toastr) toastr.error(msg);
    });
}
</script>

@endsection
