<?php

namespace App\Services;

use RuntimeException;

class DocumentParserService
{
    public function parse(string $filePath, ?string $mimeType = null): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = $mimeType ?? $this->detectMimeType($filePath);

        return match ($extension) {
            'txt', 'md' => $this->extractPlainText($filePath),
            'pdf' => $this->extractPdfText($filePath),
            'docx' => $this->extractDocxText($filePath),
            'doc' => $this->extractDocText($filePath),
            default => throw new RuntimeException("Unsupported document type: {$extension}"),
        };
    }

    private function extractPlainText(string $filePath): string
    {
        $content = file_get_contents($filePath);

        if ($content === false) {
            throw new RuntimeException('Unable to read the uploaded text file.');
        }

        return trim($content);
    }

    private function extractPdfText(string $filePath): string
    {
        if (! $this->commandExists('pdftotext')) {
            throw new RuntimeException('PDF parsing requires the pdftotext command to be installed.');
        }

        $command = sprintf('pdftotext %s - 2>/dev/null', escapeshellarg($filePath));
        $output = [];
        $code = 0;
        exec($command, $output, $code);

        if ($code !== 0) {
            throw new RuntimeException('Unable to extract text from the PDF file.');
        }

        return trim(implode(PHP_EOL, $output));
    }

    private function extractDocxText(string $filePath): string
    {
        $zip = new \ZipArchive();

        if ($zip->open($filePath) !== true) {
            throw new RuntimeException('Unable to read the DOCX file.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            throw new RuntimeException('The DOCX file does not contain a valid document body.');
        }

        libxml_use_internal_errors(true);
        $document = new \DOMDocument();
        $document->loadXML($xml);

        $textNodes = [];

        foreach ($document->getElementsByTagName('t') as $node) {
            $textNodes[] = $node->textContent;
        }

        return trim(implode(' ', array_filter($textNodes, static fn ($value) => $value !== null && $value !== '')));
    }

    private function extractDocText(string $filePath): string
    {
        if ($this->commandExists('antiword')) {
            $command = sprintf('antiword %s 2>/dev/null', escapeshellarg($filePath));
            $output = [];
            $code = 0;
            exec($command, $output, $code);

            if ($code === 0) {
                return trim(implode(PHP_EOL, $output));
            }
        }

        if ($this->commandExists('catdoc')) {
            $command = sprintf('catdoc %s 2>/dev/null', escapeshellarg($filePath));
            $output = [];
            $code = 0;
            exec($command, $output, $code);

            if ($code === 0) {
                return trim(implode(PHP_EOL, $output));
            }
        }

        throw new RuntimeException('DOC parsing requires antiword or catdoc to be installed.');
    }

    private function detectMimeType(string $filePath): string
    {
        $mimeType = mime_content_type($filePath);

        return $mimeType ?: 'application/octet-stream';
    }

    private function commandExists(string $command): bool
    {
        $result = shell_exec(sprintf('command -v %s 2>/dev/null', escapeshellarg($command)));

        return ! empty($result);
    }
}
