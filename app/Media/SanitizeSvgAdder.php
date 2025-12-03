<?php

namespace App\Media;

use enshrined\svgSanitize\Sanitizer;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

class SanitizeSvgAdder extends FileAdder
{
    public function toMediaCollection(string $collectionName = 'default', string $diskName = ''): \Spatie\MediaLibrary\MediaCollections\Models\Media
    {
        // If the file is an SVG, sanitize it before adding
        if ($this->isSvgFile()) {
            $this->sanitizeSvgFile();
        }

        return parent::toMediaCollection($collectionName, $diskName);
    }

    protected function isSvgFile(): bool
    {
        $mimeType = mime_content_type($this->pathToFile);
        $extension = strtolower(pathinfo($this->pathToFile, PATHINFO_EXTENSION));

        return $mimeType === 'image/svg+xml' || $extension === 'svg';
    }

    protected function sanitizeSvgFile(): void
    {
        try {
            // Read the SVG content
            $svgContent = file_get_contents($this->pathToFile);

            if ($svgContent === false) {
                throw new FileCannotBeAdded("Failed to read SVG file: {$this->pathToFile}");
            }

            // Create sanitizer instance
            $sanitizer = new Sanitizer;

            // Configure sanitizer to be strict
            $sanitizer->minify(true);

            // Remove dangerous elements and attributes
            $sanitizer->removeRemoteReferences(true);

            // Remove specific attributes that can contain external references
            $sanitizer->removeXMLTag(true);

            // Sanitize the SVG content
            $sanitizedSvg = $sanitizer->sanitize($svgContent);

            // Validate that sanitization succeeded
            if ($sanitizedSvg === false || empty($sanitizedSvg)) {
                Log::warning('SVG sanitization failed or returned empty content', [
                    'file' => $this->pathToFile,
                    'original_size' => strlen($svgContent),
                ]);

                throw new FileCannotBeAdded(
                    "SVG file contains potentially dangerous content and could not be sanitized safely: {$this->fileName}"
                );
            }

            // Validate that the result is still valid SVG
            if (! $this->isValidSvg($sanitizedSvg)) {
                Log::warning('Sanitized SVG is not valid XML', [
                    'file' => $this->pathToFile,
                ]);

                throw new FileCannotBeAdded(
                    "SVG file became invalid after sanitization: {$this->fileName}"
                );
            }

            // Write the sanitized content back to the file
            if (file_put_contents($this->pathToFile, $sanitizedSvg) === false) {
                throw new FileCannotBeAdded("Failed to write sanitized SVG file: {$this->pathToFile}");
            }

            Log::info('SVG file sanitized successfully', [
                'file' => $this->fileName,
                'original_size' => strlen($svgContent),
                'sanitized_size' => strlen($sanitizedSvg),
            ]);
        } catch (FileCannotBeAdded $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error during SVG sanitization', [
                'file' => $this->pathToFile,
                'error' => $e->getMessage(),
            ]);

            throw new FileCannotBeAdded(
                "Failed to sanitize SVG file: {$e->getMessage()}"
            );
        }
    }

    protected function isValidSvg(string $content): bool
    {
        // Suppress XML errors and check if it's valid XML
        $previousValue = libxml_use_internal_errors(true);

        $doc = simplexml_load_string($content);

        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previousValue);

        if ($doc === false || ! empty($errors)) {
            return false;
        }

        // Check if the root element is actually an SVG
        return $doc->getName() === 'svg';
    }
}
