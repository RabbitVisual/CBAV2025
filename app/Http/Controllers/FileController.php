<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Serve a protected image from storage.
     *
     * @param string $directory The directory within storage/app/public where the file is located.
     * @param string $filename The name of the file to serve.
     * @return StreamedResponse|\Illuminate\Http\Response
     */
    public function serveImage(string $directory, string $filename)
    {
        // Sanitize inputs to prevent directory traversal attacks
        $directory = str_replace(['..', '/'], '', $directory);
        $filename = str_replace(['..', '/'], '', $filename);

        $path = "public/{$directory}/{$filename}";

        if (!Storage::exists($path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        $file = Storage::get($path);
        $mime = Storage::mimeType($path);

        return response($file, 200)->header('Content-Type', $mime);
    }
}