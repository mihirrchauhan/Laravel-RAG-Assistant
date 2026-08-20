# Laravel RAG Assistant

This project is a Laravel-based Retrieval-Augmented Generation (RAG) application that lets users chat with a knowledge base built from uploaded documents.

## What is included

- Livewire chat interface at the home route for asking questions over indexed document content
- Admin knowledge-base panel for uploading, listing, viewing, reindexing, and deleting documents
- Background document processing pipeline using Laravel queues
- MySQL for metadata storage
- Ollama for embedding generation
- Qdrant for vector storage and similarity search
- Groq for final answer generation

## Architecture

- MySQL stores document metadata and file references
- Qdrant stores vector embeddings and payload metadata for retrieval
- Ollama generates embeddings with the `nomic-embed-text` model
- Groq generates the final answer based on retrieved context
- Laravel queues process document indexing asynchronously in the `documents` queue

## Supported document types

The app validates uploads for:

- PDF
- DOC
- DOCX
- TXT

## Prerequisites

- PHP 8.1+
- Composer
- MySQL
- Docker
- Ollama
- Qdrant
- Groq API key

## 1. Install PHP dependencies

```bash
composer install
```

## 2. Configure environment variables

Copy the example environment file and update the values for your local setup:

```bash
cp .env.example .env
```

Add or update the following values in `.env`:

```env
APP_NAME=Laravel-RAG-Assistant
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database

GROQ_API_KEY=your_groq_key
OLLAMA_EMBEDDING_URL=http://localhost:11434/api/embeddings
OLLAMA_EMBEDDING_MODEL=nomic-embed-text
QDRANT_URL=http://localhost:6333
QDRANT_VECTOR_SIZE=768
```

> If you use a different queue driver, make sure the worker setup matches it. The app dispatches jobs to the `documents` queue, so a persistent queue backend such as `database` or `redis` is recommended for production-like local testing.

## 3. Start dependencies

### Start Ollama and Qdrant

The project includes a Docker Compose setup for the vector and embedding services:

```bash
docker compose up -d
```

This starts:

- Qdrant on `http://localhost:6333`
- Ollama on `http://localhost:11434`
- The embedding model `nomic-embed-text` is pulled automatically by the compose setup

If you prefer to start Ollama manually:

```bash
ollama pull nomic-embed-text
```

## 4. Run database migrations

```bash
php artisan key:generate
php artisan migrate
```

## 5. Run the app

Start the Laravel app:

```bash
php artisan serve
```

Then open:

- Chat UI: `http://localhost:8000/`
- Admin login: `http://localhost:8000/admin/login`

## 6. Start the background queue worker

To process uploaded documents asynchronously:

```bash
php artisan queue:work --queue=documents
```

This is required for document indexing and embedding generation.

## Admin panel overview

Once logged in as an admin user, you can:

- view the dashboard statistics
- upload new documents to the knowledge base
- browse uploaded documents
- inspect document metadata and processing status
- reindex a document
- delete a document and its vector entries

The main admin routes are:

- `/admin/login`
- `/admin/dashboard`
- `/admin/documents`
- `/admin/documents/create`
- `/admin/health`

## Health check

There is a built-in health endpoint to verify service availability:

```bash
curl http://localhost:8000/admin/health
```

It checks whether:

- Ollama is reachable and responding to embedding requests
- Qdrant is reachable and serving collection metadata

## Upload and indexing flow

1. Upload a supported file from the admin area
2. Laravel stores the file locally and creates a document record in MySQL
3. The document is queued for background processing
4. The job parses the document text
5. Text is chunked into smaller segments
6. Each chunk is embedded by Ollama
7. Vectors are stored in Qdrant with document metadata in the payload
8. Chat queries retrieve the best matching chunks from Qdrant
9. Groq generates the final answer from the retrieved context

## Document processing status

The `rag_documents` table tracks document lifecycle states such as:

- `pending`
- `queued`
- `processing`
- `parsed`
- `embedded`
- `failed`

## Testing

Run the test suite with:

```bash
./vendor/bin/phpunit
```

## Notes

- The live chat interface answers questions using relevant chunks from the indexed knowledge base.
- The default app route `/` renders the chat UI.
- Uploaded documents are stored under the local storage disk and their vector records are kept in Qdrant.
- Reindexing is supported for refreshing embeddings after a document is updated or re-uploaded.
