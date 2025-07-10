<?php

namespace App\Helpers;

use App\Models\SiteSetting;

class SettingsHelper
{
    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function all()
    {
        $settings = SiteSetting::where('is_active', true)->get();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = SiteSetting::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }

    /**
     * Get social media links
     */
    public static function getSocialLinks()
    {
        return [
            'facebook' => self::get('facebook_url'),
            'twitter' => self::get('twitter_url'),
            'instagram' => self::get('instagram_url'),
            'linkedin' => self::get('linkedin_url'),
            'youtube' => self::get('youtube_url'),
            'whatsapp' => self::get('whatsapp_number'),
        ];
    }

    /**
     * Get site logo URL
     */
    public static function getLogo()
    {
        $logo = self::get('site_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Get favicon URL
     */
    public static function getFavicon()
    {
        $favicon = self::get('favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return (bool) self::get('maintenance_mode', false);
    }

    /**
     * Check if user registration is enabled
     */
    public static function isRegistrationEnabled()
    {
        return (bool) self::get('user_registration', true);
    }

    /**
     * Check if email verification is required
     */
    public static function isEmailVerificationRequired()
    {
        return (bool) self::get('email_verification', true);
    }

    /**
     * Get contact information
     */
    public static function getContactInfo()
    {
        return [
            'email' => self::get('admin_email'),
            'phone' => self::get('contact_phone'),
            'address' => self::get('contact_address'),
        ];
    }

    /**
     * Get SEO meta data
     */
    public static function getSeoData()
    {
        return [
            'title' => self::get('site_name'),
            'description' => self::get('site_description'),
            'keywords' => self::get('site_keywords'),
        ];
    }
} 