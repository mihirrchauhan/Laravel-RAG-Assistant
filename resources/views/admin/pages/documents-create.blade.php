@extends('admin.layouts.index')

@section('admin-title', 'Add Document')

@section('admin-content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Add Knowledge Document</h3>
            <small class="text-body-secondary">
                Upload a new document to be indexed for RAG.
            </small>
        </div>

        <a href="{{ route('admin.documents') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Documents
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="#" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Document Name</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category">
                            <option>General</option>
                            <option>Laravel</option>
                            <option>PHP</option>
                            <option>Python</option>
                            <option>AI</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="3" name="description"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Document File</label>
                    <input type="file" class="form-control" name="document" accept=".pdf,.doc,.docx,.txt" required>
                    <small class="text-body-secondary">
                        Supported: PDF, DOC, DOCX, TXT (Max 20 MB)
                    </small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chunk Size</label>
                        <input type="number" class="form-control" name="chunk_size" value="500">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Chunk Overlap</label>
                        <input type="number" class="form-control" name="chunk_overlap" value="50">
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" checked name="generate_embeddings">
                    <label class="form-check-label">Generate embeddings after upload</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Upload & Index</button>
                    <a href="{{ route('admin.documents') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
