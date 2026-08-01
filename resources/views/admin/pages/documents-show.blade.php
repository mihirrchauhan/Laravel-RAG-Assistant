@extends('admin.layouts.index')

@section('admin-title', 'Document Details')

@section('admin-content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Document Details</h3>
            <small class="text-body-secondary">Inspect the uploaded document metadata and current processing state.</small>
        </div>

        <a href="{{ route('admin.documents') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Documents
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-8">
                    <h4 class="mb-3">{{ $document->title }}</h4>
                    <p class="text-muted mb-4">{{ $document->description ?: 'No description provided.' }}</p>

                    <dl class="row">
                        <dt class="col-sm-4">Category</dt>
                        <dd class="col-sm-8">{{ $document->category ?: 'General' }}</dd>

                        <dt class="col-sm-4">Filename</dt>
                        <dd class="col-sm-8">{{ $document->original_filename }}</dd>

                        <dt class="col-sm-4">MIME Type</dt>
                        <dd class="col-sm-8">{{ $document->mime_type }}</dd>

                        <dt class="col-sm-4">Upload Status</dt>
                        <dd class="col-sm-8">{{ ucfirst($document->status) }}</dd>

                        <dt class="col-sm-4">Processing Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-success">{{ ucfirst($document->processing_status) }}</span>
                        </dd>

                        <dt class="col-sm-4">Uploaded By</dt>
                        <dd class="col-sm-8">{{ $document->uploader?->name ?? 'System' }}</dd>

                        <dt class="col-sm-4">Uploaded At</dt>
                        <dd class="col-sm-8">{{ $document->created_at->format('Y-m-d H:i:s') }}</dd>
                    </dl>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted">Quick Actions</h6>
                            <div class="d-grid gap-2 mt-3">
                                <form action="{{ route('admin.documents.reindex', $document) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100">Reindex Document</button>
                                </form>
                                <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Delete this document and its indexed vectors?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">Delete Document</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
