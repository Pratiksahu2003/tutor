# Education Platform Database Setup

This document provides complete instructions for setting up the education platform database from scratch on any environment.

## 🚀 Quick Setup

### Option 1: Automated Setup (Recommended)
```bash
# For fresh installation with sample data
php setup-database.php --fresh --seed --force

# For existing installation, just add tables and data
php setup-database.php --seed
```

### Option 2: Manual Setup
```bash
# Clear caches
php artisan config:clear
php artisan cache:clear

# Run migrations
php artisan migrate:fresh --force  # For fresh install
# OR
php artisan migrate --force       # For existing install

# Seed database with sample data
php artisan db:seed --class=CompleteEducationPlatformSeeder

# Optimize application
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

## 📋 Database Structure

The master migration creates the following tables:

### Core Tables
- **users** - User accounts (students, teachers, institutes, admins)
- **password_reset_tokens** - Password reset functionality
- **sessions** - User session management

### Role Management
- **roles** - User roles definition
- **permissions** - System permissions
- **user_roles** - User-role relationships
- **role_permissions** - Role-permission relationships

### Education Content
- **subjects** - Academic subjects
- **exam_categories** - Exam category classification
- **exams** - Competitive and board exams
- **exam_subjects** - Exam-subject relationships

### Profiles
- **teacher_profiles** - Teacher information and preferences
- **student_profiles** - Student information and requirements
- **institutes** - Educational institute details

### Relationships
- **teacher_subjects** - Teacher expertise in subjects
- **institute_subjects** - Subjects offered by institutes
- **teacher_exams** - Teacher specialization in exams
- **institute_exams** - Exam preparation by institutes

### Hierarchical Structure
- **school_branches** - Institute branch management
- **school_branch_subjects** - Branch-wise subject offerings
- **school_branch_exams** - Branch-wise exam preparations

### Content Management
- **pages** - Static pages (About, Privacy Policy, etc.)
- **blog_posts** - Blog and news articles
- **questions** - Question bank for assessments

### CRM & Business
- **leads** - Lead management system
- **inquiries** - Student inquiry tracking
- **site_settings** - Application configuration

### System Tables
- **cache**, **cache_locks** - Caching system
- **jobs**, **job_batches**, **failed_jobs** - Queue management
- **menus**, **sliders** - Navigation and UI elements

## 🔧 Configuration

### Environment Setup
Ensure your `.env` file has the correct database configuration:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
# OR for MySQL/PostgreSQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=education_platform
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Required Laravel Version
- Laravel 10.x or higher
- PHP 8.1 or higher

## 👥 Default Users Created

After running the seeder, these accounts will be available:

### Admin Account
- **Email:** admin@educonnect.com
- **Password:** admin123
- **Role:** Administrator

### Sample Teachers
- **Email:** rajesh.kumar@example.com (Password: teacher123)
- **Email:** priya.sharma@example.com (Password: teacher123)
- **Email:** amit.patel@example.com (Password: teacher123)
- **Email:** sneha.agarwal@example.com (Password: teacher123)
- **Email:** suresh.gupta@example.com (Password: teacher123)

### Sample Institutes
- **Email:** info@excellenceacademy.com (Password: institute123)
- **Email:** contact@brightfuture.com (Password: institute123)
- **Email:** info@knowledgehub.com (Password: institute123)
- **Email:** admin@successpoint.com (Password: institute123)
- **Email:** info@elitecoaching.com (Password: institute123)

### Sample Students
- **Email:** rahul.verma@example.com (Password: student123)
- **Email:** priya.singh@example.com (Password: student123)
- **Email:** arjun.patel@example.com (Password: student123)

## 📚 Sample Data Included

### Subjects (23 subjects across categories)
- **Science:** Mathematics, Physics, Chemistry, Biology, Computer Science
- **Languages:** English, Hindi, Sanskrit, French, German
- **Social Science:** History, Geography, Political Science, Economics, Sociology
- **Commerce:** Accountancy, Business Studies
- **Arts:** Music, Drawing, Dance
- **Competitive:** Reasoning, General Knowledge, Current Affairs

### Exams and Categories
- **Engineering Entrance:** JEE Main, JEE Advanced
- **Medical Entrance:** NEET
- **Management Entrance:** CAT
- **Civil Services:** UPSC CSE
- **Bank Exams:** IBPS PO
- **Board Exams:** CBSE Class 10, CBSE Class 12

### Site Settings
Complete site configuration including:
- General settings (site name, tagline, contact info)
- Social media links
- SEO meta tags
- Email configuration
- Platform settings (registration controls, verification requirements)
- Cache settings

## 🔄 Migration Features

### Safety Features
- **Conditional column creation** - Won't fail if columns already exist
- **Foreign key handling** - Proper constraint management
- **Index optimization** - Performance indexes on key columns
- **Rollback support** - Complete down() method for safe rollbacks

### Flexibility
- **Database agnostic** - Works with MySQL, PostgreSQL, SQLite
- **Modular structure** - Can be run in parts if needed
- **Version control friendly** - Single migration file for consistency

## 🛠️ Troubleshooting

### Common Issues

#### Database Connection Error
```bash
# Check database credentials in .env
# For SQLite, ensure file exists and is writable
touch database/database.sqlite
chmod 664 database/database.sqlite
```

#### Migration Fails
```bash
# Clear all caches and try again
php artisan config:clear
php artisan cache:clear
php artisan migrate:rollback --force
php artisan migrate --force
```

#### Foreign Key Constraints
```bash
# For MySQL, check if foreign key checks are enabled
# The migration handles this automatically
```

#### Permission Issues
```bash
# Ensure proper permissions on storage and bootstrap directories
chmod -R 775 storage bootstrap/cache
```

### Reset Everything
```bash
# Complete reset (⚠️ DELETES ALL DATA)
php artisan migrate:fresh --force
php artisan db:seed --class=CompleteEducationPlatformSeeder --force
```

## 📈 Performance Optimization

The migration includes several performance optimizations:

### Database Indexes
- User role and activity indexes
- Location-based search indexes
- Subject and exam relationship indexes
- Search and filter optimization indexes

### JSON Column Usage
- Efficient storage for flexible data (languages, facilities, preferences)
- Reduces need for additional relationship tables
- Better performance for complex queries

### Proper Foreign Keys
- Cascade deletes where appropriate
- Set null for optional relationships
- Prevents orphaned records

## 🔐 Security Considerations

### Password Hashing
- All sample passwords use Laravel's secure hashing
- Default passwords should be changed in production

### Data Validation
- Enum constraints for status fields
- Unique constraints on critical fields
- Proper foreign key relationships

### User Verification
- Email verification support built-in
- Account activation controls
- Role-based access control ready

## 🚀 Next Steps

After successful database setup:

1. **Start the application:**
   ```bash
   php artisan serve
   ```

2. **Access admin panel:**
   - Go to `/admin/login`
   - Use admin credentials provided above

3. **Customize settings:**
   - Update site settings via admin panel
   - Configure email and SMS settings
   - Customize platform behavior

4. **Add your content:**
   - Add real teachers and institutes
   - Update subjects and exams as needed
   - Create pages and blog content

5. **Configure production:**
   - Update .env for production database
   - Set up proper caching (Redis recommended)
   - Configure queue workers
   - Set up file storage (S3, etc.)

## 📞 Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify your environment meets requirements
3. Ensure database credentials are correct
4. Check Laravel logs in `storage/logs/`

The migration is designed to be robust and handle most edge cases automatically. 