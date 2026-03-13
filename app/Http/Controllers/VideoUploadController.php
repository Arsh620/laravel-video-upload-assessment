<?php

namespace App\Http\Controllers;

use App\Models\VideoUpload;
use App\Jobs\ProcessVideoUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoUploadController extends Controller
{
    public function initializeUpload(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'totalSize' => 'required|integer|min:1',
            'totalChunks' => 'required|integer|min:1'
        ]);

        $uploadId = Str::uuid();
        
        $videoUpload = VideoUpload::create([
            'upload_id' => $uploadId,
            'filename' => $uploadId . '_' . $request->filename,
            'original_name' => $request->filename,
            'total_size' => $request->totalSize,
            'total_chunks' => $request->totalChunks,
            'chunk_status' => array_fill(0, $request->totalChunks, false)
        ]);

        return response()->json([
            'uploadId' => $uploadId,
            'status' => 'initialized'
        ]);
    }

    public function uploadChunk(Request $request)
    {
        $request->validate([
            'uploadId' => 'required|string',
            'chunkNumber' => 'required|integer|min:0',
            'chunk' => 'required|file'
        ]);

        $videoUpload = VideoUpload::where('upload_id', $request->uploadId)->firstOrFail();
        
        if ($videoUpload->status !== 'uploading') {
            return response()->json(['error' => 'Upload not in progress'], 400);
        }

        $chunkPath = storage_path('app/chunks/' . $videoUpload->filename);
        if (!file_exists($chunkPath)) {
            mkdir($chunkPath, 0777, true);
        }

        $chunkFile = $chunkPath . '/' . $request->chunkNumber;
        $request->file('chunk')->move($chunkPath, $request->chunkNumber);

        $chunkStatus = $videoUpload->chunk_status;
        $chunkStatus[$request->chunkNumber] = true;
        
        $videoUpload->update([
            'chunk_status' => $chunkStatus,
            'uploaded_chunks' => array_sum($chunkStatus)
        ]);

        $progress = $videoUpload->getProgressPercentage();

        if ($videoUpload->isComplete()) {
            $this->mergeChunks($videoUpload);
            ProcessVideoUpload::dispatch($videoUpload);
        }

        return response()->json([
            'status' => 'chunk uploaded',
            'progress' => $progress,
            'isComplete' => $videoUpload->isComplete()
        ]);
    }

    public function getUploadStatus(Request $request, $uploadId)
    {
        $videoUpload = VideoUpload::where('upload_id', $uploadId)->firstOrFail();
        
        return response()->json([
            'uploadId' => $videoUpload->upload_id,
            'status' => $videoUpload->status,
            'progress' => $videoUpload->getProgressPercentage(),
            'uploadedChunks' => $videoUpload->uploaded_chunks,
            'totalChunks' => $videoUpload->total_chunks,
            'isComplete' => $videoUpload->isComplete(),
            's3Key' => $videoUpload->s3_key
        ]);
    }

    private function mergeChunks(VideoUpload $videoUpload)
    {
        $chunkPath = storage_path('app/chunks/' . $videoUpload->filename);
        $finalPath = storage_path('app/' . $videoUpload->filename);

        $output = fopen($finalPath, 'wb');

        for ($i = 0; $i < $videoUpload->total_chunks; $i++) {
            $chunkFile = $chunkPath . '/' . $i;
            if (file_exists($chunkFile)) {
                fwrite($output, file_get_contents($chunkFile));
            }
        }

        fclose($output);
    }

    public function resumeUpload(Request $request, $uploadId)
    {
        $videoUpload = VideoUpload::where('upload_id', $uploadId)->firstOrFail();
        
        $missingChunks = [];
        foreach ($videoUpload->chunk_status as $index => $uploaded) {
            if (!$uploaded) {
                $missingChunks[] = $index;
            }
        }

        return response()->json([
            'uploadId' => $videoUpload->upload_id,
            'status' => $videoUpload->status,
            'progress' => $videoUpload->getProgressPercentage(),
            'missingChunks' => $missingChunks,
            'totalChunks' => $videoUpload->total_chunks
        ]);
    }
}
