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

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-start flex-column flex-md-row gap-3">
                <div>
                    <h5 class="mb-1">Upload Knowledge Document</h5>
                    <p class="text-muted mb-0">Add a new document to the knowledge base for retrieval-augmented generation.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="#" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Document Name</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter document title" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" placeholder="Enter category (e.g. General)" value="General">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="4" name="description" placeholder="Describe the document contents"></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Document File</label>
                        <input type="file" class="form-control" name="document" accept=".pdf,.doc,.docx,.txt" required>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark">PDF</span>
                            <span class="badge bg-light text-dark">DOC</span>
                            <span class="badge bg-light text-dark">DOCX</span>
                            <span class="badge bg-light text-dark">TXT</span>
                            <small class="text-muted">Max 20 MB</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.documents') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Upload & Index</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection