<?php

use Illuminate\Support\Facades\Storage;

// Test S3 connection
try {
    $files = Storage::disk('s3')->files('videos/17c43809-06a3-4864-a75e-af9e375833d3');
    echo "✅ S3 Connection successful!\n";
    echo "Files found in S3:\n";
    foreach ($files as $file) {
        $size = Storage::disk('s3')->size($file);
        echo "- {$file} (" . number_format($size / 1024 / 1024, 2) . " MB)\n";
    }
} catch (Exception $e) {
    echo "❌ S3 Error: " . $e->getMessage() . "\n";
}

// Check upload record
$upload = App\Models\VideoUpload::where('upload_id', '17c43809-06a3-4864-a75e-af9e375833d3')->first();
if ($upload) {
    echo "\n✅ Upload Record Found:\n";
    echo "- Status: {$upload->status}\n";
    echo "- S3 Key: {$upload->s3_key}\n";
    echo "- Completed: {$upload->completed_at}\n";
    echo "- Progress: {$upload->getProgressPercentage()}%\n";
} else {
    echo "\n❌ Upload record not found\n";
}