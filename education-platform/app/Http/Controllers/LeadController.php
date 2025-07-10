<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\TeacherProfile;
use App\Models\Institute;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    // =======================
    // PUBLIC LEAD FORMS
    // =======================
    
    public function teacherInquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject_id' => 'required|exists:subjects,id',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'preferred_location' => 'nullable|string|max:255',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'teaching_mode' => 'required|in:online,offline,both',
            'timing_preference' => 'nullable|string|max:255',
            'start_date' => 'nullable|date|after_or_equal:today',
            'urgency' => 'required|in:immediate,within_week,within_month,flexible',
            'student_level' => 'nullable|string|max:100',
            'additional_requirements' => 'nullable|string|max:1000',
            'source' => 'nullable|string|max:50',
            'utm_source' => 'nullable|string|max:100',
            'utm_medium' => 'nullable|string|max:100',
            'utm_campaign' => 'nullable|string|max:100',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $lead = $this->createLead($request->all(), 'teacher_inquiry');
            
            // Send notification emails
            $this->sendLeadNotifications($lead);
            
            // Try to auto-match with teachers
            $matchedTeachers = $this->findMatchingTeachers($lead);
            
            return response()->json([
                'success' => true,
                'message' => 'Your teacher inquiry has been submitted successfully! We will contact you soon.',
                'lead_id' => $lead->id,
                'matched_teachers' => $matchedTeachers->count(),
                'estimated_response_time' => $this->getEstimatedResponseTime($lead->urgency)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lead creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
    
    public function instituteInquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'institute_type' => 'required|in:school,college,coaching,university,training_center',
            'looking_for' => 'required|in:admission,course_info,fees,facilities,placement',
            'course_interested' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'preferred_start_date' => 'nullable|date|after_or_equal:today',
            'student_level' => 'nullable|string|max:100',
            'previous_education' => 'nullable|string|max:255',
            'career_goals' => 'nullable|string|max:500',
            'additional_requirements' => 'nullable|string|max:1000',
            'source' => 'nullable|string|max:50',
            'utm_source' => 'nullable|string|max:100',
            'utm_medium' => 'nullable|string|max:100',
            'utm_campaign' => 'nullable|string|max:100',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $lead = $this->createLead($request->all(), 'institute_inquiry');
            
            // Send notification emails
            $this->sendLeadNotifications($lead);
            
            // Try to auto-match with institutes
            $matchedInstitutes = $this->findMatchingInstitutes($lead);
            
            return response()->json([
                'success' => true,
                'message' => 'Your institute inquiry has been submitted successfully! We will contact you soon.',
                'lead_id' => $lead->id,
                'matched_institutes' => $matchedInstitutes->count(),
                'estimated_response_time' => $this->getEstimatedResponseTime($lead->urgency ?? 'flexible')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lead creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
    
    public function generalInquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'inquiry_type' => 'required|in:general,support,partnership,feedback,other',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'city' => 'nullable|string|max:100',
            'preferred_contact_method' => 'required|in:email,phone,whatsapp',
            'best_time_to_call' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $lead = $this->createLead($request->all(), 'general_inquiry');
            
            // Send notification emails
            $this->sendLeadNotifications($lead);
            
            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been submitted successfully! We will get back to you soon.',
                'lead_id' => $lead->id,
                'estimated_response_time' => '1-2 business days'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lead creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
    
    public function demoRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'demo_type' => 'required|in:teacher_demo,institute_demo,platform_demo',
            'subject_id' => 'required_if:demo_type,teacher_demo|exists:subjects,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string|max:50',
            'demo_mode' => 'required|in:online,offline',
            'city' => 'required_if:demo_mode,offline|string|max:100',
            'student_level' => 'nullable|string|max:100',
            'specific_requirements' => 'nullable|string|max:500',
            'source' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $lead = $this->createLead($request->all(), 'demo_request');
            
            // Send notification emails
            $this->sendLeadNotifications($lead);
            
            return response()->json([
                'success' => true,
                'message' => 'Your demo request has been submitted successfully! We will confirm your demo slot soon.',
                'lead_id' => $lead->id,
                'estimated_confirmation_time' => '4-6 hours'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lead creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
    
    public function callbackRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'preferred_time' => 'required|in:morning,afternoon,evening,anytime',
            'topic' => 'required|string|max:255',
            'urgency' => 'required|in:immediate,today,tomorrow,this_week',
            'source' => 'nullable|string|max:50',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $lead = $this->createLead($request->all(), 'callback_request');
            
            // Send immediate notification for callback requests
            $this->sendUrgentLeadNotification($lead);
            
            return response()->json([
                'success' => true,
                'message' => 'Your callback request has been submitted! We will call you back soon.',
                'lead_id' => $lead->id,
                'estimated_callback_time' => $this->getCallbackTime($request->urgency)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Lead creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
    
    // =======================
    // LEAD TRACKING
    // =======================
    
    public function trackLead($leadId)
    {
        $lead = Lead::where('id', $leadId)
            ->orWhere('tracking_id', $leadId)
            ->first();
        
        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'lead' => [
                'id' => $lead->tracking_id,
                'status' => $lead->status,
                'created_at' => $lead->created_at->format('M d, Y H:i'),
                'last_updated' => $lead->updated_at->format('M d, Y H:i'),
                'estimated_response_time' => $this->getEstimatedResponseTime($lead->urgency ?? 'flexible'),
                'next_steps' => $this->getNextSteps($lead),
                'contact_attempts' => $lead->contact_attempts ?? 0,
                'last_contact_at' => $lead->last_contact_at ? $lead->last_contact_at->format('M d, Y H:i') : null,
            ]
        ]);
    }
    
    public function updateLeadStatus(Request $request, $leadId)
    {
        if (!auth()->check() || !auth()->user()->roles->whereIn('name', ['admin', 'manager'])->count()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $lead = Lead::findOrFail($leadId);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,contacted,qualified,converted,lost,invalid',
            'notes' => 'nullable|string|max:1000',
            'next_followup_at' => 'nullable|date|after:now',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $oldStatus = $lead->status;
        $lead->update($request->only(['status', 'notes', 'next_followup_at']));
        
        // Log status change
        $lead->addToHistory([
            'action' => 'status_changed',
            'old_status' => $oldStatus,
            'new_status' => $lead->status,
            'user' => auth()->user()->name,
            'notes' => $request->notes,
            'timestamp' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully',
            'lead' => $lead->fresh()
        ]);
    }
    
    // =======================
    // HELPER METHODS
    // =======================
    
    private function createLead(array $data, string $leadType)
    {
        // Generate tracking ID
        $trackingId = 'LEAD-' . strtoupper(Str::random(8));
        
        // Calculate lead score
        $leadScore = $this->calculateLeadScore($data, $leadType);
        
        // Determine priority
        $priority = $this->determinePriority($data, $leadScore);
        
        // Create lead
        $lead = Lead::create([
            'lead_type' => $leadType,
            'tracking_id' => $trackingId,
            'status' => 'new',
            'priority' => $priority,
            'lead_score' => $leadScore,
            
            // Personal Information
            'full_name' => $data['full_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            
            // Lead Source
            'source' => $data['source'] ?? 'website',
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'utm_content' => $data['utm_content'] ?? null,
            'utm_term' => $data['utm_term'] ?? null,
            'referrer_url' => request()->headers->get('referer'),
            'user_agent' => request()->userAgent(),
            'ip_address' => request()->ip(),
            
            // Lead Details
            'subject_id' => $data['subject_id'] ?? null,
            'budget_min' => $data['budget_min'] ?? null,
            'budget_max' => $data['budget_max'] ?? null,
            'urgency' => $data['urgency'] ?? 'flexible',
            'requirements' => $this->formatRequirements($data, $leadType),
            
            // Additional Data
            'lead_data' => json_encode($data),
            'expires_at' => $this->calculateExpiryDate($data['urgency'] ?? 'flexible'),
        ]);
        
        // Initialize history
        $lead->addToHistory([
            'action' => 'created',
            'user' => 'system',
            'timestamp' => now()
        ]);
        
        return $lead;
    }
    
    private function calculateLeadScore(array $data, string $leadType): int
    {
        $score = 0;
        
        // Base score by lead type
        $baseScores = [
            'teacher_inquiry' => 60,
            'institute_inquiry' => 70,
            'demo_request' => 80,
            'callback_request' => 90,
            'general_inquiry' => 40,
        ];
        
        $score += $baseScores[$leadType] ?? 50;
        
        // Budget scoring
        if (isset($data['budget_max'])) {
            if ($data['budget_max'] >= 5000) {
                $score += 20;
            } elseif ($data['budget_max'] >= 2000) {
                $score += 15;
            } elseif ($data['budget_max'] >= 1000) {
                $score += 10;
            } else {
                $score += 5;
            }
        }
        
        // Urgency scoring
        $urgencyScores = [
            'immediate' => 25,
            'within_week' => 20,
            'today' => 25,
            'tomorrow' => 20,
            'within_month' => 15,
            'this_week' => 15,
            'flexible' => 5,
        ];
        
        $score += $urgencyScores[$data['urgency'] ?? 'flexible'];
        
        // Source quality scoring
        $sourceScores = [
            'google_ads' => 15,
            'facebook_ads' => 12,
            'referral' => 20,
            'direct' => 18,
            'organic' => 10,
            'social' => 8,
        ];
        
        $score += $sourceScores[$data['source'] ?? 'website'] ?? 5;
        
        // Complete profile scoring
        $completenessScore = 0;
        $fields = ['full_name', 'email', 'phone', 'city'];
        foreach ($fields as $field) {
            if (!empty($data[$field])) {
                $completenessScore += 2;
            }
        }
        $score += $completenessScore;
        
        return min(100, max(0, $score));
    }
    
    private function determinePriority(array $data, int $leadScore): string
    {
        if ($leadScore >= 80) {
            return 'high';
        } elseif ($leadScore >= 60) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    private function formatRequirements(array $data, string $leadType): array
    {
        $requirements = [];
        
        switch ($leadType) {
            case 'teacher_inquiry':
                $requirements = [
                    'teaching_mode' => $data['teaching_mode'] ?? null,
                    'timing_preference' => $data['timing_preference'] ?? null,
                    'student_level' => $data['student_level'] ?? null,
                    'start_date' => $data['start_date'] ?? null,
                    'preferred_location' => $data['preferred_location'] ?? null,
                    'additional_requirements' => $data['additional_requirements'] ?? null,
                ];
                break;
                
            case 'institute_inquiry':
                $requirements = [
                    'institute_type' => $data['institute_type'] ?? null,
                    'looking_for' => $data['looking_for'] ?? null,
                    'course_interested' => $data['course_interested'] ?? null,
                    'preferred_start_date' => $data['preferred_start_date'] ?? null,
                    'student_level' => $data['student_level'] ?? null,
                    'previous_education' => $data['previous_education'] ?? null,
                    'career_goals' => $data['career_goals'] ?? null,
                    'additional_requirements' => $data['additional_requirements'] ?? null,
                ];
                break;
                
            case 'demo_request':
                $requirements = [
                    'demo_type' => $data['demo_type'] ?? null,
                    'preferred_date' => $data['preferred_date'] ?? null,
                    'preferred_time' => $data['preferred_time'] ?? null,
                    'demo_mode' => $data['demo_mode'] ?? null,
                    'student_level' => $data['student_level'] ?? null,
                    'specific_requirements' => $data['specific_requirements'] ?? null,
                ];
                break;
                
            case 'callback_request':
                $requirements = [
                    'preferred_time' => $data['preferred_time'] ?? null,
                    'topic' => $data['topic'] ?? null,
                ];
                break;
                
            case 'general_inquiry':
                $requirements = [
                    'inquiry_type' => $data['inquiry_type'] ?? null,
                    'subject' => $data['subject'] ?? null,
                    'message' => $data['message'] ?? null,
                    'preferred_contact_method' => $data['preferred_contact_method'] ?? null,
                    'best_time_to_call' => $data['best_time_to_call'] ?? null,
                ];
                break;
        }
        
        return array_filter($requirements, function($value) {
            return !is_null($value) && $value !== '';
        });
    }
    
    private function calculateExpiryDate(string $urgency): ?\DateTime
    {
        $expiryDays = [
            'immediate' => 3,
            'today' => 1,
            'tomorrow' => 2,
            'within_week' => 7,
            'this_week' => 7,
            'within_month' => 30,
            'flexible' => 90,
        ];
        
        $days = $expiryDays[$urgency] ?? 30;
        return now()->addDays($days);
    }
    
    private function findMatchingTeachers(Lead $lead)
    {
        $query = TeacherProfile::where('verified', true)
            ->where('is_active', true)
            ->where('is_available', true);
        
        // Match by subject
        if ($lead->subject_id) {
            $query->whereHas('subjects', function($q) use ($lead) {
                $q->where('subjects.id', $lead->subject_id);
            });
        }
        
        // Match by location
        if ($lead->city) {
            $query->whereHas('user', function($q) use ($lead) {
                $q->where('city', 'like', "%{$lead->city}%");
            });
        }
        
        // Match by budget
        $requirements = $lead->requirements;
        if (isset($requirements['budget_max'])) {
            $query->where('hourly_rate', '<=', $requirements['budget_max']);
        }
        
        // Match by teaching mode
        if (isset($requirements['teaching_mode']) && $requirements['teaching_mode'] !== 'both') {
            $query->where(function($q) use ($requirements) {
                $q->where('teaching_mode', $requirements['teaching_mode'])
                  ->orWhere('teaching_mode', 'both');
            });
        }
        
        return $query->take(5)->get();
    }
    
    private function findMatchingInstitutes(Lead $lead)
    {
        $query = Institute::where('verified', true)
            ->where('is_active', true);
        
        // Match by location
        if ($lead->city) {
            $query->where('city', 'like', "%{$lead->city}%");
        }
        
        $requirements = $lead->requirements;
        
        // Match by institute type
        if (isset($requirements['institute_type'])) {
            $query->where('institute_type', $requirements['institute_type']);
        }
        
        return $query->take(5)->get();
    }
    
    private function sendLeadNotifications(Lead $lead)
    {
        // Send email to admins
        // Mail::to(config('app.admin_email'))->send(new NewLeadNotification($lead));
        
        // Send SMS notification for urgent leads
        if ($lead->priority === 'high') {
            // Send SMS notification
        }
        
        // Send confirmation email to lead
        if ($lead->email) {
            // Mail::to($lead->email)->send(new LeadConfirmationEmail($lead));
        }
    }
    
    private function sendUrgentLeadNotification(Lead $lead)
    {
        // Send immediate notifications for urgent leads
        // Implement real-time notifications, SMS, etc.
    }
    
    private function getEstimatedResponseTime(string $urgency): string
    {
        $times = [
            'immediate' => '30 minutes - 2 hours',
            'today' => '2-6 hours',
            'tomorrow' => '4-12 hours',
            'within_week' => '1-2 business days',
            'this_week' => '1-2 business days',
            'within_month' => '2-3 business days',
            'flexible' => '1-3 business days',
        ];
        
        return $times[$urgency] ?? '1-3 business days';
    }
    
    private function getCallbackTime(string $urgency): string
    {
        $times = [
            'immediate' => '15-30 minutes',
            'today' => '1-4 hours',
            'tomorrow' => '24-48 hours',
            'this_week' => '1-3 business days',
        ];
        
        return $times[$urgency] ?? '1-3 business days';
    }
    
    private function getNextSteps(Lead $lead): array
    {
        switch ($lead->status) {
            case 'new':
                return [
                    'Our team will review your inquiry within the estimated response time',
                    'We will match you with suitable options based on your requirements',
                    'You will receive a call or email with recommendations'
                ];
                
            case 'contacted':
                return [
                    'Our team has contacted you',
                    'We are working on finding the best matches for your requirements',
                    'Please check your email and phone for updates'
                ];
                
            case 'qualified':
                return [
                    'Your requirements have been qualified',
                    'We are arranging meetings with matched teachers/institutes',
                    'You will receive contact details and can proceed with discussions'
                ];
                
            case 'converted':
                return [
                    'Congratulations! Your inquiry has been successfully fulfilled',
                    'Please provide feedback on our service',
                    'We are here to help with any future requirements'
                ];
                
            default:
                return ['Please contact our support team for more information'];
        }
    }
    
    // =======================
    // API ENDPOINTS
    // =======================
    
    public function getSubjects()
    {
        $subjects = Subject::where('is_active', true)
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();
        
        return response()->json($subjects);
    }
    
    public function getCities()
    {
        // Return popular cities or all cities
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Pune', 'Ahmedabad', 'Jaipur', 'Lucknow'];
        
        return response()->json($cities);
    }
    
    public function getStates()
    {
        $states = ['Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'West Bengal', 'Telangana', 'Gujarat', 'Rajasthan', 'Uttar Pradesh'];
        
        return response()->json($states);
    }
}
