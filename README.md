# 🎥 Laravel Video Upload System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/AWS_S3-Storage-FF9900?style=for-the-badge&logo=amazon-aws&logoColor=white" alt="AWS S3">
  <img src="https://img.shields.io/badge/Queue-Database-4CAF50?style=for-the-badge&logo=database&logoColor=white" alt="Queue">
</p>

<p align="center">
  A professional, production-ready Laravel application for uploading large video files with chunked processing, real-time progress tracking, S3 storage, and email notifications.
</p>

## ✨ Features

### 🚀 **Core Functionality**
- **Chunked Upload**: Handles large video files (up to 500MB) with 1MB chunks
- **Real-time Progress**: Live progress tracking with percentage and chunk counters
- **Resumable Uploads**: Pause and resume uploads seamlessly
- **Background Processing**: Laravel queues for asynchronous file processing
- **S3 Integration**: Secure storage on Amazon S3 with scalable architecture
- **Email Notifications**: Automatic admin notifications on upload completion

### 🎨 **Modern UI/UX**
- **Responsive Design**: Mobile-friendly interface
- **Drag & Drop**: Intuitive file selection
- **Real-time Stats**: Upload speed, time remaining, uploaded size
- **Professional Styling**: Modern gradients, animations, and icons
- **Status Indicators**: Clear visual feedback for all operations

### 🔧 **Technical Features**
- **Error Handling**: Comprehensive error management with retries
- **File Validation**: Type and size validation
- **Database Tracking**: Complete upload history and status
- **Queue Management**: Reliable background job processing
- **Security**: CSRF protection and secure file handling

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Laravel**: 11.x
- **Database**: MySQL/SQLite
- **AWS Account**: For S3 storage
- **Composer**: For dependency management
- **Node.js**: For asset compilation (optional)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/laravel-video-upload.git
cd laravel-video-upload
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Environment Variables
Update your `.env` file with the following:

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=video_upload
DB_USERNAME=root
DB_PASSWORD=

# AWS S3 Configuration
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket_name

# Queue Configuration
QUEUE_CONNECTION=database

# Mail Configuration (Gmail Example)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_ADMIN_EMAIL="admin@yourdomain.com"
```

### 5. Database Setup
```bash
php artisan migrate
```

### 6. AWS S3 Setup
1. Create an S3 bucket in AWS Console
2. Create IAM user with S3 permissions
3. Generate access keys
4. Update `.env` with your credentials

### 7. Gmail App Password (for Email)
1. Enable 2-Factor Authentication in Gmail
2. Go to Google Account → Security → App Passwords
3. Generate app password for "Mail"
4. Use the 16-character password in `.env`

## 🎯 Usage

### 1. Start the Application
```bash
php artisan serve
```

### 2. Start Queue Worker
```bash
php artisan queue:work --timeout=600
```

### 3. Access the Application
Visit `http://localhost:8000` in your browser

### 4. Upload Process
1. **Select File**: Drag & drop or click to select video file
2. **Start Upload**: Click "Start Upload" button
3. **Monitor Progress**: Watch real-time progress and stats
4. **Background Processing**: File automatically processes and uploads to S3
5. **Email Notification**: Admin receives email when complete

## 📁 Project Structure

```
laravel-video-upload/
├── app/
│   ├── Http/Controllers/
│   │   └── VideoUploadController.php    # Main upload controller
│   ├── Jobs/
│   │   └── ProcessVideoUpload.php        # Background job for S3 upload
│   ├── Mail/
│   │   └── VideoUploadCompleted.php      # Email notification class
│   └── Models/
│       └── VideoUpload.php               # Upload tracking model
├── database/migrations/
│   └── create_video_uploads_table.php    # Database schema
├── resources/views/
│   ├── upload.blade.php                  # Main upload interface
│   └── emails/
│       └── video-upload-completed.blade.php  # Email template
└── routes/
    └── web.php                           # Application routes
```

## 🔄 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/` | Main upload interface |
| `POST` | `/upload/initialize` | Initialize chunked upload |
| `POST` | `/upload/chunk` | Upload individual chunk |
| `GET` | `/upload/status/{id}` | Check upload status |
| `GET` | `/upload/resume/{id}` | Resume paused upload |
| `GET` | `/test-email` | Test email functionality |

## 🧪 Testing

### Test Email Functionality
```bash
# Visit in browser
http://localhost:8000/test-email
```

### Test Upload Process
1. Select a video file (MP4, AVI, MOV)
2. Monitor console for any errors
3. Check database for upload records
4. Verify file appears in S3 bucket
5. Check email logs or inbox

## 🔧 Configuration Options

### Chunk Size
Modify chunk size in `upload.blade.php`:
```javascript
const CHUNK_SIZE = 1024 * 1024; // 1MB chunks
```

### File Size Limit
Update validation in JavaScript:
```javascript
if (file.size > 500 * 1024 * 1024) { // 500MB limit
```

### Queue Timeout
Adjust in `ProcessVideoUpload.php`:
```php
public $timeout = 600; // 10 minutes
```

## 🚨 Troubleshooting

### Common Issues

**1. Queue Jobs Not Processing**
```bash
# Make sure queue worker is running
php artisan queue:work

# Check failed jobs
php artisan queue:failed
```

**2. S3 Upload Errors**
- Verify AWS credentials in `.env`
- Check S3 bucket permissions
- Ensure bucket exists in correct region

**3. Email Not Sending**
```bash
# Check mail configuration
php artisan tinker
>>> config('mail.mailer')

# Test email
http://localhost:8000/test-email
```

**4. File Upload Fails**
- Check PHP upload limits in `php.ini`:
  ```ini
  upload_max_filesize = 500M
  post_max_size = 500M
  max_execution_time = 300
  ```

### Debug Mode
Enable debug mode in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## 📊 Database Schema

### `video_uploads` Table
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `upload_id` | string | Unique upload identifier |
| `filename` | string | Generated filename |
| `original_name` | string | Original file name |
| `total_size` | bigint | File size in bytes |
| `total_chunks` | integer | Total number of chunks |
| `uploaded_chunks` | integer | Chunks uploaded so far |
| `chunk_status` | json | Array of chunk completion status |
| `status` | enum | Upload status (uploading/processing/completed/failed) |
| `s3_key` | string | S3 object key |
| `completed_at` | timestamp | Completion timestamp |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

## 🔐 Security Considerations

- **CSRF Protection**: All forms include CSRF tokens
- **File Validation**: Type and size validation on both client and server
- **Secure Storage**: Files stored in private S3 bucket
- **Input Sanitization**: All user inputs are validated and sanitized
- **Error Handling**: Sensitive information not exposed in error messages

## 🚀 Production Deployment

### 1. Environment Setup
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Use production mail driver
MAIL_MAILER=ses  # or smtp
```

### 2. Queue Management
```bash
# Use Supervisor for queue workers
sudo apt install supervisor

# Create supervisor config
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

### 3. Web Server Configuration
- Configure Nginx/Apache
- Set up SSL certificate
- Configure proper file upload limits

## 📈 Performance Optimization

- **Chunk Size**: Optimize based on network conditions
- **Queue Workers**: Run multiple workers for high load
- **Database Indexing**: Add indexes on frequently queried columns
- **CDN**: Use CloudFront for S3 content delivery
- **Caching**: Implement Redis for session and cache storage

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel Framework
- AWS SDK for PHP
- Font Awesome Icons
- Modern CSS Gradients

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Email: support@yourdomain.com
- Documentation: [Wiki](https://github.com/your-username/laravel-video-upload/wiki)

---

<p align="center">
  Made with ❤️ using Laravel
</p>