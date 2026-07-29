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
                    <h2>12</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <h6>Indexed</h6>
                    <h2>10</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <h6>Processing</h6>
                    <h2>1</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-danger border-4">
                <div class="card-body">
                    <h6>Failed</h6>
                    <h2>1</h2>
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

                <tr>

                    <td>Laravel Documentation</td>

                    <td>Laravel</td>

                    <td>PDF</td>

                    <td>2.3 MB</td>

                    <td>

                        <span class="badge bg-success">

                            Indexed

                        </span>

                    </td>

                    <td>20 Jul 2026</td>

                    <td>

                        <button class="btn btn-sm btn-info">

                            View

                        </button>

                        <button class="btn btn-sm btn-warning">

                            Reindex

                        </button>

                        <button class="btn btn-sm btn-danger">

                            Delete

                        </button>

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection