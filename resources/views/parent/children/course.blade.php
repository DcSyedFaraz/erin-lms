@extends('child.layout.app')

@section('content')
    <div class="container-fluid">
        <!-- Course Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-body bg-gradient-primary text-white"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1"><i class="fas fa-book-open mr-2"></i>{{ $course->title }}</h2>
                                <p class="mb-0 opacity-75">Learning as {{ $child->name }}</p>
                            </div>
                            <div class="text-right">
                                @isset($courseStats)
                                    <div class="mb-2">
                                        <span class="badge badge-light text-dark mr-2" title="Your best across modules">
                                            <i class="fas fa-trophy text-warning mr-1"></i>
                                            Best {{ $courseStats['best_points'] }}/{{ $courseStats['max_points'] }} pts
                                        </span>
                                        <span class="badge badge-light text-dark mr-2" title="Attempts">
                                            <i class="fas fa-redo mr-1"></i>{{ $courseStats['attempts'] }} attempts
                                        </span>
                                        @if(!is_null($courseStats['avg_percent']))
                                            <span class="badge badge-light text-dark" title="Average score">
                                                <i class="fas fa-chart-line mr-1"></i>{{ $courseStats['avg_percent'] }}%
                                            </span>
                                        @endif
                                    </div>
                                @endisset
                                <a href="{{ route('parent.children.dashboard', $child) }}" class="btn btn-light">
                                    <i class="fas fa-arrow-left mr-1"></i> Back to Courses
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Modules Sidebar -->
            <div class="col-lg-4">
                <div class="card card-outline card-primary h-100">
                    @if ($course->thumbnail)
                        <img src="{{ asset("storage/$course->thumbnail") }}" class="card-img-top"
                            style="max-height: 200px; object-fit: cover;" alt="{{ $course->title }}">
                    @endif
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list mr-2 text-primary"></i>Course Modules
                        </h5>
                        <small class="text-muted">Select a module to view content</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 500px; overflow-y: auto;">
                            @forelse($course->modules as $module)
                                @php($isLocked = ($lockedModules[$module->id] ?? false))
                                <a href="#" class="list-group-item list-group-item-action module-item border-0 {{ $isLocked ? 'disabled text-muted' : '' }}"
                                    data-module-id="{{ $module->id }}" title="{{ $isLocked ? 'Locked until previous quiz is submitted' : 'Open '.$module->title }}" {{ $isLocked ? 'data-locked=1' : '' }}>
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge badge-primary mr-2">#{{ $module->order }}</span>
                                                <h6 class="mb-0 font-weight-bold">
                                                    @if($isLocked)
                                                        <i class="fas fa-lock text-secondary mr-1" title="Locked"></i>
                                                    @endif
                                                    {{ $module->title }}
                                                </h6>
                                            </div>
                                            <p class="mb-1 small text-muted">
                                                {{ \Illuminate\Support\Str::limit($module->description, 90) }}
                                            </p>
                                            <div class="d-flex align-items-center flex-wrap">
                                                <small class="text-muted mr-3">
                                                    <i class="fas fa-file-alt mr-1"></i>{{ $module->contents->count() }}
                                                    Content{{ $module->contents->count() !== 1 ? 's' : '' }}
                                                </small>
                                                @isset($quizStats)
                                                    @php($s = $quizStats[$module->id] ?? null)
                                                    @if($s)
                                                        <small class="mr-2">
                                                            <span class="badge badge-primary" title="Best score">
                                                                <i class="fas fa-star mr-1"></i>{{ $s['best_points'] }}/{{ $s['max_points'] }}
                                                            </span>
                                                        </small>
                                                        <small>
                                                            <span class="badge badge-secondary" title="Attempts">
                                                                <i class="fas fa-redo mr-1"></i>{{ $s['attempts'] }}
                                                            </span>
                                                        </small>
                                                    @endif
                                                @endisset
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right text-muted ml-2"></i>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i>
                                    <p class="mb-0">No modules available yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Viewer -->
            <div class="col-lg-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-play-circle mr-2 text-primary"></i>
                                Module: <span id="currentModuleTitle" class="text-primary">Select a module</span>
                            </h5>
                            <small class="text-muted">
                                <i class="fas fa-shield-alt mr-1"></i>Content protected
                            </small>
                        </div>
                    </div>
                    <div class="card-body position-relative">
                        <style>
                            .content-viewer * {
                                -webkit-user-select: none;
                                user-select: none;
                            }

                            .content-viewer {
                                position: relative;
                                border-radius: 8px;
                            }

                            .no-context {
                                -webkit-touch-callout: none;
                            }

                            .pdf-frame {
                                width: 100%;
                                height: 520px;
                                border: none;
                                border-radius: 8px;
                            }

                            .video-frame {
                                width: 100%;
                                max-height: 520px;
                                background: #000;
                                border-radius: 8px;
                            }

                            .text-block {
                                padding: 20px;
                                border-radius: 8px;
                                line-height: 1.6;
                                font-size: 16px;
                            }

                            /* Dark mode text block */
                            body.dark-mode .text-block {
                                background: #2d3748;
                                color: #e2e8f0;
                                border: 1px solid #4a5568;
                            }

                            /* Light mode text block */
                            body.light-mode .text-block {
                                background: #f7fafc;
                                color: #2d3748;
                                border: 1px solid #e2e8f0;
                            }

                            .img-block {
                                max-width: 100%;
                                border-radius: 8px;
                                box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
                            }

                            .content-item {
                                margin-bottom: 24px;
                            }

                            .content-type-badge {
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                color: white;
                                border-radius: 20px;
                                padding: 4px 12px;
                                font-size: 12px;
                                font-weight: 600;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            }

                            .loading-overlay {
                                background: rgba(0, 0, 0, .7);
                                backdrop-filter: blur(4px);
                                border-radius: 8px;
                            }
                        </style>

                        <!-- Loading Overlay -->
                        <div id="loadingOverlay"
                            class="d-none position-absolute w-100 h-100 align-items-center justify-content-center loading-overlay"
                            style="top: 0; left: 0; z-index: 10;">
                            <div class="text-center text-white">
                                <div class="spinner-border text-light mb-3" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="h5">Loading module content...</div>
                                <small class="opacity-75">Please wait while we prepare your learning materials</small>
                            </div>
                        </div>

                        <!-- Content Viewer -->
                        <div class="content-viewer no-context" id="contentViewer">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-mouse-pointer fa-3x text-muted opacity-50"></i>
                                </div>
                                <h5 class="text-muted mb-2">Select a Module</h5>
                                <p class="text-muted">Choose a module from the sidebar to start learning</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const viewer = document.getElementById('contentViewer');
            if (!viewer) return;

            // Enhanced security measures
            viewer.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
            document.addEventListener('contextmenu', function(e) {
                if (viewer.contains(e.target)) e.preventDefault();
            }, true);

            document.addEventListener('keydown', function(e) {
                const isCtrl = e.ctrlKey || e.metaKey;
                if ((isCtrl && ['s', 'p', 'c', 'x', 'u', 'a'].includes(e.key.toLowerCase())) ||
                    e.key === 'PrintScreen' || e.key === 'F12') {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, true);

            document.addEventListener('selectstart', function(e) {
                if (viewer.contains(e.target)) e.preventDefault();
            }, true);

            document.addEventListener('dragstart', function(e) {
                if (viewer.contains(e.target)) e.preventDefault();
            }, true);

            // Module loading functionality
            const items = document.querySelectorAll('.module-item');
            const titleEl = document.getElementById('currentModuleTitle');
            const contentViewer = document.getElementById('contentViewer');
            const overlay = document.getElementById('loadingOverlay');
            const moduleUrlTpl =
                "{{ route('parent.children.course.module', ['child' => $child->id, 'course' => $course->id, 'module' => '__MID__']) }}";

            function setLoading(isLoading) {
                if (!overlay) return;
                if (isLoading) {
                    overlay.classList.remove('d-none');
                    overlay.classList.add('d-flex');
                } else {
                    overlay.classList.add('d-none');
                    overlay.classList.remove('d-flex');
                }
            }

            function activateItem(moduleId) {
                items.forEach((el) => {
                    el.classList.toggle('active', el.getAttribute('data-module-id') == moduleId);
                });
            }

            async function loadModule(moduleId, title) {
                if (titleEl) titleEl.textContent = title || 'Select a module';
                activateItem(moduleId);
                setLoading(true);

                const failsafeTimeout = setTimeout(() => {
                    console.warn('Failsafe timeout - hiding loading overlay');
                    setLoading(false);
                }, 15000);

                try {
                    const url = moduleUrlTpl.replace('__MID__', moduleId);
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'text/html'
                        }
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                    }

                    const html = await res.text();
                    contentViewer.innerHTML = html;

                    // Wait for all media content to load

                    // Update URL
                    const u = new URL(window.location.href);
                    u.searchParams.set('module', moduleId);
                    window.history.replaceState({}, '', u.toString());

                    // Show success message
                    if (window.toastr) {
                        toastr.success('Module loaded successfully!');
                    }

                } catch (e) {
                    console.error('Error loading module:', e);
                    contentViewer.innerHTML = `
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                            </div>
                            <h5 class="text-muted mb-2">Could not load module</h5>
                            <p class="text-muted mb-3">${e.message}</p>
                            <button class="btn btn-outline-primary" onclick="location.reload()">
                                <i class="fas fa-redo mr-1"></i>Try Again
                            </button>
                        </div>
                    `;
                    if (window.toastr) {
                        toastr.error('Could not load module: ' + e.message);
                    }
                } finally {
                    clearTimeout(failsafeTimeout);
                    setLoading(false);
                }
            }

            // ------- Quiz Helpers -------
            function getCsrf(){
                const m = document.querySelector('meta[name="csrf-token"]');
                return m ? m.getAttribute('content') : '';
            }

            function pad(n){ return n<10 ? '0'+n : ''+n; }

            function initQuizUI(){
                const wrapper = document.getElementById('quizWrapper');
                if (!wrapper) return;
                const attemptId = wrapper.getAttribute('data-attempt-id');
                const answerUrl = wrapper.getAttribute('data-answer-url');
                const completeUrl = wrapper.getAttribute('data-complete-url');
                const endsAtIso = wrapper.getAttribute('data-ends-at');

                // Timer
                if (endsAtIso){
                    const el = document.getElementById('quizTimer');
                    const end = new Date(endsAtIso).getTime();
                    const tick = () => {
                        const now = Date.now();
                        let diff = Math.floor((end - now) / 1000);
                        if (diff <= 0) {
                            el.textContent = '00:00';
                            clearInterval(timer);
                            // finalize
                            fetch(completeUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' }, body: new URLSearchParams({ attempt_id: attemptId }) })
                                .then(r=>r.json())
                                .then(data=>{ if (data && data.html){ document.getElementById('quizQuestionContainer').innerHTML = data.html; } });
                            return;
                        }
                        const m = Math.floor(diff/60); const s = diff%60;
                        el.textContent = pad(m)+':'+pad(s);
                    };
                    tick();
                    const timer = setInterval(tick, 1000);
                }

                // Bind answer form submit
                const form = document.getElementById('quizAnswerForm');
                if (form){
                    const submitBtn = document.getElementById('submitAnswerBtn');
                    const inputs = form.querySelectorAll('input[name="selected_answer"]');
                    const labels = form.querySelectorAll('label.quiz-option');
                    if (submitBtn) submitBtn.disabled = true;
                    inputs.forEach(i=> i.addEventListener('change', (ev)=>{
                        if (submitBtn) submitBtn.disabled = false;
                        // Fallback: toggle active class to reflect selection
                        labels.forEach(l => l.classList.remove('active'));
                        const lbl = ev.target.closest('label.quiz-option');
                        if (lbl) lbl.classList.add('active');
                    }));

                    form.addEventListener('submit', async function(e){
                        e.preventDefault();
                        if (submitBtn){ submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Submitting...'; }
                        const fd = new FormData(form);
                        fd.append('attempt_id', attemptId);
                        try{
                            const res = await fetch(answerUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept':'application/json' }, body: fd });
                            const data = await res.json();
                            if ((data.status === 'ok' || data.status === 'completed' || data.status === 'expired') && data.html){
                                document.getElementById('quizQuestionContainer').innerHTML = data.html;
                                if (data.progress){
                                    const pc = document.getElementById('quizProgressCurrent');
                                    if (pc) pc.textContent = data.progress.current;
                                    const pn = document.getElementById('pointsNow');
                                    if (pn) pn.textContent = data.progress.points;
                                    const pm = document.getElementById('pointsMax');
                                    if (pm && data.progress.max) pm.textContent = data.progress.max;
                                    const timer = document.getElementById('quizTimer');
                                    if (timer && (data.status === 'completed' || data.status === 'expired')) timer.textContent = '00:00';
                                }
                                initQuizUI(); // bind new DOM
                                if (window.toastr) toastr.success('Answer submitted');
                            } else {
                                if (window.toastr) toastr.error('Could not submit answer');
                            }
                        } catch (err){
                            console.error(err);
                            if (window.toastr) toastr.error('Error submitting answer');
                        } finally {
                            if (submitBtn){ submitBtn.disabled = false; submitBtn.textContent = 'Submit & Next'; }
                        }
                    });
                }
            }

            // Event delegation for quiz buttons
            document.addEventListener('click', async function(e){
                const target = e.target.closest('#startQuizBtn');
                if (target && viewer.contains(target)){
                    e.preventDefault();
                    const url = target.getAttribute('data-start-url');
                    try{
                        const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept':'text/html' } });
                        const html = await res.text();
                        viewer.innerHTML = html;
                        initQuizUI();
                    } catch (err){
                        console.error(err);
                        if (window.toastr) toastr.error('Could not start quiz');
                    }
                    return;
                }

                const back = e.target.closest('#backToModuleBtn, #summaryBackBtn');
                if (back && viewer.contains(back)){
                    e.preventDefault();
                    const url = back.getAttribute('data-module-url');
                    try{
                        const res = await fetch(url, { headers: { 'Accept': 'text/html' } });
                        const html = await res.text();
                        viewer.innerHTML = html;
                    } catch (err){ console.error(err); }
                    return;
                }
            });

            // Event listeners for module items
            items.forEach((el) => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (this.hasAttribute('data-locked')){
                        if (window.toastr) toastr.info('This module is locked until the previous module\'s quiz is submitted.');
                        return;
                    }
                    const id = this.getAttribute('data-module-id');
                    const t = this.querySelector('h6')?.textContent?.trim() || 'Module';
                    if (id) loadModule(id, t);
                });
            });

            // Auto-load initial module
            const params = new URLSearchParams(window.location.search);
            let initial = params.get('module');
            if (!initial && items.length){
                const firstUnlocked = Array.from(items).find(x => !x.hasAttribute('data-locked'));
                if (firstUnlocked) initial = firstUnlocked.getAttribute('data-module-id');
            }
            if (initial) {
                const initialEl = Array.from(items).find(x => x.getAttribute('data-module-id') == initial);
                const t = initialEl ? (initialEl.querySelector('h6')?.textContent?.trim() || 'Module') : 'Module';
                loadModule(initial, t);
            }
        })();
    </script>
@endsection
