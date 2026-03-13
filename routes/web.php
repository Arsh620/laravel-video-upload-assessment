<?php

use App\Http\Controllers\VideoUploadController;
use Illuminate\Support\Facades\Route;
use App\Models\VideoUpload;
use App\Mail\VideoUploadCompleted;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('upload');
});

// Demo route to test email
Route::get('/test-email', function () {
    $upload = VideoUpload::where('status', 'completed')->first();
    if ($upload) {
        Mail::to('admin@example.com')->send(new VideoUploadCompleted($upload));
        return 'Email sent successfully! Check your inbox or logs.';
    }
    return 'No completed uploads found to test email.';
});

// Video Upload Routes
Route::post('/upload/initialize', [VideoUploadController::class, 'initializeUpload']);
Route::post('/upload/chunk', [VideoUploadController::class, 'uploadChunk']);
Route::get('/upload/status/{uploadId}', [VideoUploadController::class, 'getUploadStatus']);
Route::get('/upload/resume/{uploadId}', [VideoUploadController::class, 'resumeUpload']);