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

        <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#uploadDocumentModal">
            <i class="fas fa-upload me-2"></i>
            Upload Document
        </button>
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

<!-- Upload Modal -->

<div class="modal fade" id="uploadDocumentModal">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form method="POST"
                  action="#"
                  enctype="multipart/form-data">

                @csrf

                <div class="modal-header">

                    <h5>Upload Knowledge Document</h5>

                    <button class="btn-close"
                            data-coreui-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Document Name

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="title"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Category

                            </label>

                            <select
                                class="form-select"
                                name="category">

                                <option>General</option>

                                <option>Laravel</option>

                                <option>PHP</option>

                                <option>Python</option>

                                <option>AI</option>

                            </select>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Description

                        </label>

                        <textarea
                            class="form-control"
                            rows="3"
                            name="description"></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Document File

                        </label>

                        <input
                            type="file"
                            class="form-control"
                            name="document"
                            accept=".pdf,.doc,.docx,.txt"
                            required>

                        <small class="text-body-secondary">

                            Supported :
                            PDF, DOC, DOCX, TXT (Max 20 MB)

                        </small>

                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <label class="form-label">

                                Chunk Size

                            </label>

                            <input
                                type="number"
                                class="form-control"
                                name="chunk_size"
                                value="500">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Chunk Overlap

                            </label>

                            <input
                                type="number"
                                class="form-control"
                                name="chunk_overlap"
                                value="50">

                        </div>

                    </div>

                    <div class="form-check mt-3">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            checked
                            name="generate_embeddings">

                        <label class="form-check-label">

                            Generate embeddings after upload

                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-coreui-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        class="btn btn-primary">

                        Upload & Index

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection