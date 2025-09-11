<div class="modal fade" id="childrenProfilesModal" tabindex="-1" role="dialog" aria-labelledby="childrenProfilesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title" id="childrenProfilesModalLabel">
                    <i class="fas fa-users mr-2"></i>Who's Learning Today?
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <style>
                    .profiles-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                        gap: 24px;
                        margin-bottom: 2rem;
                    }

                    .profile-tile {
                        cursor: pointer;
                        text-align: center;
                        transition: all 0.3s ease;
                        padding: 1rem;
                        border-radius: 12px;
                    }

                    .profile-tile:hover {
                        transform: translateY(-4px);
                    }

                    .profile-avatar {
                        width: 100px;
                        height: 100px;
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 36px;
                        font-weight: 700;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: #fff;
                        margin: 0 auto 16px;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                    }

                    .profile-tile:hover .profile-avatar {
                        transform: scale(1.05);
                        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
                    }

                    .profile-name {
                        font-weight: 600;
                        font-size: 1.1rem;
                        margin-bottom: 0.5rem;
                    }

                    .profile-tile.active .profile-avatar {
                        outline: 3px solid #28a745;
                        outline-offset: 3px;
                        animation: pulse 2s infinite;
                    }

                    @keyframes pulse {
                        0% { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3), 0 0 0 0 rgba(40, 167, 69, 0.7); }
                        70% { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3), 0 0 0 10px rgba(40, 167, 69, 0); }
                        100% { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3), 0 0 0 0 rgba(40, 167, 69, 0); }
                    }

                    .add-tile .profile-avatar {
                        border: 3px dashed #6c757d;
                        background: transparent;
                        color: #6c757d;
                        box-shadow: none;
                    }

                    .add-tile:hover .profile-avatar {
                        border-color: #667eea;
                        color: #667eea;
                        background: rgba(102, 126, 234, 0.1);
                    }

                    .create-form {
                        display: none;
                        animation: slideDown 0.3s ease;
                    }

                    @keyframes slideDown {
                        from { opacity: 0; transform: translateY(-20px); }
                        to { opacity: 1; transform: translateY(0); }
                    }

                    /* Dark mode styles */
                    body.dark-mode .modal-content {
                        background-color: #2d3748;
                        color: #e2e8f0;
                    }

                    body.dark-mode .profile-name {
                        color: #e2e8f0;
                    }

                    body.dark-mode .create-form .card {
                        background-color: #4a5568;
                        border-color: #718096;
                    }

                    /* Light mode styles */
                    body.light-mode .modal-content {
                        background-color: #ffffff;
                        color: #2d3748;
                    }

                    body.light-mode .profile-name {
                        color: #2d3748;
                    }
                </style>

                @php($children = auth()->user()->childProfiles)
                @php($activeId = session('active_child_id'))
                @php($count = $children->count())

                <div class="profiles-grid" id="profilesGrid">
                    @foreach ($children as $child)
                        <div class="profile-tile {{ $activeId === $child->id ? 'active' : '' }}"
                            data-id="{{ $child->id }}" data-url="{{ route('parent.children.dashboard', $child) }}">
                            <div class="profile-avatar">
                                {{ strtoupper(mb_substr($child->name, 0, 1)) }}
                            </div>
                            <div class="profile-name">{{ $child->name }}</div>
                            @if($activeId === $child->id)
                                <small class="text-success font-weight-bold">
                                    <i class="fas fa-check-circle mr-1"></i>Currently Active
                                </small>
                            @else
                                <small class="text-muted">Click to enter</small>
                            @endif
                        </div>
                    @endforeach

                    @if ($count < 5)
                        <div class="profile-tile add-tile" id="addProfileTile">
                            <div class="profile-avatar">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="profile-name">Add New Profile</div>
                            <small class="text-muted">Create learning profile</small>
                        </div>
                    @endif
                </div>

                <div class="card create-form" id="createProfileForm">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-user-plus mr-2"></i>Create New Child Profile
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="newChildForm">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="childNameInput" class="form-label">Child's Name</label>
                                        <input type="text" id="childNameInput" class="form-control form-control-lg"
                                            placeholder="Enter your child's name" maxlength="50" required>
                                        <small class="form-text text-muted">This will be displayed on their learning profile</small>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            <i class="fas fa-plus mr-1"></i>Create Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-secondary" id="cancelCreate">
                                        <i class="fas fa-times mr-1"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="mt-3">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Profile Limit:</strong> You can create up to 5 child profiles.
                                Currently: {{ $count }}/5 profiles created.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const addTile = document.getElementById('addProfileTile');
        const createForm = document.getElementById('createProfileForm');
        const cancelBtn = document.getElementById('cancelCreate');
        const form = document.getElementById('newChildForm');
        const nameInput = document.getElementById('childNameInput');
        const grid = document.getElementById('profilesGrid');
        const limit = 5;
        const tiles = document.querySelectorAll('#profilesGrid .profile-tile');
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Click-to-navigate for existing tiles
        tiles.forEach((tile) => {
            if (tile.id === 'addProfileTile') return;
            tile.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                if (url) {
                    // Add loading state
                    this.style.opacity = '0.7';
                    this.style.pointerEvents = 'none';

                    // Show loading message
                    if (window.toastr) {
                        toastr.info('Entering learning space...');
                    }

                    setTimeout(() => {
                        window.location.href = url;
                    }, 500);
                }
            });
        });

        if (addTile) {
            addTile.addEventListener('click', function() {
                createForm.style.display = 'block';
                nameInput.focus();
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                createForm.style.display = 'none';
                nameInput.value = '';
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const name = nameInput.value.trim();
                if (!name) {
                    nameInput.focus();
                    return;
                }

                // Disable form during submission
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Creating...';
                submitBtn.disabled = true;

                fetch("{{ route('parent.children.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ name })
                    })
                    .then(async (res) => {
                        const data = await res.json();
                        if (!res.ok) throw data;
                        return data;
                    })
                    .then(({ data, count }) => {
                        // Create new tile
                        const tile = document.createElement('div');
                        tile.className = 'profile-tile';
                        tile.setAttribute('data-id', data.id);
                        tile.setAttribute('data-url',
                            "{{ route('parent.children.dashboard', ['child' => '__ID__']) }}"
                            .replace('__ID__', data.id));
                        tile.innerHTML = `
                            <div class="profile-avatar">${data.name.charAt(0).toUpperCase()}</div>
                            <div class="profile-name">${data.name}</div>
                            <small class="text-muted">Click to enter</small>
                        `;

                        // Add click handler
                        tile.addEventListener('click', function() {
                            const url = this.getAttribute('data-url');
                            if (url) {
                                this.style.opacity = '0.7';
                                this.style.pointerEvents = 'none';
                                if (window.toastr) toastr.info('Entering learning space...');
                                setTimeout(() => window.location.href = url, 500);
                            }
                        });

                        // Insert tile
                        if (document.getElementById('addProfileTile')) {
                            grid.insertBefore(tile, document.getElementById('addProfileTile'));
                        } else {
                            grid.appendChild(tile);
                        }

                        // Success feedback
                        if (typeof toastr !== 'undefined') {
                            toastr.success(`${data.name}'s profile created successfully!`);
                        }

                        // Reset form
                        nameInput.value = '';
                        createForm.style.display = 'none';

                        // Remove add tile if limit reached
                        if (count >= limit) {
                            const add = document.getElementById('addProfileTile');
                            if (add) add.remove();
                        }

                        // Add entrance animation
                        tile.style.opacity = '0';
                        tile.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            tile.style.transition = 'all 0.3s ease';
                            tile.style.opacity = '1';
                            tile.style.transform = 'scale(1)';
                        }, 100);
                    })
                    .catch((err) => {
                        let msg = 'Could not create profile.';
                        if (err && err.message) msg = err.message;
                        if (err && err.errors) {
                            const firstKey = Object.keys(err.errors)[0];
                            if (firstKey && err.errors[firstKey][0]) msg = err.errors[firstKey][0];
                        }
                        if (typeof toastr !== 'undefined') {
                            toastr.error(msg);
                        }
                    })
                    .finally(() => {
                        // Re-enable form
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });
        }
    })();
</script>
