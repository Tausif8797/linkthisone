<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoDownloaderController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function download(Request $request)
    {
        $request->validate([
            'video_url' => 'required|url',
        ]);

        $videoUrl = $request->input('video_url');

        // Identify platform
        if (strpos($videoUrl, 'facebook.com') !== false) {
            $platform = 'facebook';
        } elseif (strpos($videoUrl, 'instagram.com') !== false) {
            $platform = 'instagram';
        } else {
            return back()->withErrors(['video_url' => 'Unsupported platform']);
        }

        try {
            // Set output directory
            $outputPath = storage_path('app/public/videos');
            
            // Create directory if not exists
            if (!File::exists($outputPath)) {
                File::makeDirectory($outputPath, 0755, true);
            }

            $timestamp = time();
$command = "yt-dlp -o \"{$outputPath}/{$timestamp}_%(title)s.%(ext)s\" {$videoUrl}";

            // Use double quotes for Windows paths
            // $command = "yt-dlp -o \"{$outputPath}/%(title)s.%(ext)s\" {$videoUrl}";
            
            exec($command, $output, $returnCode);
            
            // Log::info("return code: {$returnCode}");

            if ($returnCode !== 0) {
                return back()->withErrors(['video_url' => 'Failed to download video.']);
            }

            // Find downloaded file
            $files = File::files($outputPath);
            if (empty($files)) {
                return back()->withErrors(['video_url' => 'No video was downloaded.']);
            }
            Log::info("Video URL: {$videoUrl}");
Log::info("yt-dlp command: {$command}");
Log::info("return code: {$returnCode}");

            $latestFile = end($files);
            return response()->download($latestFile->getPathname());
        } catch (\Exception $e) {
            // Log::error('Download error: ' . $e->getMessage());
            return back()->withErrors(['video_url' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
