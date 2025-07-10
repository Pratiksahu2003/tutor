<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Education Platform',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'The name of your website',
                'sort_order' => 1
            ],
            [
                'key' => 'site_description',
                'value' => 'A comprehensive education platform connecting students with qualified teachers and institutes',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Site Description',
                'description' => 'Brief description of your website for SEO',
                'sort_order' => 2
            ],
            [
                'key' => 'site_keywords',
                'value' => 'education, learning, tutors, teachers, institutes, online learning, courses',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Keywords',
                'description' => 'SEO keywords separated by commas',
                'sort_order' => 3
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@educationplatform.com',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Admin Email',
                'description' => 'Primary admin email address',
                'sort_order' => 4
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Contact Phone',
                'description' => 'Primary contact phone number',
                'sort_order' => 5
            ],
            [
                'key' => 'contact_address',
                'value' => "123 Education Street\nLearning City, LC 12345\nUnited States",
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Contact Address',
                'description' => 'Physical business address',
                'sort_order' => 6
            ],

            // Site Configuration
            [
                'key' => 'site_logo',
                'value' => '',
                'type' => 'file',
                'group' => 'site',
                'label' => 'Site Logo',
                'description' => 'Main logo for the website (recommended: 200x60px)',
                'sort_order' => 1
            ],
            [
                'key' => 'favicon',
                'value' => '',
                'type' => 'file',
                'group' => 'site',
                'label' => 'Favicon',
                'description' => 'Website favicon (recommended: 32x32px .ico file)',
                'sort_order' => 2
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'site',
                'label' => 'Maintenance Mode',
                'description' => 'Enable to show maintenance page to visitors',
                'sort_order' => 3
            ],
            [
                'key' => 'user_registration',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'site',
                'label' => 'User Registration',
                'description' => 'Allow new users to register',
                'sort_order' => 4
            ],
            [
                'key' => 'email_verification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'site',
                'label' => 'Email Verification',
                'description' => 'Require email verification for new accounts',
                'sort_order' => 5
            ],
            [
                'key' => 'site_theme',
                'value' => 'default',
                'type' => 'text',
                'group' => 'site',
                'label' => 'Site Theme',
                'description' => 'Current website theme',
                'sort_order' => 6
            ],

            // Social Media
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/educationplatform',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Facebook page URL',
                'sort_order' => 1
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/eduplatform',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Twitter profile URL',
                'sort_order' => 2
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/educationplatform',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Instagram profile URL',
                'sort_order' => 3
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com/company/education-platform',
                'type' => 'text',
                'group' => 'social',
                'label' => 'LinkedIn URL',
                'description' => 'LinkedIn company page URL',
                'sort_order' => 4
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://youtube.com/@educationplatform',
                'type' => 'text',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'YouTube channel URL',
                'sort_order' => 5
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '+1234567890',
                'type' => 'text',
                'group' => 'social',
                'label' => 'WhatsApp Number',
                'description' => 'WhatsApp contact number',
                'sort_order' => 6
            ],

            // Content Management
            [
                'key' => 'privacy_policy',
                'value' => "# Privacy Policy\n\nThis Privacy Policy describes how Education Platform collects, uses, and protects your information when you use our services.\n\n## Information We Collect\n\nWe collect information you provide directly to us, such as when you create an account, update your profile, or contact us.\n\n## How We Use Your Information\n\nWe use the information we collect to provide, maintain, and improve our services.\n\n## Information Sharing\n\nWe do not sell, trade, or otherwise transfer your personal information to third parties without your consent.\n\n## Data Security\n\nWe implement appropriate security measures to protect your personal information.\n\n## Contact Us\n\nIf you have any questions about this Privacy Policy, please contact us at privacy@educationplatform.com",
                'type' => 'textarea',
                'group' => 'content',
                'label' => 'Privacy Policy',
                'description' => 'Privacy policy content (supports Markdown)',
                'sort_order' => 1
            ],
            [
                'key' => 'terms_conditions',
                'value' => "# Terms and Conditions\n\nWelcome to Education Platform. These terms and conditions outline the rules and regulations for the use of our website.\n\n## Acceptance of Terms\n\nBy accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.\n\n## User Accounts\n\nUsers are responsible for maintaining the confidentiality of their account information.\n\n## Prohibited Uses\n\nYou may not use our service for any illegal or unauthorized purpose.\n\n## Content\n\nOur service allows you to post, link, store, share and otherwise make available certain information, text, graphics, videos, or other material.\n\n## Termination\n\nWe may terminate or suspend your account and bar access to the service immediately, without prior notice or liability.\n\n## Contact Information\n\nIf you have any questions about these Terms and Conditions, please contact us at legal@educationplatform.com",
                'type' => 'textarea',
                'group' => 'content',
                'label' => 'Terms & Conditions',
                'description' => 'Terms and conditions content (supports Markdown)',
                'sort_order' => 2
            ],
            [
                'key' => 'about_us',
                'value' => "# About Education Platform\n\nEducation Platform is a comprehensive online learning ecosystem that connects students with qualified teachers and reputable educational institutes.\n\n## Our Mission\n\nTo democratize education by making quality learning accessible to everyone, everywhere.\n\n## What We Offer\n\n- Qualified Teachers: Connect with experienced educators\n- Verified Institutes: Partner with accredited institutions\n- Flexible Learning: Online and offline options\n- Personalized Experience: Tailored to your learning needs\n\n## Our Values\n\n- **Quality**: We maintain high standards in education\n- **Accessibility**: Learning should be available to all\n- **Innovation**: We embrace new technologies\n- **Community**: Building connections in education\n\n## Contact Us\n\nReach out to us for any inquiries or support.",
                'type' => 'textarea',
                'group' => 'content',
                'label' => 'About Us',
                'description' => 'About us page content (supports Markdown)',
                'sort_order' => 3
            ],
            [
                'key' => 'contact_us_text',
                'value' => "Get in touch with us! We're here to help with any questions or concerns you may have about our platform.",
                'type' => 'textarea',
                'group' => 'content',
                'label' => 'Contact Us Text',
                'description' => 'Additional text for contact page',
                'sort_order' => 4
            ],
            [
                'key' => 'footer_text',
                'value' => '© 2024 Education Platform. All rights reserved. Empowering learners worldwide.',
                'type' => 'text',
                'group' => 'content',
                'label' => 'Footer Text',
                'description' => 'Footer copyright text',
                'sort_order' => 5
            ],

            // System Settings
            [
                'key' => 'items_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Items Per Page',
                'description' => 'Number of items to show per page in listings',
                'sort_order' => 1
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Session Timeout (minutes)',
                'description' => 'User session timeout in minutes',
                'sort_order' => 2
            ],
            [
                'key' => 'max_upload_size',
                'value' => '5120',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Max Upload Size (KB)',
                'description' => 'Maximum file upload size in KB',
                'sort_order' => 3
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'weekly',
                'type' => 'text',
                'group' => 'system',
                'label' => 'Backup Frequency',
                'description' => 'Automatic backup frequency (daily, weekly, monthly)',
                'sort_order' => 4
            ],
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Enable Notifications',
                'description' => 'Enable system notifications',
                'sort_order' => 5
            ],
            [
                'key' => 'debug_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Debug Mode',
                'description' => 'Enable debug mode (not recommended for production)',
                'sort_order' => 6
            ],

            // Cache Settings
            [
                'key' => 'cache_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'cache',
                'label' => 'Enable Caching',
                'description' => 'Enable application caching',
                'sort_order' => 1
            ],
            [
                'key' => 'cache_duration',
                'value' => '3600',
                'type' => 'number',
                'group' => 'cache',
                'label' => 'Cache Duration (seconds)',
                'description' => 'Default cache duration in seconds',
                'sort_order' => 2
            ],

            // Database Settings
            [
                'key' => 'auto_backup',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'database',
                'label' => 'Auto Backup',
                'description' => 'Enable automatic database backups',
                'sort_order' => 1
            ],
            [
                'key' => 'backup_retention_days',
                'value' => '30',
                'type' => 'number',
                'group' => 'database',
                'label' => 'Backup Retention (days)',
                'description' => 'Number of days to keep backup files',
                'sort_order' => 2
            ],

            // FAQ Settings
            [
                'key' => 'faq_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'content',
                'label' => 'Enable FAQ',
                'description' => 'Enable FAQ page functionality',
                'sort_order' => 6
            ],
            [
                'key' => 'faq_categories',
                'value' => '["General", "Accounts", "Payments", "Technical", "Learning"]',
                'type' => 'json',
                'group' => 'content',
                'label' => 'FAQ Categories',
                'description' => 'Available FAQ categories (JSON array)',
                'sort_order' => 7
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
} 