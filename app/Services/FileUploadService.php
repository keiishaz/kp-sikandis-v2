<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Mengunggah file ke direktori yang ditentukan.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string Path file yang berhasil diunggah
     */
    public function upload(UploadedFile $file, string $directory = 'dokumen-sk', string $disk = 'public'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = time() . '-' . Str::random(10) . '.' . $extension;
        
        $path = $file->storeAs($directory, $filename, $disk);

        return $path;
    }

    /**
     * Menghapus file jika ada.
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function delete(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
}
