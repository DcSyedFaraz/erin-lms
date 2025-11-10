@extends('admin.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0">Video Library</h1>
                <p class="text-muted">Curate videos, poems, premium short films, and clips per subscription tier.</p>
            </div>
            <a href="{{ route('admin.video-library.create') }}" class="btn btn-primary">+ Add Item</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Tier</th>
                            <th>Published</th>
                            <th>Updated</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->title }}</strong>
                                    @if ($item->is_featured)
                                        <span class="badge bg-warning text-dark ms-1">Featured</span>
                                    @endif
                                    <div class="small text-muted">
                                        {{ \Illuminate\Support\Str::limit($item->description, 80) }}</div>
                                </td>
                                <td>{{ \App\Models\VideoLibraryItem::CONTENT_TYPES[$item->content_type] ?? $item->content_type }}
                                </td>
                                <td>{{ ucfirst($item->access_tier) }}</td>
                                <td>
                                    @if ($item->is_published)
                                        <span class="badge bg-success">Live</span>
                                        <div class="small text-muted">{{ optional($item->published_at)->diffForHumans() }}
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $item->updated_at->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.video-library.edit', $item) }}"
                                        class="btn btn-outline-primary btn-sm">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="mb-1">No items yet.</p>
                                    <a class="btn btn-link" href="{{ route('admin.video-library.create') }}">Create your
                                        first upload</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
