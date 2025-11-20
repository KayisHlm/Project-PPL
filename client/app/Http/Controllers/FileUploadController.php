<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Handle temporary file upload for Dropzone
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTemp(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // max 10MB
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Generate unique filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '_' . time() . '.' . $extension;
                
                // Store file in storage/app/public/temp
                $path = $file->storeAs('temp', $filename, 'public');
                
                // Return the storage path
                return response()->json([
                    'success' => true,
                    'path' => $path,
                    'url' => Storage::url($path),
                    'original_name' => $originalName
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move file from temp to permanent location
     *
     * @param string $tempPath
     * @param string $destination
     * @return string|false
     */
    public static function moveTempFile($tempPath, $destination)
    {
        if (Storage::disk('public')->exists($tempPath)) {
            $filename = basename($tempPath);
            $newPath = $destination . '/' . $filename;
            
            Storage::disk('public')->move($tempPath, $newPath);
            
            return $newPath;
        }
        
        return false;
    }

    /**
     * Clean up old temporary files (older than 24 hours)
     */
    public static function cleanupTempFiles()
    {
        $files = Storage::disk('public')->files('temp');
        
        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            
            // Delete files older than 24 hours
            if (time() - $lastModified > 86400) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
