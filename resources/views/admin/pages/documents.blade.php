@extends('admin.layouts.index')

@section('admin-title', 'Knowledge Base')

@section('admin-content')

<div class="container-fluid">

    <!-- Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Knowledge Base</h3>
            <small class="text-body-secondary">
                Manage AI documents used for Retrieval-Augmented Generation (RAG).
            </small>
        </div>

        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
            <i class="fas fa-upload me-2"></i>
            Upload Document
        </a>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <h6>Total Documents</h6>
                    <h2>{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6>Indexed</h6>
                    <h2>{{ $stats['indexed'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <h6>Processing</h6>
                    <h2>{{ $stats['processing'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-danger border-4">
                <div class="card-body">
                    <h6>Failed</h6>
                    <h2>{{ $stats['failed'] }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Documents Table -->
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>Uploaded Documents</strong>

            <input
                type="text"
                class="form-control w-25"
                placeholder="Search document...">

        </div>

        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">

                <thead>

                <tr>

                    <th>Name</th>

                    <th>Category</th>

                    <th>Type</th>

                    <th>Size</th>

                    <th>Status</th>

                    <th>Uploaded</th>

                    <th width="180">Action</th>

                </tr>

                </thead>

                <tbody>
                @forelse($documents as $document)
                    <tr>
                        <td>{{ $document->title }}</td>
                        <td>{{ $document->category ?? 'General' }}</td>
                        <td>{{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }}</td>
                        <td>{{ number_format($document->size ?? 0, 0) }} KB</td>
                        <td>
                            @php
                                $badge = match($document->processing_status) {
                                    'embedded' => ['bg-success', 'Indexed'],
                                    'failed' => ['bg-danger', 'Failed'],
                                    'pending', 'queued', 'processing', 'parsed', 'chunked' => ['bg-warning', 'Processing'],
                                    default => ['bg-secondary', ucfirst($document->processing_status)],
                                };
                            @endphp
                            <span class="badge {{ $badge[0] }}">{{ $badge[1] }}</span>
                        </td>
                        <td>{{ $document->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.documents.show', $document) }}" class="btn btn-sm btn-info">View</a>
                            <form action="{{ route('admin.documents.reindex', $document) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Reindex</button>
                            </form>
                            <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this document and its indexed vectors?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No documents have been uploaded yet.</td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection