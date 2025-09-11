@php($moduleHasContent = $module->contents->count() > 0)
@if ($moduleHasContent)
    @foreach ($module->contents as $content)
        <div class="content-item">
            <div class="mb-3">
                <span class="content-type-badge">
                    <i
                        class="fas fa-{{ $content->type === 'pdf' ? 'file-pdf' : ($content->type === 'video' ? 'play' : ($content->type === 'image' ? 'image' : 'text')) }} mr-1"></i>
                    {{ strtoupper($content->type) }}
                </span>
            </div>

            @if ($content->type === 'pdf' && $content->path)
                <div class="pdf-container">
                    <object class="pdf-frame"
                        data="{{ asset('storage/' . $content->path) }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"
                        type="application/pdf">
                        <iframe class="pdf-frame"
                            src="{{ asset('storage/' . $content->path) }}#toolbar=0&navpanes=0&scrollbar=1&view=FitH"></iframe>
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                            </div>
                            <h6 class="text-muted mb-2">PDF Viewer Not Supported</h6>
                            <p class="text-muted small mb-3">Your browser doesn't support the PDF viewer.</p>
                            <a href="{{ asset('storage/' . $content->path) }}" target="_blank" rel="noopener"
                                class="btn btn-outline-primary">
                                <i class="fas fa-external-link-alt mr-1"></i>Open in New Tab
                            </a>
                        </div>
                    </object>
                </div>
            @elseif($content->type === 'video' && $content->path)
                <div class="video-container">
                    <video class="video-frame" controls controlsList="nodownload noplaybackrate" disablepictureinpicture
                        playsinline preload="metadata">
                        <source src="{{ asset('storage/' . $content->path) }}" type="video/mp4">
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-video fa-3x text-primary"></i>
                            </div>
                            <h6 class="text-muted mb-2">Video Not Supported</h6>
                            <p class="text-muted small">Your browser does not support the video format.</p>
                        </div>
                    </video>
                </div>
            @elseif($content->type === 'image' && $content->path)
                <div class="image-container text-center">
                    <img class="img-block" src="{{ asset('storage/' . $content->path) }}" alt="Learning content image"
                        draggable="false">
                </div>
            @elseif($content->type === 'text' && $content->text)
                <div class="text-block">
                    <div class="content-text">
                        {!! nl2br($content->text) !!}
                    </div>
                </div>
            @endif
        </div>
    @endforeach
@else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-folder-open fa-4x text-muted opacity-50"></i>
        </div>
        <h5 class="text-muted mb-2">No Content Available</h5>
        <p class="text-muted mb-0">This module doesn't have any learning materials yet.</p>
        <small class="text-muted">Check back later for updates!</small>
    </div>
@endif
