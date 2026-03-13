<!DOCTYPE html>
<html>
<head>
    <title>Video Upload Completed</title>
</head>
<body>
    <h1>Video Upload Completed Successfully</h1>
    
    <p>The video upload has been completed and stored in Amazon S3.</p>
    
    <h3>Upload Details:</h3>
    <ul>
        <li><strong>Upload ID:</strong> {{ $videoUpload->upload_id }}</li>
        <li><strong>Original Filename:</strong> {{ $videoUpload->original_name }}</li>
        <li><strong>File Size:</strong> {{ number_format($videoUpload->total_size / 1024 / 1024, 2) }} MB</li>
        <li><strong>S3 Location:</strong> {{ $videoUpload->s3_key }}</li>
        <li><strong>Completed At:</strong> {{ $videoUpload->completed_at->format('Y-m-d H:i:s') }}</li>
    </ul>
    
    <p>The file is now available in your S3 bucket: {{ config('filesystems.disks.s3.bucket') }}</p>
</body>
</html>