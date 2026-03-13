<?php

namespace App\Jobs;

use App\Models\VideoUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\VideoUploadCompleted;

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 600;

    public function __construct(
        public VideoUpload $videoUpload
    ) {}

    public function handle(): void
    {
        try {
            $this->videoUpload->update(['status' => 'processing']);

            $localPath = storage_path('app/' . $this->videoUpload->filename);
            
            if (!file_exists($localPath)) {
                throw new \Exception('Merged file not found');
            }

            $s3Key = 'videos/' . $this->videoUpload->upload_id . '/' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $this->videoUpload->original_name);
            
            Storage::disk('s3')->put($s3Key, file_get_contents($localPath));

            $this->videoUpload->update([
                'status' => 'completed',
                's3_key' => $s3Key,
                'completed_at' => now()
            ]);

            $this->cleanup($localPath);

            Mail::to(config('mail.admin_email', 'admin@example.com'))
                ->send(new VideoUploadCompleted($this->videoUpload));

        } catch (\Exception $e) {
            $this->videoUpload->update(['status' => 'failed']);
            throw $e;
        }
    }

    private function cleanup(string $localPath): void
    {
        if (file_exists($localPath)) {
            unlink($localPath);
        }

        $chunkDir = storage_path('app/chunks/' . $this->videoUpload->filename);
        if (is_dir($chunkDir)) {
            array_map('unlink', glob("$chunkDir/*"));
            rmdir($chunkDir);
        }
    }
}