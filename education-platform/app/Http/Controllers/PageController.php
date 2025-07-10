<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    /**
     * Display the about page
     */
    public function about()
    {
        $page = Cache::remember('page.about', 3600, function () {
            return Page::where('slug', 'about')->where('status', 'published')->first();
        });

        if (!$page) {
            // Default content if page doesn't exist in CMS
            $page = (object) [
                'title' => 'About Us',
                'content' => $this->getDefaultAboutContent(),
                'meta_title' => 'About Us - Education Platform',
                'meta_description' => 'Learn more about our education platform connecting students with qualified teachers and institutes.',
            ];
        }

        return view('pages.about', compact('page'));
    }

    /**
     * Display the services page
     */
    public function services()
    {
        $page = Cache::remember('page.services', 3600, function () {
            return Page::where('slug', 'services')->where('status', 'published')->first();
        });

        if (!$page) {
            $page = (object) [
                'title' => 'Our Services',
                'content' => $this->getDefaultServicesContent(),
                'meta_title' => 'Our Services - Education Platform',
                'meta_description' => 'Discover our comprehensive education services including online tutoring, institute partnerships, and more.',
            ];
        }

        return view('pages.services', compact('page'));
    }

    /**
     * Display terms and conditions
     */
    public function terms()
    {
        $page = Cache::remember('page.terms', 3600, function () {
            return Page::where('slug', 'terms-and-conditions')->where('status', 'published')->first();
        });

        if (!$page) {
            $page = (object) [
                'title' => 'Terms and Conditions',
                'content' => $this->getDefaultTermsContent(),
                'meta_title' => 'Terms and Conditions - Education Platform',
                'meta_description' => 'Read our terms and conditions for using our education platform.',
            ];
        }

        return view('pages.terms', compact('page'));
    }

    /**
     * Display privacy policy
     */
    public function privacy()
    {
        $page = Cache::remember('page.privacy', 3600, function () {
            return Page::where('slug', 'privacy-policy')->where('status', 'published')->first();
        });

        if (!$page) {
            $page = (object) [
                'title' => 'Privacy Policy',
                'content' => $this->getDefaultPrivacyContent(),
                'meta_title' => 'Privacy Policy - Education Platform',
                'meta_description' => 'Learn about how we protect your privacy and handle your data.',
            ];
        }

        return view('pages.privacy', compact('page'));
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $page = Cache::remember('page.faq', 3600, function () {
            return Page::where('slug', 'faq')->where('status', 'published')->first();
        });

        if (!$page) {
            $page = (object) [
                'title' => 'Frequently Asked Questions',
                'content' => $this->getDefaultFaqContent(),
                'meta_title' => 'FAQ - Education Platform',
                'meta_description' => 'Find answers to commonly asked questions about our education platform.',
            ];
        }

        return view('pages.faq', compact('page'));
    }

    /**
     * Display how it works page
     */
    public function howItWorks()
    {
        $page = Cache::remember('page.how-it-works', 3600, function () {
            return Page::where('slug', 'how-it-works')->where('status', 'published')->first();
        });

        if (!$page) {
            $page = (object) [
                'title' => 'How It Works',
                'content' => $this->getDefaultHowItWorksContent(),
                'meta_title' => 'How It Works - Education Platform',
                'meta_description' => 'Learn how our platform connects students with teachers and institutes.',
            ];
        }

        return view('pages.how-it-works', compact('page'));
    }

    /**
     * Display careers page
     */
    public function careers()
    {
        $page = Cache::remember('page.careers', 3600, function () {
            return Page::where('slug', 'careers')->where('status', 'published')->first();
        });

        if (!$page) {
            $page = (object) [
                'title' => 'Careers',
                'content' => $this->getDefaultCareersContent(),
                'meta_title' => 'Careers - Education Platform',
                'meta_description' => 'Join our team and help us transform education.',
            ];
        }

        return view('pages.careers', compact('page'));
    }

    /**
     * Display a dynamic CMS page by slug
     */
    public function show($slug)
    {
        $page = Cache::remember("page.{$slug}", 3600, function () use ($slug) {
            return Page::where('slug', $slug)
                      ->where('status', 'published')
                      ->first();
        });

        if (!$page) {
            abort(404);
        }

        return view('pages.dynamic', compact('page'));
    }

    /**
     * Default about content
     */
    private function getDefaultAboutContent()
    {
        return '
        <div class="row">
            <div class="col-lg-8">
                <h2>About Our Education Platform</h2>
                <p class="lead">We are dedicated to connecting students with qualified teachers and reputable institutes to provide the best learning experience.</p>
                
                <h3>Our Mission</h3>
                <p>To democratize quality education by making it accessible to every student, regardless of their location or background. We believe that everyone deserves access to excellent teachers and educational resources.</p>
                
                <h3>Our Vision</h3>
                <p>To become the leading education platform that bridges the gap between students and educators, fostering a community of continuous learning and growth.</p>
                
                <h3>Why Choose Us?</h3>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Verified and qualified teachers</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Comprehensive institute profiles</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Easy booking and scheduling</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Secure payment processing</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>24/7 customer support</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Multi-language support</li>
                </ul>
            </div>
            <div class="col-lg-4">
                <img src="/images/about-us.jpg" alt="About Us" class="img-fluid rounded shadow">
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-lg-12">
                <h3>Our Team</h3>
                <p>We are a passionate team of educators, technologists, and innovators working together to transform the education landscape.</p>
            </div>
        </div>
        ';
    }

    /**
     * Default services content
     */
    private function getDefaultServicesContent()
    {
        return '
        <div class="row">
            <div class="col-lg-12">
                <h2>Our Services</h2>
                <p class="lead">Comprehensive education solutions for students, teachers, and institutes.</p>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-person-workspace display-4 text-primary mb-3"></i>
                        <h4>Find Teachers</h4>
                        <p>Connect with qualified teachers for personalized tutoring sessions.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-building display-4 text-success mb-3"></i>
                        <h4>Institute Directory</h4>
                        <p>Discover reputable educational institutes in your area.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check display-4 text-info mb-3"></i>
                        <h4>Easy Booking</h4>
                        <p>Simple and secure booking system for classes and sessions.</p>
                    </div>
                </div>
            </div>
        </div>
        ';
    }

    /**
     * Default terms content
     */
    private function getDefaultTermsContent()
    {
        return '
        <h2>Terms and Conditions</h2>
        <p><strong>Last updated:</strong> ' . date('F d, Y') . '</p>
        
        <h3>1. Acceptance of Terms</h3>
        <p>By using our platform, you agree to these terms and conditions.</p>
        
        <h3>2. Use of Platform</h3>
        <p>You may use our platform for lawful purposes only and in accordance with these terms.</p>
        
        <h3>3. User Accounts</h3>
        <p>Users are responsible for maintaining the confidentiality of their account information.</p>
        
        <h3>4. Privacy</h3>
        <p>Your privacy is important to us. Please review our Privacy Policy.</p>
        
        <h3>5. Contact Us</h3>
        <p>If you have any questions about these terms, please contact us.</p>
        ';
    }

    /**
     * Default privacy content
     */
    private function getDefaultPrivacyContent()
    {
        return '
        <h2>Privacy Policy</h2>
        <p><strong>Last updated:</strong> ' . date('F d, Y') . '</p>
        
        <h3>Information We Collect</h3>
        <p>We collect information you provide directly to us, such as when you create an account.</p>
        
        <h3>How We Use Your Information</h3>
        <p>We use the information we collect to provide, maintain, and improve our services.</p>
        
        <h3>Information Sharing</h3>
        <p>We do not sell, trade, or otherwise transfer your personal information to third parties.</p>
        
        <h3>Data Security</h3>
        <p>We implement appropriate security measures to protect your personal information.</p>
        
        <h3>Contact Us</h3>
        <p>If you have any questions about this Privacy Policy, please contact us.</p>
        ';
    }

    /**
     * Default FAQ content
     */
    private function getDefaultFaqContent()
    {
        return '
        <h2>Frequently Asked Questions</h2>
        
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        How do I find a teacher?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can search for teachers by subject, location, or use our advanced filters to find the perfect match.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        How do I book a session?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Once you find a teacher, you can view their availability and book a session directly through their profile.
                    </div>
                </div>
            </div>
            
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Is payment secure?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, we use industry-standard encryption and secure payment gateways to protect your transactions.
                    </div>
                </div>
            </div>
        </div>
        ';
    }

    /**
     * Default how it works content
     */
    private function getDefaultHowItWorksContent()
    {
        return '
        <h2>How It Works</h2>
        
        <div class="row mt-4">
            <div class="col-lg-4 text-center mb-4">
                <div class="step-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="h3 mb-0">1</span>
                </div>
                <h4>Search & Discover</h4>
                <p>Browse through our extensive database of qualified teachers and institutes.</p>
            </div>
            
            <div class="col-lg-4 text-center mb-4">
                <div class="step-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="h3 mb-0">2</span>
                </div>
                <h4>Connect & Book</h4>
                <p>Contact teachers directly and book sessions that fit your schedule.</p>
            </div>
            
            <div class="col-lg-4 text-center mb-4">
                <div class="step-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="h3 mb-0">3</span>
                </div>
                <h4>Learn & Grow</h4>
                <p>Attend classes and track your progress with our learning tools.</p>
            </div>
        </div>
        ';
    }

    /**
     * Default careers content
     */
    private function getDefaultCareersContent()
    {
        return '
        <h2>Join Our Team</h2>
        <p class="lead">Help us transform education and make a positive impact on millions of students worldwide.</p>
        
        <h3>Why Work With Us?</h3>
        <ul>
            <li>Competitive salary and benefits</li>
            <li>Flexible working hours</li>
            <li>Professional development opportunities</li>
            <li>Collaborative work environment</li>
            <li>Make a real impact in education</li>
        </ul>
        
        <h3>Current Openings</h3>
        <p>We are always looking for talented individuals to join our team. Send your resume to careers@example.com</p>
        ';
    }
} 