<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Video Upload System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-content {
            padding: 40px;
        }

        .upload-zone {
            border: 3px dashed #e5e7eb;
            border-radius: 15px;
            padding: 60px 20px;
            text-align: center;
            transition: all 0.3s ease;
            background: #f9fafb;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .upload-zone::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(79, 70, 229, 0.1), transparent);
            transition: left 0.5s;
        }

        .upload-zone:hover::before {
            left: 100%;
        }

        .upload-zone.dragover {
            border-color: #4f46e5;
            background: #eef2ff;
            transform: scale(1.02);
        }

        .upload-icon {
            font-size: 4rem;
            color: #9ca3af;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .upload-zone:hover .upload-icon {
            color: #4f46e5;
            transform: scale(1.1);
        }

        .upload-text {
            font-size: 1.3rem;
            color: #374151;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .upload-subtext {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .file-info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            display: none;
        }

        .file-info-card.show {
            display: block;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .file-info-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .file-detail {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-detail i {
            color: #4f46e5;
            width: 20px;
        }

        .progress-section {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            display: none;
        }

        .progress-section.show {
            display: block;
            animation: slideIn 0.5s ease;
        }

        .progress-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-container {
            background: #e5e7eb;
            border-radius: 25px;
            height: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            border-radius: 25px;
            transition: width 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .progress-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4f46e5;
        }

        .chunk-info {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .status-text {
            font-size: 1rem;
            color: #374151;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            background: #f3f4f6;
        }

        .controls {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin: 15px 0;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.5s ease;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .upload-zone {
                padding: 40px 15px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .controls {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-cloud-upload-alt"></i> Video Upload System</h1>
            <p>Upload large video files with chunked processing and real-time progress tracking</p>
        </div>

        <div class="main-content">
            <div class="upload-zone" id="uploadZone">
                <div class="upload-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="upload-text">Drag & Drop Your Video Here</div>
                <div class="upload-subtext">or click to browse files • Supports MP4, AVI, MOV • Max 500MB</div>
                <input type="file" id="fileInput" accept="video/*" style="display: none;">
                <button class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-folder-open"></i> Choose Video File
                </button>
            </div>

            <div class="file-info-card" id="fileInfoCard">
                <div class="file-info-title">
                    <i class="fas fa-info-circle"></i> File Information
                </div>
                <div class="file-details">
                    <div class="file-detail">
                        <i class="fas fa-file-video"></i>
                        <div>
                            <strong>Name:</strong><br>
                            <span id="fileName">-</span>
                        </div>
                    </div>
                    <div class="file-detail">
                        <i class="fas fa-hdd"></i>
                        <div>
                            <strong>Size:</strong><br>
                            <span id="fileSize">-</span>
                        </div>
                    </div>
                    <div class="file-detail">
                        <i class="fas fa-tag"></i>
                        <div>
                            <strong>Type:</strong><br>
                            <span id="fileType">-</span>
                        </div>
                    </div>
                    <div class="file-detail">
                        <i class="fas fa-puzzle-piece"></i>
                        <div>
                            <strong>Chunks:</strong><br>
                            <span id="totalChunks">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="progress-section" id="progressSection">
                <div class="progress-title">
                    <i class="fas fa-chart-line"></i> Upload Progress
                </div>
                <div class="progress-info">
                    <div class="progress-text" id="progressText">0%</div>
                    <div class="chunk-info" id="chunkInfo">0/0 chunks</div>
                </div>
                <div class="progress-container">
                    <div class="progress-bar" id="progressBar" style="width: 0%;"></div>
                </div>
                <div class="status-text" id="statusText">Ready to upload</div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number" id="uploadSpeed">0</div>
                        <div class="stat-label">MB/s</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="timeRemaining">--</div>
                        <div class="stat-label">Time Left</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number" id="uploadedSize">0</div>
                        <div class="stat-label">MB Uploaded</div>
                    </div>
                </div>
            </div>

            <div class="controls" id="controls" style="display: none;">
                <button class="btn btn-success" id="uploadBtn" onclick="startUpload()">
                    <i class="fas fa-play"></i> Start Upload
                </button>
                <button class="btn btn-warning" id="pauseBtn" onclick="pauseUpload()" disabled>
                    <i class="fas fa-pause"></i> Pause
                </button>
                <button class="btn btn-success" id="resumeBtn" onclick="resumeUpload()" disabled>
                    <i class="fas fa-play"></i> Resume
                </button>
                <button class="btn btn-danger" id="cancelBtn" onclick="cancelUpload()" disabled>
                    <i class="fas fa-stop"></i> Cancel
                </button>
            </div>

            <div id="statusMessages"></div>
        </div>
    </div>

    <script>
        const CHUNK_SIZE = 1024 * 1024; // 1MB chunks
        let selectedFile = null;
        let uploadId = null;
        let isUploading = false;
        let isPaused = false;
        let currentChunk = 0;
        let totalChunks = 0;
        let startTime = null;
        let uploadedBytes = 0;

        // Setup drag and drop
        const uploadZone = document.getElementById('uploadZone');
        const fileInput = document.getElementById('fileInput');

        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });

        uploadZone.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (!file.type.startsWith('video/')) {
                showAlert('Please select a video file.', 'error');
                return;
            }

            if (file.size > 500 * 1024 * 1024) { // 500MB limit
                showAlert('File size must be less than 500MB.', 'error');
                return;
            }

            selectedFile = file;
            totalChunks = Math.ceil(file.size / CHUNK_SIZE);

            // Update file info
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            document.getElementById('fileType').textContent = file.type;
            document.getElementById('totalChunks').textContent = totalChunks;
            document.getElementById('chunkInfo').textContent = `0/${totalChunks} chunks`;

            // Show cards with animation
            document.getElementById('fileInfoCard').classList.add('show');
            document.getElementById('progressSection').classList.add('show');
            document.getElementById('controls').style.display = 'flex';

            showAlert('File selected successfully. Ready to upload!', 'info');
        }

        async function startUpload() {
            if (!selectedFile) return;

            try {
                showAlert('Initializing upload...', 'info');
                
                // Initialize upload
                const initResponse = await fetch('/upload/initialize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        filename: selectedFile.name,
                        totalSize: selectedFile.size,
                        totalChunks: totalChunks
                    })
                });

                const initData = await initResponse.json();
                uploadId = initData.uploadId;

                isUploading = true;
                isPaused = false;
                currentChunk = 0;
                startTime = Date.now();
                uploadedBytes = 0;

                updateButtons();
                showAlert('Upload started...', 'info');
                updateStatusText('Uploading...');

                await uploadChunks();

            } catch (error) {
                showAlert('Error starting upload: ' + error.message, 'error');
                resetUpload();
            }
        }

        async function uploadChunks() {
            while (currentChunk < totalChunks && isUploading && !isPaused) {
                try {
                    const start = currentChunk * CHUNK_SIZE;
                    const end = Math.min(start + CHUNK_SIZE, selectedFile.size);
                    const chunk = selectedFile.slice(start, end);

                    const chunkStartTime = Date.now();

                    const formData = new FormData();
                    formData.append('uploadId', uploadId);
                    formData.append('chunkNumber', currentChunk);
                    formData.append('chunk', chunk);

                    const response = await fetch('/upload/chunk', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        currentChunk++;
                        uploadedBytes += chunk.size;
                        
                        // Calculate upload speed
                        const chunkTime = (Date.now() - chunkStartTime) / 1000;
                        const chunkSpeed = (chunk.size / 1024 / 1024) / chunkTime; // MB/s
                        
                        updateProgress(data.progress);
                        updateStats(chunkSpeed);
                        document.getElementById('chunkInfo').textContent = `${currentChunk}/${totalChunks} chunks`;

                        if (data.isComplete) {
                            showAlert('Upload completed! Processing video...', 'success');
                            updateStatusText('Processing and uploading to S3...');
                            monitorProcessing();
                            return;
                        }
                    } else {
                        throw new Error(data.error || 'Upload failed');
                    }

                } catch (error) {
                    showAlert('Error uploading chunk: ' + error.message, 'error');
                    pauseUpload();
                    return;
                }
            }
        }

        async function monitorProcessing() {
            const checkStatus = async () => {
                try {
                    const response = await fetch(`/upload/status/${uploadId}`);
                    const data = await response.json();

                    if (data.status === 'completed') {
                        showAlert('Video successfully uploaded to S3! Email notification sent.', 'success');
                        updateStatusText('✅ Upload Complete - Stored in S3');
                        resetUpload();
                    } else if (data.status === 'failed') {
                        showAlert('Video processing failed. Please try again.', 'error');
                        updateStatusText('❌ Processing Failed');
                        resetUpload();
                    } else {
                        updateStatusText('🔄 Processing video and uploading to S3...');
                        setTimeout(checkStatus, 2000);
                    }
                } catch (error) {
                    showAlert('Error checking status: ' + error.message, 'error');
                }
            };

            checkStatus();
        }

        function pauseUpload() {
            isPaused = true;
            isUploading = false;
            updateButtons();
            updateStatusText('⏸️ Upload Paused');
            showAlert('Upload paused.', 'info');
        }

        async function resumeUpload() {
            if (!uploadId) return;

            try {
                const response = await fetch(`/upload/resume/${uploadId}`);
                const data = await response.json();

                isPaused = false;
                isUploading = true;
                currentChunk = data.totalChunks - data.missingChunks.length;

                updateButtons();
                updateStatusText('▶️ Upload Resumed');
                showAlert('Upload resumed...', 'info');

                await uploadChunks();

            } catch (error) {
                showAlert('Error resuming upload: ' + error.message, 'error');
            }
        }

        function cancelUpload() {
            isUploading = false;
            isPaused = false;
            resetUpload();
            updateStatusText('🚫 Upload Cancelled');
            showAlert('Upload cancelled.', 'info');
        }

        function resetUpload() {
            isUploading = false;
            isPaused = false;
            currentChunk = 0;
            uploadId = null;
            startTime = null;
            uploadedBytes = 0;
            updateButtons();
            updateProgress(0);
            document.getElementById('chunkInfo').textContent = `0/${totalChunks} chunks`;
            updateStats(0);
        }

        function updateButtons() {
            document.getElementById('uploadBtn').disabled = isUploading;
            document.getElementById('pauseBtn').disabled = !isUploading;
            document.getElementById('resumeBtn').disabled = !isPaused;
            document.getElementById('cancelBtn').disabled = !isUploading && !isPaused;
        }

        function updateProgress(percentage) {
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = Math.round(percentage) + '%';
        }

        function updateStats(speed) {
            // Update upload speed
            document.getElementById('uploadSpeed').textContent = speed.toFixed(1);
            
            // Update uploaded size
            document.getElementById('uploadedSize').textContent = (uploadedBytes / 1024 / 1024).toFixed(1);
            
            // Calculate time remaining
            if (speed > 0 && selectedFile) {
                const remainingBytes = selectedFile.size - uploadedBytes;
                const remainingTime = remainingBytes / (speed * 1024 * 1024); // seconds
                document.getElementById('timeRemaining').textContent = formatTime(remainingTime);
            }
        }

        function updateStatusText(text) {
            document.getElementById('statusText').textContent = text;
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            
            const icon = type === 'success' ? 'fas fa-check-circle' : 
                        type === 'error' ? 'fas fa-exclamation-circle' : 
                        'fas fa-info-circle';
            
            alertDiv.innerHTML = `<i class="${icon}"></i> ${message}`;
            
            const container = document.getElementById('statusMessages');
            container.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function formatTime(seconds) {
            if (seconds < 60) return Math.round(seconds) + 's';
            if (seconds < 3600) return Math.round(seconds / 60) + 'm';
            return Math.round(seconds / 3600) + 'h';
        }
    </script>
</body>
</html>